<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AuthorController;
use App\Http\Controllers\BookController;

Route::get('/', function () {
    if (!session()->has('logged_in')) {
        return redirect()->route('signin');
    }

    return redirect()->route('profile');
});


Route::get('/signin', [AuthController::class, 'showLoginForm'])->name('signin');
Route::post('/signin', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware(['check.session'])->group(function () {
    Route::get('profile',[DashboardController::class, 'index'])->name('profile'); 

    Route::group(['prefix' => 'authors'], function () {
        Route::get('/', [AuthorController::class, 'index'])->name('authors');
        Route::get('/{id}', [AuthorController::class, 'show'])->name('authors.show');
        Route::delete('/{id}', [AuthorController::class, 'destroy'])->name('authors.destroy');
    });

    // Books
    Route::group(['prefix' => 'books'], function () {
        Route::get('/create', [BookController::class, 'create'])->name('books.create');
        Route::post('/', [BookController::class, 'store'])->name('books.store');
        Route::delete('/{id}', [BookController::class, 'destroy'])->name('books.destroy');
    });
    

});
