<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiController;

Route::get('/testapi/en/{name}', [ApiController::class, 'search_book_for_en']);
Route::get('/testapi/ja/{name}', [ApiController::class, 'search_book_for_ja']);