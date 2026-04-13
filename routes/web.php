<?php

use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminMediaController;
use App\Http\Controllers\Admin\AdminPostController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Public\PublicController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PublicController::class, 'home'])->name('home');
Route::get('/explore', [PublicController::class, 'explore'])->name('explore');
Route::get('/categories', [PublicController::class, 'categories'])->name('categories.index');
Route::get('/categories/{category:slug}', [PublicController::class, 'category'])->name('categories.show');
Route::get('/posts/{post:slug}', [PublicController::class, 'show'])->name('posts.show');
Route::get('/about', [PublicController::class, 'about'])->name('about');
Route::get('/contact', [PublicController::class, 'contact'])->name('contact');
Route::post('/contact', [PublicController::class, 'sendContact'])->name('contact.send');

Route::prefix('admin')->name('admin.')->group(function () {
    Route::middleware('guest')->group(function () {
        Route::get('/login', [AdminAuthController::class, 'create'])->name('login');
        Route::post('/login', [AdminAuthController::class, 'store'])->middleware('throttle:6,1')->name('login.store');
    });

    Route::middleware(['auth', 'admin'])->group(function () {
        Route::post('/logout', [AdminAuthController::class, 'destroy'])->name('logout');
        Route::get('/dashboard', AdminDashboardController::class)->name('dashboard');
        Route::resource('posts', AdminPostController::class)->except('show');
        Route::get('/media', [AdminMediaController::class, 'index'])->name('media.index');
        Route::put('/media/{media}', [AdminMediaController::class, 'update'])->name('media.update');
        Route::delete('/media/{media}', [AdminMediaController::class, 'destroy'])->name('media.destroy');
        Route::get('/admins', [AdminUserController::class, 'index'])->name('admins.index');
        Route::post('/admins', [AdminUserController::class, 'store'])->name('admins.store');
        Route::put('/admins/{admin}', [AdminUserController::class, 'update'])->name('admins.update');
    });
});
