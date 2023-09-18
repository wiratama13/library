<?php

use App\Models\Author;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AuthorController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\CatalogController;
use App\Http\Controllers\PublisherController;
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

// Route::get('/', function () {
//     return view('welcome');
// });

Auth::routes();

Route::group(['middleware' => 'auth'], function() {
    
    Route::get('/', [HomeController::class, 'index'])->name('dashboard');
    Route::get('/author', [AuthorController::class, 'index'])->name('author');

    Route::get('/catalog', [CatalogController::class, 'index'])->name('catalog');
    Route::get('/catalog/create', [CatalogController::class, 'create'])->name('catalog-create');
    Route::post('/catalog/store', [CatalogController::class, 'store'])->name('catalog-store');
    Route::get('/catalog/edit/{id}', [CatalogController::class, 'edit'])->name('catalog-edit');
    Route::post('/catalog/update/{id}', [CatalogController::class, 'update'])->name('catalog-update');
    Route::delete('/catalog/destroy/{id}', [CatalogController::class, 'destroy'])->name('catalog-delete');

    Route::resource('/publishers', PublisherController::class);
    Route::get('api/publishers', [PublisherController::class, 'api']);
    
    Route::resource('/authors', AuthorController::class);
    Route::get('api/authors',[AuthorController::class,'api']);

    Route::resource('/transactions', TransactionController::class);
    Route::get('api/transactions',[TransactionController::class,'api'])->name('transaction.get');

    Route::resource('/books', BookController::class);
    Route::get('api/books', [BookController::class,'api']);
    Route::delete('api/book-delete/{id}', [BookController::class, 'apiDelete']);
    Route::post('api/book-store', [BookController::class, 'apiStore']);
    Route::get('api/get-book/{id}', [BookController::class, 'apiGetBook']);

    // Route::get('/members', [MemberController::class, 'index'])->name('member');
    Route::resource('/members', MemberController::class);
    Route::get('api/members', [MemberController::class, 'api'])->name('member-api');

});