<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;

class BookController extends Controller
{
    public function borrow(Request $request) {
        $request->validate([
            'id' => 'required|exists:books,id',
            'borrower' => 'required|string|max:255',
        ]);

        Book::where('id', $request->id)
            ->update(['borrower' => $request->borrower]);
        return redirect('/')->with('success', '本を借りました。');
    }

    public function return(Request $request) {
        $request->validate([
            'id' => 'required|exists:books,id',
        ]);

        Book::where('id', $request->id)
            ->update(['borrower' => null]);
        return redirect('/')->with('success', '本を返却しました。');
    }
}
