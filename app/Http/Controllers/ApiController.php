<?php
// TODO: 著者のみ検索をできるようにする、著者かつタイトルの検索結果を表示できるように(実行時間長めなのでタイムアウトしているかも？)
// 読み込み長い時はローディング表示できたらいいな
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ApiController extends Controller
{
    public function search_book(Request $request)
    {
        // --- 入力を取得 ---
        $query  = trim((string)$request->query('query', ''));
        $author = trim((string)$request->query('author', ''));
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
        if($query == '' && $author == '' && $year == '' && $isbn == '') {
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

            if ($author !== '') {
                $params['author'] = $author;
            }

            if ($year !== '') {
                $params['first_publish_year'] = $year;
            }

            if ($openLibraryLanguage !== null) {
                $params['language'] = $openLibraryLanguage;
            }
        }

        try {
            $apiSource = 'openlibrary';
            $requestParams = $params;

            if ($language === 'jpn') {
                $apiSource = 'ndl';

                $ndlParams = [
                    'title' => $query,
                    'cnt' => $limit,
                    'idx' => (($page - 1) * $limit) + 1,
                ];

                if ($author != '') {
                    $ndlParams['creator'] = $author;
                }

                if ($year != '') { 
                    $ndlParams['from'] = $year;
                }

                logger()->debug('NDL URL', [
                    'url' => 'https://ndlsearch.ndl.go.jp/api/opensearch?' . http_build_query($ndlParams),
                    'params' => $ndlParams,
                ]);
                $response = Http::timeout(10)
                    ->get('https://ndlsearch.ndl.go.jp/api/opensearch', $ndlParams);
                $requestParams = $ndlParams;

                if (!$response->successful()) {
                    return response()->json([
                        'message' => '国立図書館APIの取得に失敗しました',
                        'status' => $response->status(),
                        'body' => $response->body(),
                    ], 502, [], JSON_UNESCAPED_UNICODE);
                }

                $xml = simplexml_load_string($response->body());
                if ($xml === false) {
                    return response()->json([
                        'message' => '国立図書館APIのレスポンス解析に失敗しました',
                    ], 502, [], JSON_UNESCAPED_UNICODE);
                }

                $ns = $xml->getNamespaces(true);
                $opensearchNs = $ns['opensearch'] ?? null;
                $dcNs = $ns['dc'] ?? null;

                $channel = $xml->channel ?? null;
                $itemsNode = $channel?->item ?? [];

                $total = 0;
                if ($channel && $opensearchNs) {
                    $channelOpenSearch = $channel->children($opensearchNs);
                    $total = (int) ($channelOpenSearch->totalResults ?? 0);
                }

                $items = collect();
                foreach ($itemsNode as $itemNode) {
                    $dc = $dcNs ? $itemNode->children($dcNs) : null;

                    $creator = isset($dc->creator) ? trim((string) $dc->creator) : '';
                    $dateRaw = isset($dc->date) ? trim((string) $dc->date) : '';
                    preg_match('/\d{4}/', $dateRaw, $yearMatch);
                    $publishYear = isset($yearMatch[0]) ? (int) $yearMatch[0] : null;

                    $identifier = isset($dc->identifier) ? trim((string) $dc->identifier) : '';
                    preg_match_all('/97[89]\d{10}|\d{9}[\dXx]/', preg_replace('/[^0-9Xx]/', '', $identifier), $isbnMatches);
                    $isbns = array_values(array_unique($isbnMatches[0] ?? []));

                    $lang = isset($dc->language) ? trim((string) $dc->language) : 'jpn';

                    $items->push([
                        'title' => trim((string) ($itemNode->title ?? '')),
                        'author_name' => $creator !== '' ? [$creator] : [],
                        'first_publish_year' => $publishYear,
                        'isbn' => $isbns,
                        'language' => $lang !== '' ? [$lang] : ['jpn'],
                    ]);
                }
                $items = $items->values();
                if ($total <= 0) {
                    $total = $items->count();
                }
            } else {
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
                    'author' => $author,
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
