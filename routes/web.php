<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;

// ルートパスで borrowing_records.index を表示
Route::get('/', function () {
    $books = DB::table('books')
        ->whereNotNull('borrower')
        ->where('borrower', '!=', '')
        ->where('borrower', '!=', '借りていない')
        ->select('book_title', 'borrower')
        ->orderBy('book_title')
        ->get();

    return view('borrowing_records.index', ['books' => $books]);
});

Route::get('/borrowing-records', function () {
    $books = DB::table('books')
        ->whereNotNull('borrower')
        ->where('borrower', '!=', '')
        ->where('borrower', '!=', '借りていない')
        ->select('book_title', 'borrower')
        ->orderBy('book_title')
        ->get();

    return view('borrowing_records.index', ['books' => $books]);
});