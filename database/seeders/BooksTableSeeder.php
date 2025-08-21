<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BooksTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('books')->insert([
            [
                'book_title' => 'サンプル本1',
                'author' => '著者A',
                'published_date' => '2020-04-05',
                'borrower'=> '借りた人の名前',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}