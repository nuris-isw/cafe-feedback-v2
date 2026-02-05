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

class FeedbackController extends Controller
{
    /**
     * Tampilkan daftar semua feedback kepada Admin di halaman dashboard.
     */
    public function index(Request $request)
    {
        // 1. Mulai Query
        $query = Feedback::query();

        // 2. Filter berdasarkan Rating
        if ($request->has('rating') && $request->rating != '') {
            $query->where('rating', $request->rating);
        }

        // 3. Filter berdasarkan Kategori
        if ($request->has('category') && $request->category != '') {
            $query->where('category', $request->category);
        }

        // 4. Filter berdasarkan Status
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        // 5. Eksekusi dengan Pagination (Tetap membawa parameter filter saat pindah halaman)
        $feedbacks = $query->latest()->paginate(10)->withQueryString();

        // Statistik tetap mengambil data keseluruhan atau bisa juga disesuaikan dengan filter
        $stats = [
            'total' => Feedback::count(),
            'average_rating' => round(Feedback::avg('rating'), 1) ?: 0,
            'pending' => Feedback::where('status', 'Pending')->count(),
            'responded' => Feedback::where('status', 'Responded')->count(),
        ];

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

}
