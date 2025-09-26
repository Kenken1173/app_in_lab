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
}