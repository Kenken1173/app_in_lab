<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ApiController extends Controller
{
    public function search_book(Request $request)
    {
        // --- 入力を取得（auther/author 両対応にしておく） ---
        $query  = trim((string)$request->query('query', ''));
        $auther = trim((string)$request->query('auther', $request->query('author', '')));
        $year   = trim((string)$request->query('year', ''));
        $isbnRaw = trim((string)$request->query('isbn', ''));
        $isbn = $isbnRaw !== '' ? preg_replace('/[^0-9Xx]/', '', $isbnRaw) : '';

        $language = $request->query('language', null); // "en" / "jpn" / null
        if ($language === '') $language = null;
        if (!in_array($language, [null, 'en', 'jpn'], true)) {
            $language = null; // 想定外は無視
        }

        $page  = max(1, (int)$request->query('page', 1));
        $limit = (int)$request->query('limit', 20);
        $limit = min(50, max(1, $limit)); // 暴走防止（仮）

        // --- ダミー生成（後でここを本実装に差し替える） ---
        $total = 123; // 仮の総件数
        $startIndex = ($page - 1) * $limit + 1;

        $items = [];
        for ($i = 0; $i < $limit; $i++) {
            $idx = $startIndex + $i;
            if ($idx > $total) break;

            $items[] = [
                // フロントの renderList が拾えるように典型キーに寄せる
                'title' => ($query !== '' ? $query : 'Sample Book') . " #{$idx}",
                'author_name' => [ $auther !== '' ? $auther : 'Sample Author' ],
                'first_publish_year' => ($year !== '' ? (int)$year : (2000 + ($idx % 20))),
                'isbn' => [ $isbn !== '' ? $isbn : ('978000000' . str_pad((string)$idx, 4, '0', STR_PAD_LEFT)) ],
                'language' => $language,
            ];
        }

        $hasMore = ($page * $limit) < $total;

        return response()->json([
            'items' => $items,
            'total' => $total,
            'page' => $page,
            'limit' => $limit,
            'has_more' => $hasMore,

            // デバッグ用（不要なら消してOK）
            'echo' => [
                'query' => $query,
                'auther' => $auther,
                'year' => $year,
                'isbn' => $isbn,
                'language' => $language,
            ],
        ]);
    }
}

// 外部API接続を行う際に使用する
class SubApiController extends Controller
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