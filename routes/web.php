<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookController;

Route::get('/', function () {
    return view('test');
});

Route::get('/admin', [BookController::class, 'admin'])->name('admin');