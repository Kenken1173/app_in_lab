<?php

namespace App\Http\Controllers;

use App\Models\Book;use Illuminate\Http\Request;

class BookController extends Controller
{
    public function admin()
    {
        $books = Book::all(); // 全書籍データを取得
        return view('admin', compact('books'));
    }
}
