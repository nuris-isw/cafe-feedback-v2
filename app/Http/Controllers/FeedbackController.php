<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Feedback;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use App\Mail\FeedbackResponded;
use App\Mail\AdminNewFeedback;
use App\Exports\FeedbackExport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class FeedbackController extends Controller
{
    /**
     * Tampilkan daftar semua feedback kepada Admin di halaman dashboard.
     */
    public function index(Request $request)
    {
        // 1. Mulai Query Dasar
        $query = Feedback::query();

        // 2. Terapkan Filter (Gunakan filled() agar lebih ringkas)
        if ($request->filled('rating')) {
            $query->where('rating', $request->rating);
        }

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('created_at', [
                $request->start_date . ' 00:00:00', 
                $request->end_date . ' 23:59:59'
            ]);
        }

        // 3. Statistik Dinamis (Mengikuti Filter)
        // Gunakan clone agar query statistik tidak merusak query utama untuk pagination
        $stats = [
            'total' => (clone $query)->count(),
            'average_rating' => round((clone $query)->avg('rating'), 1) ?: 0,
            'pending' => (clone $query)->where('status', 'Pending')->count(),
            'responded' => (clone $query)->where('status', 'Responded')->count(),
        ];

        // 4. Eksekusi Query Utama untuk Tabel
        $feedbacks = $query->latest()->paginate(10)->withQueryString();

        return view('dashboard', compact('feedbacks', 'stats'));
    }

    /**
     * Tampilkan form feedback kepada pengunjung.
     */
    public function create()
    {
        // View ini akan kita buat di sub-langkah berikutnya
        return view('feedback-form');
    }

    /**
     * Metode store akan diimplementasikan di Langkah 5.
     */
    public function store(Request $request)
    {
        // 1. Validasi Data
        $validatedData = $request->validate([
            'visitor_name' => 'required|string|max:255',
            'visitor_email' => 'required|email|max:255',
            'rating' => 'required|integer|min:1|max:5',
            'category' => 'required|string|in:Rasa,Pelayanan,Suasana,Kebersihan,Harga,Fasilitas',
            'comment' => 'nullable|string|max:1000',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $photoPath = null;

        // 2. Upload Foto (Jika Ada)
        if ($request->hasFile('photo')) {
            $file = $request->file('photo');
            $filename = time() . '_' . $file->getClientOriginalName();

            // Simpan menggunakan storeAs untuk menghindari bug Windows
            $photoPath = $file->storeAs('feedback_photos', $filename, 'public');
        }

        // 3. Simpan ke Database
        $feedback = Feedback::create([
            'visitor_name' => $validatedData['visitor_name'],
            'visitor_email' => $validatedData['visitor_email'],
            'rating' => $validatedData['rating'],
            'category' => $validatedData['category'],
            'comment' => $validatedData['comment'] ?? null,
            'photo_path' => $photoPath,
            'status' => 'Pending',
        ]);

        // 4. KIRIM NOTIFIKASI KE SEMUA ADMIN/USER
        try {
            // Mengambil semua email dari tabel users
            $adminEmails = \App\Models\User::pluck('email')->toArray();
            
            if (!empty($adminEmails)) {
                Mail::to($adminEmails)->send(new AdminNewFeedback($feedback));
            }
        } catch (\Exception $e) {
            \Log::error("Gagal kirim notifikasi admin: " . $e->getMessage());
        }

        // 4. Redirect dan Tampilkan Pesan Sukses
        return redirect()
            ->route('feedback.create')
            ->with('success', 'Terima kasih atas feedback Anda! Kami akan segera menindaklanjutinya.');
    }

    /**
     * Tampilkan detail feedback tertentu.
     */
    public function show(Feedback $feedback) // Menggunakan Route Model Binding
    {
        return view('admin-feedback-detail', compact('feedback'));
    }

    /**
     * Memproses respon Admin, update status, dan menyimpan data respon.
     */
    public function respond(Request $request, Feedback $feedback)
    {
        // 1. Validasi Respon
        $validatedData = $request->validate([
            'response_text' => 'required|string|max:500', // Sesuai batasan di view
        ]);

        // 2. Update Data Feedback (Alur 5)
        $feedback->update([
            'admin_response' => $validatedData['response_text'],
            'status' => 'Responded', // Tandai sudah direspon
            'responded_at' => Carbon::now(), // Catat waktu respon
        ]);
        
        // Kirim Email via Gmail SMTP
        try {
            Mail::to($feedback->visitor_email)->send(new FeedbackResponded($feedback));
            $msg = 'Respon berhasil dikirim dan email notifikasi telah diteruskan.';
        } catch (\Exception $e) {
            // Log error untuk pengecekan Admin
            \Log::error("Gagal kirim email Gmail: " . $e->getMessage());
            $msg = 'Respon disimpan di sistem, namun email gagal terkirim (Cek koneksi SMTP).';
        }

        return redirect()->route('dashboard')->with('success', $msg);
    }

    public function exportExcel(Request $request) 
    {
        // Mengambil filter yang sedang aktif
        $filters = $request->only(['rating', 'category', 'status']);
        
        // Nama file dinamis
        $fileName = 'Feedback_Anora_' . now()->format('Y-m-d_His') . '.xlsx';
        
        return Excel::download(new FeedbackExport($filters), $fileName);
    }

    public function exportPdf(Request $request)
    {
        $filters = $request->only(['rating', 'category', 'status', 'start_date', 'end_date']);
        
        // 1. Inisialisasi Query Dasar
        $query = Feedback::query();

        // 2. Terapkan Filter (Termasuk Tanggal)
        if (!empty($filters['rating'])) {
            $query->where('rating', $filters['rating']);
        }
        if (!empty($filters['category'])) {
            $query->where('category', $filters['category']);
        }
        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }
        if (!empty($filters['start_date']) && !empty($filters['end_date'])) {
            $query->whereBetween('created_at', [
                $filters['start_date'] . ' 00:00:00', 
                $filters['end_date'] . ' 23:59:59'
            ]);
        }

        // 3. Data Utama untuk Tabel
        $feedbacks = (clone $query)->latest()->get();

        // 4. Statistik Global Dinamis (Mengikuti Filter)
        $stats = [
            'total'          => (clone $query)->count(),
            'average_rating' => round((clone $query)->avg('rating'), 1) ?: 0,
            'pending'        => (clone $query)->where('status', 'Pending')->count(),
            'responded'      => (clone $query)->where('status', 'Responded')->count(),
        ];

        // 5. Statistik Per Kategori Dinamis
        $categories = ['Rasa', 'Pelayanan', 'Suasana', 'Kebersihan', 'Harga', 'Fasilitas'];
        $categoryStats = [];
        foreach ($categories as $cat) {
            $categoryStats[$cat] = round((clone $query)->where('category', $cat)->avg('rating'), 1) ?: 0;
        }

        // 6. Generate PDF
        $pdf = Pdf::loadView('emails.pdf-report', [
            'feedbacks'     => $feedbacks,
            'stats'         => $stats,
            'categoryStats' => $categoryStats,
            'date'          => date('d F Y, H:i'),
            'period'        => (!empty($filters['start_date'])) 
                            ? $filters['start_date'] . ' s/d ' . $filters['end_date'] 
                            : 'Semua Waktu',
        ]);

        return $pdf->setPaper('a4', 'landscape')->download('Laporan_Feedback_Anora.pdf');
    }

}
