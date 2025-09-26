<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\BookController;

// テスト画面
Route::get('/', function () {
    return view('test');
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