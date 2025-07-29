<?php

use App\Http\Controllers\Books\BooksController;
use App\Http\Controllers\Books\ScannerController;
use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\InvitationCodes\InvitationCodesController;
use App\Http\Controllers\LoanController;
use App\Http\Controllers\LoanRequestController;
use App\Http\Controllers\Notes\NoteController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\User\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;


Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::get('/register', [RegisterController::class, 'create'])->name('register');
Route::post('/register', [RegisterController::class, 'store']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
Route::get('/books/scan', function () {
    return view('books.scan');
})->name('books.scan');
Route::resource('books', BooksController::class)->middleware('auth');
Route::resource('loans', LoanController::class)->middleware('auth');
Route::resource('users', UserController::class)->middleware('auth');
Route::resource('loan-requests', LoanRequestController::class)->middleware('auth');
Route::resource('notes', NoteController::class)->middleware('auth');
Route::get('/notes/create-with-image/{book}', [NoteController::class, 'createImageNote'])->name('notes.createImage');
Route::post('/notes/create-with-image/{book}', [NoteController::class, 'storeImageNote'])->name('notes.storeImage');
Route::get('invitation-code', [InvitationCodesController::class, 'show'])->name('invitation-code.show');
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware('auth')
    ->name('dashboard');


Route::post('/books/scan', [ScannerController::class, 'store'])->name('books.storeFromIsbn');
