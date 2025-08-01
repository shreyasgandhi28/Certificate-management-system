<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Public Application Routes
Route::prefix('apply')->name('apply.')->group(function () {
    Route::get('/', [App\Http\Controllers\Public\FormController::class, 'create'])->name('create');
    Route::get('/{token}', [App\Http\Controllers\Public\FormController::class, 'show'])->name('show');
    Route::post('/', [App\Http\Controllers\Public\FormController::class, 'store'])->name('store');
    Route::get('/{token}/success', [App\Http\Controllers\Public\FormController::class, 'success'])->name('success');
});

require __DIR__.'/auth.php';
