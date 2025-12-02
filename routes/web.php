<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FeedbackController;


// --- Route Pengunjung (Tidak Dilindungi) ---
// Route untuk menampilkan form feedback (Alur 1)
Route::get('/', [FeedbackController::class, 'create'])->name('feedback.create');
// Route untuk memproses pengiriman feedback (Alur 2)
Route::post('/feedback', [FeedbackController::class, 'store'])->name('feedback.store');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
