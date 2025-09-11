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
                'borrower' => '山田太郎',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'book_title' => 'サンプル本2',
                'author' => '著者B',
                'published_date' => '2019-07-12',
                'borrower' => null, // 追加
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'book_title' => 'サンプル本3',
                'author' => '著者C',
                'published_date' => '2018-01-23',
                'borrower' => '東北花子', // 追加
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'book_title' => 'サンプル本4',
                'author' => '著者D',
                'published_date' => '2021-11-15',
                'borrower' => null, // 追加
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'book_title' => 'サンプル本5',
                'author' => '著者E',
                'published_date' => '2022-03-30',
                'borrower' => null, // 追加
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'book_title' => 'サンプル本6',
                'author' => '著者F',
                'published_date' => '2017-09-10',
                'borrower' => null, // 追加
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'book_title' => 'サンプル本7',
                'author' => '著者G',
                'published_date' => '2020-12-01',
                'borrower' => null, // 追加
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'book_title' => 'サンプル本8',
                'author' => '著者H',
                'published_date' => '2016-05-18',
                'borrower' => null, // 追加
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'book_title' => 'サンプル本9',
                'author' => '著者I',
                'published_date' => '2015-08-22',
                'borrower' => null, // 追加
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'book_title' => 'サンプル本10',
                'author' => '著者J',
                'published_date' => '2023-02-14',
                'borrower' => null, // 追加
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'book_title' => 'サンプル本11',
                'author' => '著者K',
                'published_date' => '2021-06-09',
                'borrower' => null, // 追加
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}