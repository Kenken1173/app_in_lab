<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class TestController extends Controller
{
    public function index()
    {
        return response()->json([
            'name' => 'テスト太郎',
            'role' => 'Engineer',
        ]);
    }

    public function search_book($name)
    {

        // Google Books APIにリクエストを送信
        // キーワードの後に続くテキスト続くテキストがタイトルに含まれている結果を返す。
        // https://developers.google.com/books/docs/v1/using?hl=ja#PerformingSearch
        $response = Http::get("https://www.googleapis.com/books/v1/volumes", [
            'q' => 'intitle:'.$name,
        ]);

        if ($response->successful()){
            return $response->json();
        }

        // 失敗した場合にエラーメッセージを表示させる
        return response()->json(['error'=>'Failed to fetch data from Google Books API'], 500);
    }
}