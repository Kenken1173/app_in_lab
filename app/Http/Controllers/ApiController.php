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
        if ($language === '') {
            $language = null;
        }

        if (!in_array($language, [null, 'en', 'jpn'], true)) {
            $language = null;
        }

        // Open Library API用の言語コードに変換
        $openLibraryLanguage = match ($language) {
            'en' => 'eng',
            'jpn' => 'jpn',
            default => null,
        };

        $page  = max(1, (int)$request->query('page', 1));
        $limit = (int)$request->query('limit', 20);
        $limit = min(50, max(1, $limit)); // 暴走防止（仮）

        // 何も入力されていない場合は検索しない
        if($query == '' && $auther == '' && $year == '' && $isbn == '') {
            return response()->json([
                'items' => [],
                'total' => 0,
                'page' => $page,
                'limit' => $limit,
                'has_more' => false,
            ]);
        }

        // Open Library API用のパラメータ作成
        $params = [
            'page' => $page,
            'limit' => $limit,
            'fields' => 'key,title,author_name,first_publish_year,isbn,language',
        ];

        //isbnがある場合はそれで優先して検索
        if ($isbn !== '') {
            $params['isbn'] = $isbn;
        } else {
            if ($query !== '') {
                // q は総合検索
                $params['q'] = $query;
            }

            if ($auther !== '') {
                $params['author'] = $auther;
            }

            if ($year !== '') {
                $params['first_publish_year'] = $year;
            }

            if ($openLibraryLanguage !== null) {
                $params['language'] = $openLibraryLanguage;
            }
        }

        try {
            $response = Http::timeout(10)
                ->acceptJson()
                ->get('https://openlibrary.org/search.json', $params);

            if (!$response->successful()) {
                return response()->json([
                    'message' => 'Open Library APIの取得に失敗しました',
                    'status' => $response->status(),
                    'body' => $response->body(),
                ], 502, [], JSON_UNESCAPED_UNICODE);
            }

            $data = $response->json();

            $docs = $data['docs'] ?? [];
            $total = (int) ($data['numFound'] ?? count($docs));

            // フロント側が扱いやすい形に整形
            $items = collect($docs)->map(function ($book) {
                return [
                    'title' => $book['title'] ?? '',
                    'author_name' => $book['author_name'] ?? [],
                    'first_publish_year' => $book['first_publish_year'] ?? null,
                    'isbn' => $book['isbn'] ?? [],
                    'language' => $book['language'] ?? [],
                ];
            })->values();
        
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
                    'open_library_language' => $openLibraryLanguage,
                    'params' => $params,
                ],
            ], 200, [], JSON_UNESCAPED_UNICODE);

        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'API接続中にエラーが発生しました',
                'error' => $e->getMessage(),
            ], 500, [], JSON_UNESCAPED_UNICODE);
        }
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
                        'location' => $book['subject'] ?? [],
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
                        'location' => $book['subject'] ?? [],
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