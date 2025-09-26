<?php

use App\Models\Book;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\BookController;

// テスト画面
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

Route::get('/admin', [BookController::class, 'admin_index'])->name('admin.index');
Route::post('/books', [BookController::class, 'store'])->name('books.store');
Route::delete('/books/{id}', [BookController::class, 'destroy'])->name('books.destroy');

Route::get('/about', function () {
    return view('about');
});
Route::get('/welcome', function () {
    return view('welcome');
});

// ルートパスで borrowing_records.index を表示
Route::get('/record', function () {
    $books = DB::table('books')
        ->whereNotNull('borrower')
        ->where('borrower', '!=', '')
        ->where('borrower', '!=', '借りていない')
        ->select('id', 'book_title', 'borrower', 'updated_at')
        ->orderBy('book_title')
        ->get();

    return view('borrowing_records.index', ['books' => $books]);
});

Route::get('/borrowing-records', [BookController::class, 'index'])->name('books.index');

// 返却処理
Route::post('/books/{id}/return', [BookController::class, 'return'])->name('books.return');
Route::post('/borrow', [BookController::class, 'borrow']);
Route::post('/return', [BookController::class, 'return']);