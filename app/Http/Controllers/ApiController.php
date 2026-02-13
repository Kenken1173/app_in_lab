<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;

class ApiController extends Controller
{
    // 英語検索版
    public function search_book_for_en($name)
    {

        // Open Library APIにリクエストを送信
        $response = Http::get("https://openlibrary.org/search.json", [
            'q' => $name,
            'limit' => 10, // 取得する結果の数を制限
        ]);

        if ($response->successful()){
            $data = $response->json();
            // 必要なフィールドのみ指定
            $books = [];
            if (isset($data['docs'])){
                foreach ($data['docs'] as $book){
                    $books[] = [
                        'book_title' => $book['title'] ?? null,
                        'author' => $book['author_name'] ?? null,
                        'field' => $book['subject'] ?? [],
                        'published_year' => $book['first_publish_year'] ?? [],
                    ];
                }
            }
            
            // 成功時に結果を返す
            return response()->json([
                // 'numFound' => $data['numFound'] ?? 0,
                'books' => $books
            ], 200, [], JSON_UNESCAPED_UNICODE);
        }

        // 失敗した場合にエラーメッセージとレスポンス詳細を表示させる
        return response()->json([
            'error'=>'Failed to fetch data from Open Library API',
            'status' => $response->status(),
            'response_body' => $response->body(),
            'requested_url' => $response->effectiveUri(),
        ], 500, [], JSON_UNESCAPED_UNICODE);
    }

    // 日本語検索版
    public function search_book_for_ja($name)
    {

        // Open Library APIにリクエストを送信
        $response = Http::get("https://openlibrary.org/search.json", [
            'q' => $name,
            'limit' => 10, // 取得する結果の数を制限
            'language' => 'jpn', // 日本語の書籍を検索
        ]);

        if ($response->successful()){
            $data = $response->json();
            // 必要なフィールドのみ指定
            $books = [];
            if (isset($data['docs'])){
                foreach ($data['docs'] as $book){
                    $books[] = [
                        'book_title' => $book['title'] ?? null,
                        'author' => $book['author_name'] ?? null,
                        'field' => $book['subject'] ?? [],
                        'published_year' => $book['first_publish_year'] ?? [],
                    ];
                }
            }
            
            // 成功時に結果を返す
            return response()->json([
                // 'numFound' => $data['numFound'] ?? 0,
                'books' => $books
            ], 200, [], JSON_UNESCAPED_UNICODE);
        }

        // 失敗した場合にエラーメッセージとレスポンス詳細を表示させる
        return response()->json([
            'error'=>'Failed to fetch data from Open Library API',
            'status' => $response->status(),
            'response_body' => $response->body(),
            'requested_url' => $response->effectiveUri(),
        ], 500, [], JSON_UNESCAPED_UNICODE);
    }
}