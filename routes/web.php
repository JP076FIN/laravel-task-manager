<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

// Task list shown on home and named as tasks.index
Route::get('/home', [TaskController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('home');


Route::middleware('auth')->group(function () {
    // Register all task resource routes except index (already defined as /home)
    Route::resource('tasks', TaskController::class)->except(['index']);

    // Profile routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Error testing

Route::get('/force-404', function () {
    abort(404);
});


Route::get('/force-500', function () {
    abort(500);
});

Route::fallback(function () {
    abort(404);
});


require __DIR__.'/auth.php';
