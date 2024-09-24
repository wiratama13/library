<?php

use App\Models\Author;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\HomeController;

use App\Http\Controllers\MemberController;
use App\Http\Controllers\PublisherController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\TransactionController;

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


Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login')->middleware('guest');

Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register-form');
Route::post('/register', [AuthController::class, 'register'])->name('register');


// Route::group(['auth:sanctum', ], function() {
   
// });

Route::middleware(['auth:sanctum','check.token.expiration'])->group(function () {
    Route::get('/', [HomeController::class, 'index'])->name('dashboard');
    Route::delete('/api/book-delete/{id}', [BookController::class, 'apiDelete']);
    Route::post('/api/book-store', [BookController::class, 'apiStore']);
    Route::get('/api/get-book/{id}', [
        BookController::class,
        'apiGetBook'
    ]);

    Route::resource('/transactions', TransactionController::class);
    Route::resource('/books', BookController::class);

    Route::resource('/members', MemberController::class);

    
});



