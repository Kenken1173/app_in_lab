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
            [
                'book_title' => 'サンプル本2',
                'author' => '著者B',
                'published_date' => '2021-06-10',
                'borrower'=> "東北太郎",
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'book_title' => 'サンプル本3',
                'author' => '著者C',
                'published_date' => '2023-01-15',
                'borrower'=> '山田太郎',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}