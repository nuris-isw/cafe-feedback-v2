<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\FeedbackController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Routes (Akses Pengunjung)
|--------------------------------------------------------------------------
*/

// Form pengisian feedback (Alur 1)
Route::get('/', [FeedbackController::class, 'create'])->name('feedback.create');

// Proses simpan feedback (Alur 2)
Route::post('/feedback', [FeedbackController::class, 'store'])->name('feedback.store');


/*
|--------------------------------------------------------------------------
| Protected Routes (Akses Admin & Owner)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'verified'])->group(function () {
    
    // Dashboard & Manajemen Feedback
    Route::get('/dashboard', [FeedbackController::class, 'index'])->name('dashboard');
    Route::get('/feedbacks/{feedback}', [FeedbackController::class, 'show'])->name('feedbacks.show');
    Route::patch('/feedbacks/{feedback}/respond', [FeedbackController::class, 'respond'])->name('feedbacks.respond');

    // --- FITUR EXPORT (Baru) ---
    // Route export dengan nama yang sesuai dengan yang kita panggil di Blade
    Route::get('/admin/export-excel', [FeedbackController::class, 'exportExcel'])->name('admin.export-excel');
    Route::get('/admin/export-pdf', [FeedbackController::class, 'exportPdf'])->name('admin.export-pdf');

    // Profile Management (Breeze Default)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';