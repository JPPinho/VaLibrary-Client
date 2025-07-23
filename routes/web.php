<?php

use App\Http\Controllers\Books\BooksController;
use App\Http\Controllers\Dashboard\DashboardController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;


Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);

Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/books', [BooksController::class, 'index'])->name('books.index');

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware('auth')
    ->name('dashboard');
