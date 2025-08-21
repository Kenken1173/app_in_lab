<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    protected $fillable = ['book_title', 'author', 'published_date', 'borrower', 'image_path', 'field'];
}
