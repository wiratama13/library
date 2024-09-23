<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\TransactionController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });
// Route::middleware('auth:sanctum')


Route::post('/login', [AuthController::class, 'login'])->name('login-post')->middleware('guest');
// Route::get('/form/login', [AuthController::class, 'showLoginForm'])->name('login-form')->middleware('guest');
Route::middleware(['auth:sanctum','check.token.expiration'])->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    Route::get('transactions', [TransactionController::class, 'api'])->name('transaction.get');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('books', [BookController::class, 'api']);

    Route::get('members', [MemberController::class, 'api'])->name('member-api');
});