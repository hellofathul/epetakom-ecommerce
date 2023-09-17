<?php

/** Admin Routes */

use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Backend\AdminController;
use Illuminate\Support\Facades\Route;

Route::get('dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

/** Profile Routes */
Route::get('profile', [ProfileController::class, 'index'])->name('profile');
Route::post('profile/update', [ProfileController::class, 'updateProfile'])->name('profile.update');
Route::post('profile/update/password', [ProfileController::class, 'updatePassword'])->name('password.update');