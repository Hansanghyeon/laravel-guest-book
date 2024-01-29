<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [CommentController::class, 'index'])->name('home');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/auth/github/redirect', [AuthController::class, 'github_redirect'])->name('auth.github.redirect');
Route::get('/auth/github/callback', [AuthController::class, 'github_callback']);
Route::get('/auth/google/redirect', [AuthController::class, 'google_redirect'])->name('auth.google.redirect');
Route::get('/auth/google/callback', [AuthController::class, 'google_callback']);

Route::post('/comments/store', [CommentController::class, 'store'])->name('comments.add');

require __DIR__ . '/auth.php';
