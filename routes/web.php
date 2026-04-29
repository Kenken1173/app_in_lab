<?php

use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\BookController;
use App\Http\Controllers\ApiController;

// テスト画面
Route::get('/', function (Request $request) {
    $keyword = trim((string) $request->query('keyword', ''));
    $location = (string) $request->query('location', '');
    $availability = (string) $request->query('availability', '');
    $sort = (string) $request->query('sort', 'title_asc');

    $sortOptions = [
        'title_asc' => ['book_title', 'asc'],
        'title_desc' => ['book_title', 'desc'],
        'author_asc' => ['author', 'asc'],
        'author_desc' => ['author', 'desc'],
        'year_asc' => ['published_year', 'asc'],
        'year_desc' => ['published_year', 'desc'],
        'created_desc' => ['created_at', 'desc'],
        'created_asc' => ['created_at', 'asc'],
    ];

    if (! array_key_exists($sort, $sortOptions)) {
        $sort = 'title_asc';
    }

    if (! in_array($location, ['', '206', '300'], true)) {
        $location = '';
    }

    if (! in_array($availability, ['', 'available', 'borrowed'], true)) {
        $availability = '';
    }

    $booksQuery = Book::query();

    if ($keyword !== '') {
        $booksQuery->where(function ($query) use ($keyword) {
            $likeKeyword = '%' . $keyword . '%';

            $query->where('book_title', 'like', $likeKeyword)
                ->orWhere('author', 'like', $likeKeyword)
                ->orWhere('published_year', 'like', $likeKeyword)
                ->orWhere('location', 'like', $likeKeyword)
                ->orWhere('borrower', 'like', $likeKeyword);
        });
    }

    if ($location !== '') {
        $booksQuery->where('location', $location);
    }

    if ($availability === 'borrowed') {
        $booksQuery
            ->whereNotNull('borrower')
            ->where('borrower', '!=', '')
            ->where('borrower', '!=', '借りていない');
    } elseif ($availability === 'available') {
        $booksQuery->where(function ($query) {
            $query->whereNull('borrower')
                ->orWhere('borrower', '')
                ->orWhere('borrower', '借りていない');
        });
    }

    [$sortColumn, $sortDirection] = $sortOptions[$sort];
    $books = $booksQuery
        ->orderBy($sortColumn, $sortDirection)
        ->orderBy('id')
        ->paginate(20)
        ->withQueryString();
    $allBooksForManagement = Book::orderBy('book_title')->orderBy('id')->get();

    $viewData = compact('books', 'allBooksForManagement', 'keyword', 'location', 'availability', 'sort');

    if (session('success')) {
        $viewData['success'] = session('success');
    }

    return view('home', $viewData);
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

// APIエンドポイント
Route::get('/api/search_book', [ApiController::class, 'search_book'])
    ->name('api.search_book');
