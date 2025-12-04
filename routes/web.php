<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// Redirect the root URL to dashboard
Route::get('/', function () {
    return redirect()->route('dashboard');
});

// Dashboard route for authenticated users
Route::get('/dashboard', function () {
    return view('dashboard'); // your dashboard Blade
})->middleware(['auth', 'verified'])->name('dashboard');

// Profile management routes for authenticated users
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Authentication routes
require __DIR__.'/auth.php';
