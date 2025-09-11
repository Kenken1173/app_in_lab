<?php

use App\Models\Book;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookController;

Route::get('/', function () {
    $books = Book::all();
    if (session('success')) {
        return view('home', [
            'books' => $books,
            'success' => session('success'),
        ]);
    }
    return view('home', compact('books'));
});

Route::get('test', function() {
    return view('test');
});

Route::post('/borrow', [BookController::class, 'borrow']);
Route::post('/return', [BookController::class, 'return']);