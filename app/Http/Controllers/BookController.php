<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;

class BookController extends Controller
{
    public function index()
    {
        $books = Book::borrowed()->orderBy('book_title')->get();
        return view('borrowing_records.index', compact('books'));
    }

    public function return($id)
    {
        $book = Book::findOrFail($id);
        $book->borrower = null;
        $book->save();
        return redirect()->back();
    }

    public function admin_index()
    {
        $books = Book::all();
        return view('admin', compact('books'));
    }

    // 新規書籍登録
    public function store(Request $request)
    {
        $request->validate([
            'book_title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'published_date' => 'required|date',
            'categories' => 'array', // チェックボックスのバリデーション（任意）
        ]);

        Book::create([
            'book_title' => $request->book_title,
            'author' => $request->author,
            'published_date' => $request->published_date,
            'field'          => implode(',', $request->input('categories', [])),
        ]);

        return redirect()->back()->with('success', '書籍を追加しました');
    }

    public function destroy($id)
    {
        $book = Book::findOrFail($id); // 指定されたIDのBookを取得
        $book->delete();               // 削除

        return redirect()->back()->with('success', '書籍を削除しました');
    }
    
    public function admin()
    {
        $books = Book::all(); // 全書籍データを取得
        return view('admin', compact('books'));
    }
}