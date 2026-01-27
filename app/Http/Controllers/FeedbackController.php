<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Feedback;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use App\Mail\FeedbackResponded;

class FeedbackController extends Controller
{
    /**
     * Tampilkan daftar semua feedback kepada Admin di halaman dashboard.
     */
    public function index()
    {
        // Ambil semua feedback, diurutkan dari yang terbaru, menggunakan pagination
        $feedbacks = Feedback::latest()->paginate(10); 

        // Hitung statistik sederhana
        $stats = [
            'total' => Feedback::count(),
            'average_rating' => round(Feedback::avg('rating'), 1) ?: 0,
            'pending' => Feedback::where('status', 'Pending')->count(),
            'responded' => Feedback::where('status', 'Responded')->count(),
        ];

        // Menggunakan view 'dashboard' yang sudah ada dari Breeze
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
        Feedback::create([
            'visitor_name' => $validatedData['visitor_name'],
            'visitor_email' => $validatedData['visitor_email'],
            'rating' => $validatedData['rating'],
            'comment' => $validatedData['comment'] ?? null,
            'photo_path' => $photoPath,
            'status' => 'Pending',
        ]);

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
