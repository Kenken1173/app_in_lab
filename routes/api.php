<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TestController;

Route::get('/testapi/{name}', [TestController::class, 'search_book']);