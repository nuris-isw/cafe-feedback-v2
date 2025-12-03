<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FeedbackController;


// --- Route Pengunjung (Tidak Dilindungi) ---
// Route untuk menampilkan form feedback (Alur 1)
Route::get('/', [FeedbackController::class, 'create'])->name('feedback.create');
// Route untuk memproses pengiriman feedback (Alur 2)
Route::post('/feedback', [FeedbackController::class, 'store'])->name('feedback.store');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [FeedbackController::class, 'index'])->name('dashboard');
    // Menampilkan detail feedback spesifik
    Route::get('/feedbacks/{feedback}', [FeedbackController::class, 'show'])->name('feedbacks.show');
    // Memproses pengiriman respon admin
    Route::patch('/feedbacks/{feedback}/respond', [FeedbackController::class, 'respond'])->name('feedbacks.respond');
    
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
