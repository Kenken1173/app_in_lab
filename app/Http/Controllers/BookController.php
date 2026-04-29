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

        $title = $book->book_title;

        $book->borrower = null;
        $book->save();

        return redirect()
            ->back()
            ->with('book_returned', '「' . $title . '」を返却しました。');
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
            'published_year' => 'required|integer|min:0|max:' . date('Y'),
            'location' => 'required|string|in:206,300',
        ]);

    $publishedYear = (int) $request->published_year;

    $book = Book::create([
        'book_title' => $request->book_title,
        'author' => $request->author,
        'published_year' => $publishedYear,
        'published_date' => $publishedYear . '-01-01',
        'field' => implode(',', $request->input('categories', [])),
    ]);

    return redirect()
        ->back()
        ->with('book_added', '「' . $book->book_title . '」を登録しました。');
}

public function destroy($id)
{
    $book = Book::findOrFail($id);

    $title = $book->book_title;

    $book->delete();

    return redirect()
        ->back()
        ->with('book_deleted', '「' . $title . '」を削除しました。');
}
    
    public function admin()
    {
        $books = Book::all(); // 全書籍データを取得
        return view('admin', compact('books'));
    }

    public function borrow(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:books,id',
            'borrower' => 'required|string|max:255',
        ]);

        $book = Book::findOrFail($request->id);
        $book->borrower = $request->borrower;
        $book->save();

        return redirect()
            ->back()
            ->with('book_borrowed', '「' . $book->book_title . '」を貸出中にしました。');
    }
}