<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Feedback;
use Illuminate\Support\Facades\Storage;

class FeedbackController extends Controller
{
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

}
