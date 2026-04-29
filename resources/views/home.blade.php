<html lang="en">
@include('layouts.header')
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Document</title>
  <!-- Tailwind を読み込む -->
  <!-- @theme を書いた CSS を読み込む -->
  @vite('resources/css/app.css')
    <style>
    /* モーダルのスタイル */
    .modal-bg {
      display: none;
      position: fixed;
      z-index: 50;
      left: 0; top: 0; width: 100vw; height: 100vh;
      background: rgba(0,0,0,0.3);
      align-items: center;
      justify-content: center;
    }
    .modal-bg.active {
      display: flex;
    }
    .modal-content {
      background: #fff;
      border-radius: 1rem;
      box-shadow: 0 8px 32px rgba(44,62,80,0.18);
      padding: 2.5rem 2rem 2rem 2rem;
      max-width: 480px;
      text-align: center;
      animation: fadeIn 0.2s;
    }
    .modal-title {
      font-size: 1.2rem;
      font-weight: bold;
      color: var(--color-primary);
      margin-bottom: 1.2rem;
    }
    .modal-btns {
      margin-top: 2rem;
      display: flex;
      gap: 1.2rem;
      justify-content: center;
    }
    .modal-btn {
      padding: 0.5rem 1.5rem;
      border-radius: 0.5rem;
      font-weight: 600;
      font-size: 1rem;
      border: none;
      cursor: pointer;
      transition: background 0.15s;
    }
    .modal-btn.confirm {
      background: var(--color-accent);
      color: #fff;
    }
    .modal-btn.confirm:hover {
      background: #26c6da;
    }
    .modal-btn.cancel {
      background: #e0e0e0;
      color: #444;
    }
    .modal-btn.cancel:hover {
      background: #bdbdbd;
    }
    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(30px);}
      to   { opacity: 1; transform: translateY(0);}
    }
  </style>
</head>
<body>
@php
  $flash = null;

  if (session('book_added')) {
      $flash = [
          'type' => 'success',
          'title' => '書籍を追加しました',
          'message' => session('book_added'),
          'color' => 'emerald',
          'icon' => '✓',
      ];
  } elseif (session('book_deleted')) {
      $flash = [
          'type' => 'danger',
          'title' => '書籍を削除しました',
          'message' => session('book_deleted'),
          'color' => 'red',
          'icon' => '×',
      ];
  } elseif (session('book_borrowed')) {
      $flash = [
          'type' => 'info',
          'title' => '貸出処理が完了しました',
          'message' => session('book_borrowed'),
          'color' => 'sky',
          'icon' => '✓',
      ];
  } elseif (session('book_returned')) {
      $flash = [
          'type' => 'success',
          'title' => '返却処理が完了しました',
          'message' => session('book_returned'),
          'color' => 'emerald',
          'icon' => '✓',
      ];
  }
@endphp

@if ($flash)
  <div class="max-w-6xl mx-auto mt-6 px-4">
    <div
      id="flash-message"
      class="
        flex items-start gap-3 rounded-xl px-5 py-4 shadow-sm border
        @if ($flash['color'] === 'emerald')
          border-emerald-200 bg-emerald-50 text-emerald-800
        @elseif ($flash['color'] === 'red')
          border-red-200 bg-red-50 text-red-800
        @elseif ($flash['color'] === 'sky')
          border-sky-200 bg-sky-50 text-sky-800
        @endif
      "
      role="alert"
    >
      <div
        class="
          flex h-8 w-8 shrink-0 items-center justify-center rounded-full
          @if ($flash['color'] === 'emerald')
            bg-emerald-100 text-emerald-700
          @elseif ($flash['color'] === 'red')
            bg-red-100 text-red-700
          @elseif ($flash['color'] === 'sky')
            bg-sky-100 text-sky-700
          @endif
        "
      >
        {{ $flash['icon'] }}
      </div>

      <div class="flex-1">
        <div class="font-bold">{{ $flash['title'] }}</div>
        <div class="mt-1 text-sm">
          {{ $flash['message'] }}
        </div>
      </div>

      <button
        type="button"
        id="close-flash"
        class="ml-4 opacity-70 hover:opacity-100"
        aria-label="閉じる"
      >
        ×
      </button>
    </div>
  </div>
@endif
  <div class="max-w-6xl mx-auto mt-12 bg-white rounded-xl shadow-lg p-8">
    <h1 class="text-center text-[var(--color-primary)] text-[var(--text-title)] font-bold mb-8 tracking-wide">
      すべての図書
    </h1>

    <div class="flex flex-col lg:flex-row gap-6 items-start">
      {{-- 左：本一覧 --}}
      <div class="flex-1 min-w-0">
        <table class="w-full border-collapse bg-[var(--color-background)] rounded-lg overflow-hidden shadow">
          <thead>
            <tr>
              <th class="bg-[var(--color-primary)] text-white font-semibold px-6 py-3 text-left text-[var(--text-heading)]">タイトル</th>
              <th class="bg-[var(--color-primary)] text-white font-semibold px-6 py-3 text-left text-[var(--text-heading)]">著者</th>
              <th class="bg-[var(--color-primary)] text-white font-semibold px-6 py-3 text-left text-[var(--text-heading)]">出版年</th>
              <th class="bg-[var(--color-primary)] text-white font-semibold px-6 py-3 text-center text-[var(--text-heading)]">借りる・返却</th>
            </tr>
          </thead>
          <tbody>
            @foreach($books as $book)
              <tr class="even:bg-gray-100 hover:bg-[var(--color-accent)]/10 transition">
                <td class="px-6 py-4 text-[var(--text-body)]">{{ $book->book_title }}</td>
                <td class="px-6 py-4 text-[var(--text-body)]">{{ $book->author }}</td>
                <td class="px-6 py-4 text-[var(--text-body)]">{{ $book->published_year }}</td>

                @if (is_null($book->borrower))
                  <td class="px-6 py-4 text-center">
                    <form action="/borrow" method="POST" class="inline borrow-form">
                      @csrf
                      <input type="hidden" name="id" value="{{ $book->id }}">
                      <button type="submit" class="bg-[var(--color-accent)] hover:bg-cyan-400 text-white font-semibold py-2 px-4 rounded transition">借りる</button>
                    </form>

                    <!-- モーダル -->
                    <div id="modal-bg-{{ $book->id }}-borrow" class="modal-bg">
                      <div class="modal-content">
                        <div class="modal-title">あなたの名前を入力してください</div>
                        <div id="modal-bookinfo-{{ $book->id }}-borrow" style="color:#222; font-size:1rem; margin-bottom:1.5rem; line-height:1.7;">
                          <!-- 本の情報が入る -->
                        </div>
                        <div class="modal-btns">
                          <button id="modal-confirm-{{ $book->id }}-borrow" class="modal-btn confirm">OK</button>
                          <button id="modal-cancel-{{ $book->id }}-borrow" class="modal-btn cancel">キャンセル</button>
                        </div>
                      </div>
                    </div>
                  </td>
                @else
                  <td class="px-6 py-4 text-center">
                    <form action="{{ route('books.return', ['id' => $book->id]) }}" method="POST" class="inline return-form">
                      @csrf
                      <input type="hidden" name="id" value="{{ $book->id }}">
                      <button type="submit" class="bg-red-500 hover:bg-red-600 text-white font-semibold py-2 px-4 rounded transition">返却する</button>
                    </form>

                    <!-- モーダル -->
                    <div id="modal-bg-{{ $book->id }}-return" class="modal-bg">
                      <div class="modal-content">
                        <div class="modal-title">本当に返却しますか？</div>
                        <div id="modal-bookinfo-{{ $book->id }}-return" style="color:#222; font-size:1rem; margin-bottom:1.5rem; line-height:1.7;">
                          <!-- 本の情報が入る -->
                        </div>
                        <div class="modal-btns">
                          <button id="modal-confirm-{{ $book->id }}-return" class="modal-btn confirm">OK</button>
                          <button id="modal-cancel-{{ $book->id }}-return" class="modal-btn cancel">キャンセル</button>
                        </div>
                      </div>
                    </div>
                  </td>
                  <td class="hidden">{{ $book->borrower }}</td>
                @endif
              </tr>
            @endforeach
          </tbody>
      </table>
    </div>

       {{-- 右：追加/削除ボタン--}}
      <aside class="w-full lg:w-72">
        <div class="bg-[var(--color-background)] rounded-xl shadow p-4 border">
          <div class="text-sm font-semibold text-gray-700 mb-3">管理</div>

          <button id="open-add-book"
                  class="w-full flex flex-col items-center justify-center p-5
                         bg-transparent border-2 border-[var(--color-primary)] text-[var(--color-primary)]
                         rounded-[var(--btn-radius)]
                         transition duration-200 hover:bg-[var(--color-primary)] hover:text-white hover:scale-[1.02]
                         focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[var(--color-primary)]">
            <span class="text-base font-bold mb-1">書籍を新しく追加</span>
            <span class="text-xs opacity-80">新しい書籍情報を登録</span>
          </button>

          <button id="open-delete-book"
                  class="w-full mt-4 flex flex-col items-center justify-center p-5
                         bg-transparent border-2 border-red-500 text-red-500
                         rounded-[var(--btn-radius)]
                         transition duration-200 hover:bg-red-500 hover:text-white hover:scale-[1.02]
                         focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
            <span class="text-base font-bold mb-1">書籍を削除</span>
            <span class="text-xs opacity-80">登録済みの書籍を削除</span>
          </button>
        </div>
      </aside>

  </div>
</div>

<div id="modal-bg-add-book" class="modal-bg">
  <div class="modal-content max-h-[85vh] overflow-auto" style="max-width:720px; text-align:left;">
    <div class="modal-title">追加したい書籍を検索してください</div>

    <!-- 検索セクション -->
    <section class="mb-4 p-4 rounded-xl border bg-[var(--color-background)]">
      <div class="mb-3">
        <h4 class="font-semibold text-gray-800">Open Libraryで検索</h4>
        <p class="text-xs text-gray-500 mt-1">
          キーワード / 著者 / 発行年 / ISBN のいずれかを入力すると検索できます
        </p>
      </div>

      <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
        <div class="sm:col-span-2">
          <label for="ol_keyword" class="block text-sm font-medium text-gray-700">キーワード</label>
          <input id="ol_keyword" type="text"
                 class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                 placeholder="例：量子情報 / Quantum information" />
          <div>
            <label for="ol_language" class="block text-sm font-medium text-gray-700">言語</label>
            <select id="ol_language" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
              <option value="">指定しない</option>
              <option value="jpn">日本語</option>
              <option value="en">English</option>
            </select>
          </div>
        </div>

        <div>
          <label for="ol_author" class="block text-sm font-medium text-gray-700">著者</label>
          <input id="ol_author" type="text"
                 class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                 placeholder="例：Hotta / ホッタ" />
        </div>

        <div>
          <label for="ol_year" class="block text-sm font-medium text-gray-700">発行年</label>
          <input id="ol_year" type="number" inputmode="numeric"
                 class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                 placeholder="例：2015" min="1400" max="2100" />
        </div>

        <div class="sm:col-span-2">
          <label for="ol_isbn" class="block text-sm font-medium text-gray-700">ISBN</label>
          <input id="ol_isbn" type="text" inputmode="numeric"
                 class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                 placeholder="例：978-0000000000（ハイフン可）" />
        </div>
      </div>

      <div class="mt-4 flex items-center justify-end gap-2">
        <button type="button" id="ol_clear_btn"
                class="px-3 py-2 rounded-md border border-gray-300 text-gray-700 hover:bg-gray-50">
          クリア
        </button>

        <button type="button" id="ol_search_btn" disabled
                class="px-4 py-2 rounded-md bg-[var(--color-primary)] text-white font-semibold
                       opacity-50 cursor-not-allowed transition">
          検索
        </button>
      </div>
      <!-- 結果表示（メタ情報） -->
      <div id="ol_results_meta" class="mt-4 text-xs text-gray-600 hidden"></div>

      <!-- 結果表示（一覧） -->
      <div id="ol_results_list" class="mt-2 space-y-2 hidden"></div>

      <!-- ページャ（最初は非表示） -->
      <div id="ol_results_pager" class="mt-4 flex items-center justify-between hidden">
        <button type="button" id="ol_prev_btn"
                class="px-3 py-2 rounded-md border border-gray-300 text-gray-700 hover:bg-gray-50 opacity-50 cursor-not-allowed"
                disabled>
          前へ
        </button>

        <div id="ol_page_label" class="text-sm text-gray-700">Page 1</div>

        <button type="button" id="ol_next_btn"
                class="px-3 py-2 rounded-md border border-gray-300 text-gray-700 hover:bg-gray-50 opacity-50 cursor-not-allowed"
                disabled>
          次へ
        </button>
      </div>

    </section>

    <section class="mt-4 p-4 rounded-xl border bg-white">
      <h4 class="font-semibold text-gray-800 mb-3">選択した書籍を登録</h4>

      <form id="selected-book-form" action="{{ route('books.store') }}" method="POST" class="space-y-4">
        @csrf

        <div>
          <label for="selected_book_title" class="block text-sm font-medium text-gray-700">書籍タイトル</label>
          <input id="selected_book_title" name="book_title" type="text"
                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                required>
        </div>

        <div>
          <label for="selected_author" class="block text-sm font-medium text-gray-700">著者</label>
          <input id="selected_author" name="author" type="text"
                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                required>
        </div>

        <div>
          <label for="selected_published_year" class="block text-sm font-medium text-gray-700">出版年</label>
          <input id="selected_published_year" name="published_year" type="number"
                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                min="0" max="{{ date('Y') }}" required>
        </div>

        <div>
          <label for="selected_isbn" class="block text-sm font-medium text-gray-700">ISBN</label>
          <input id="selected_isbn" type="text"
                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                readonly>
          <p class="mt-1 text-xs text-gray-500">ISBN は表示のみです。今はDB保存には使いません。</p>
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700">カテゴリ</label>
          <div class="flex flex-col mt-1">
            <label><input type="checkbox" name="categories[]" value="選択肢1"> 選択肢1</label>
            <label><input type="checkbox" name="categories[]" value="選択肢2"> 選択肢2</label>
            <label><input type="checkbox" name="categories[]" value="選択肢3"> 選択肢3</label>
          </div>
        </div>

        <div class="flex justify-end">
          <button type="submit"
                  class="px-4 py-2 rounded-md bg-[var(--color-primary)] text-white font-semibold hover:opacity-90 transition">
            登録する
          </button>
        </div>
      </form>
    </section>

    <!-- フッター（いまは閉じるだけ） -->
    <div class="modal-btns" style="justify-content:flex-end; margin-top:1rem;">
      <button type="button" id="cancel-add-book" class="modal-btn cancel">閉じる</button>
    </div>
  </div>
</div>
<div id="modal-bg-delete-book" class="modal-bg">
  <div class="modal-content" style="max-width:640px; text-align:left;">
    <div class="modal-title" style="color:#ef4444;">登録した書籍情報を削除できます</div>

    <div class="text-sm text-gray-700 max-h-[60vh] overflow-auto">
      <ul class="space-y-2">
        @foreach($books as $book)
          <li class="flex items-center justify-between gap-3 border rounded-lg p-3 bg-white">
            <div class="min-w-0">
              <div class="font-semibold truncate">{{ $book->book_title }}</div>
              <div class="text-xs text-gray-500">{{ $book->author }} / {{ $book->published_year }}</div>
            </div>

            <form action="{{ route('books.destroy', $book->id) }}"
                  method="POST"
                  onsubmit="return confirm('本当に削除しますか？')">
              @csrf
              @method('DELETE')
              <button type="submit"
                      class="bg-red-500 hover:bg-red-600 text-white font-semibold py-2 px-3 rounded transition">
                削除
              </button>
            </form>
          </li>
        @endforeach
      </ul>
    </div>

    <div class="modal-btns" style="justify-content:flex-end;">
      <button type="button" id="close-delete-book" class="modal-btn cancel">閉じる</button>
    </div>
  </div>
</div>


<script>
document.addEventListener('DOMContentLoaded', function() {
  let targetForm = null;

  //自動で閉じる
  const flashMessage = document.getElementById('flash-message');
  const closeFlash = document.getElementById('close-flash');

  closeFlash?.addEventListener('click', () => {
    flashMessage?.remove();
  });

  if (flashMessage) {
    setTimeout(() => {
      flashMessage.style.transition = 'opacity 0.4s ease, transform 0.4s ease';
      flashMessage.style.opacity = '0';
      flashMessage.style.transform = 'translateY(-8px)';

      setTimeout(() => {
        flashMessage.remove();
      }, 400);
    }, 4000);
  }

  // 追加・削除モーダルの処理
  const addBg = document.getElementById('modal-bg-add-book');
  const delBg = document.getElementById('modal-bg-delete-book');

  document.getElementById('open-add-book')?.addEventListener('click', () => addBg?.classList.add('active'));
  document.getElementById('open-delete-book')?.addEventListener('click', () => delBg?.classList.add('active'));

  document.getElementById('cancel-add-book')?.addEventListener('click', () => addBg?.classList.remove('active'));
  document.getElementById('close-delete-book')?.addEventListener('click', () => delBg?.classList.remove('active'));

  addBg?.addEventListener('click', (e) => { if (e.target === addBg) addBg.classList.remove('active'); });
  delBg?.addEventListener('click', (e) => { if (e.target === delBg) delBg.classList.remove('active'); });

  const keywordEl = document.getElementById('ol_keyword');
  const authorEl  = document.getElementById('ol_author');
  const yearEl    = document.getElementById('ol_year');
  const isbnEl    = document.getElementById('ol_isbn');
  const searchBtn = document.getElementById('ol_search_btn');
  const clearBtn  = document.getElementById('ol_clear_btn');

  const metaEl    = document.getElementById('ol_results_meta');
  const listEl    = document.getElementById('ol_results_list');
  const pagerEl   = document.getElementById('ol_results_pager');
  const prevBtn   = document.getElementById('ol_prev_btn');
  const nextBtn   = document.getElementById('ol_next_btn');
  const pageLabel = document.getElementById('ol_page_label');
  const selectedBookTitleEl = document.getElementById('selected_book_title');
  const selectedAuthorEl = document.getElementById('selected_author');
  const selectedPublishedYearEl = document.getElementById('selected_published_year');
  const selectedIsbnEl = document.getElementById('selected_isbn');

  const langEl = document.getElementById('ol_language');
  const SEARCH_URL = `{{ route('api.search_book') }}`;
  const PER_PAGE = 20;

  let currentPage = 1;
  let lastParams = null;

  function norm(v) { return (v ?? '').toString().trim(); }

  function buildParams(page) {
    return {
      query: norm(keywordEl?.value),
      auther: norm(authorEl?.value),   // ← 要件どおり "auther" で送る
      year: norm(yearEl?.value),
      isbn: norm(isbnEl?.value),
      language: norm(langEl?.value) || null, // ""ならnull扱い
      page: page,
      limit: PER_PAGE,
    };
  }

  function anyFilled(p) {
    return !!(p.query || p.auther || p.year || p.isbn);
  }

  function toQueryString(params) {
    const qs = new URLSearchParams();
    for (const [k, v] of Object.entries(params)) {
      if (v === null || v === undefined) continue;
      if (typeof v === 'string' && v.trim() === '') continue;
      qs.append(k, v);
    }
    return qs.toString();
  }

  function esc(s){ return String(s ?? '').replace(/[&<>"']/g, m => ({
    '&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'
  }[m]));}

  function renderList(items){
    metaEl.classList.remove('hidden');
    listEl.classList.remove('hidden');
    pagerEl.classList.remove('hidden');

    if (!items || items.length === 0) {
      listEl.innerHTML = `
        <div class="rounded-lg border bg-white p-4 text-sm text-gray-600">
          該当する書籍は見つかりませんでした
        </div>
      `;
      return;
    }

    listEl.innerHTML = items.map((it, idx) => {
      const rawTitle  = it.title ?? it.book_title ?? it.name ?? '';
      const rawAuthor = it.author ?? it.auther ?? it.author_name?.[0] ?? '';
      const rawYear   = it.year ?? it.first_publish_year ?? '';
      const rawIsbn   = Array.isArray(it.isbn) ? (it.isbn[0] ?? '') : (it.isbn ?? '');

      const title  = esc(rawTitle || '(no title)');
      const author = esc(rawAuthor || '不明');
      const year   = esc(rawYear || '不明');
      const isbn   = esc(rawIsbn || '');

      const buttonData = esc(JSON.stringify({
        title: rawTitle || '',
        author: rawAuthor || '',
        published_year: rawYear || '',
        isbn: rawIsbn || ''
      }));

      return `
        <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm">
          <div class="flex items-start justify-between gap-4">
            <div class="min-w-0 flex-1">
              <div class="text-base font-semibold text-gray-900 break-words">
                ${title}
              </div>

              <div class="mt-3 grid grid-cols-1 sm:grid-cols-2 gap-x-4 gap-y-2 text-sm text-gray-700">
                <div>
                  <span class="font-medium text-gray-500">著者</span><br>
                  <span>${author}</span>
                </div>
                <div>
                  <span class="font-medium text-gray-500">出版年</span><br>
                  <span>${year}</span>
                </div>
                <div class="sm:col-span-2">
                  <span class="font-medium text-gray-500">ISBN</span><br>
                  <span>${isbn || 'なし'}</span>
                </div>
              </div>
            </div>

            <div class="shrink-0">
              <button
                type="button"
                class="add-book-from-result px-3 py-2 rounded-md bg-[var(--color-primary)] text-white text-sm font-semibold hover:opacity-90 transition cursor-pointer"
                data-book='${buttonData}'
              >
                この本を追加
              </button>
            </div>
          </div>
        </div>
      `;
    }).join('');
  }

  async function fetchAndRender(page) {
    const params = { ...lastParams, page, limit: PER_PAGE };
    const url = `${SEARCH_URL}?${toQueryString(params)}`;

    // 表示を一旦クリア
    metaEl.textContent = '';
    listEl.innerHTML = `<div class="text-sm text-gray-600">読み込み中...</div>`;

    try {
      const resp = await fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
      if (!resp.ok) throw new Error(`HTTP ${resp.status}`);
      const data = await resp.json();

      // 返却形式はどちらでも対応できるように
      const items = data.items ?? data.docs ?? [];
      const total = (typeof data.total === 'number') ? data.total
                  : (typeof data.numFound === 'number') ? data.numFound
                  : null;

      renderList(items);

      // 次へ判定：totalがあれば厳密、無ければ「20件返ってきたら次があるかも」
      const hasMore = (typeof data.has_more === 'boolean')
        ? data.has_more
        : (typeof total === 'number')
          ? (page * PER_PAGE < total)
          : (items.length === PER_PAGE);

      // ページャ更新
      currentPage = page;
      pageLabel.textContent = `Page ${page}`;

      prevBtn.disabled = page <= 1;
      prevBtn.classList.toggle('opacity-50', prevBtn.disabled);
      prevBtn.classList.toggle('cursor-not-allowed', prevBtn.disabled);

      nextBtn.disabled = !hasMore;
      nextBtn.classList.toggle('opacity-50', nextBtn.disabled);
      nextBtn.classList.toggle('cursor-not-allowed', nextBtn.disabled);

      // メタ表示
      metaEl.textContent = (typeof total === 'number')
        ? `表示: ${items.length}件 / 総件数: ${total}件`
        : `表示: ${items.length}件`;

    } catch (e) {
      listEl.innerHTML = `<div class="text-sm text-red-600">取得に失敗しました</div>`;
      nextBtn.disabled = true;
      nextBtn.classList.add('opacity-50', 'cursor-not-allowed');
    }
  }

  // 検索ボタン：page=1で実行
  searchBtn.addEventListener('click', () => {
    const p = buildParams(1);
    if (!anyFilled(p)) return;
    lastParams = p;
    fetchAndRender(1);
  });

  // 次へ/前へ
  nextBtn.addEventListener('click', () => {
    if (!lastParams) return;
    fetchAndRender(currentPage + 1);
  });

  prevBtn.addEventListener('click', () => {
    if (!lastParams || currentPage <= 1) return;
    fetchAndRender(currentPage - 1);
  });

  // 入力に応じて検索ボタン有効化（languageは条件に含めない）
  function updateSearchEnabled() {
    const p = buildParams(currentPage);
    const ok = anyFilled(p);
    searchBtn.disabled = !ok;
    searchBtn.classList.toggle('opacity-50', !ok);
    searchBtn.classList.toggle('cursor-not-allowed', !ok);
  }
  [keywordEl, authorEl, yearEl, isbnEl].forEach(el => {
    el.addEventListener('input', updateSearchEnabled);
    el.addEventListener('change', updateSearchEnabled);
  });
  updateSearchEnabled();

  clearBtn?.addEventListener('click', () => {
    keywordEl.value = '';
    authorEl.value = '';
    yearEl.value = '';
    isbnEl.value = '';
    if (langEl) langEl.value = '';

    if (selectedBookTitleEl) selectedBookTitleEl.value = '';
    if (selectedAuthorEl) selectedAuthorEl.value = '';
    if (selectedPublishedYearEl) selectedPublishedYearEl.value = '';
    if (selectedIsbnEl) selectedIsbnEl.value = '';

    lastParams = null;
    currentPage = 1;

    metaEl.textContent = '';
    listEl.innerHTML = '';
    metaEl.classList.add('hidden');
    listEl.classList.add('hidden');
    pagerEl.classList.add('hidden');

    pageLabel.textContent = 'Page 1';
    prevBtn.disabled = true;
    nextBtn.disabled = true;
    prevBtn.classList.add('opacity-50', 'cursor-not-allowed');
    nextBtn.classList.add('opacity-50', 'cursor-not-allowed');

    updateSearchEnabled();
  });

  listEl?.addEventListener('click', (e) => {
    const btn = e.target.closest('.add-book-from-result');
    if (!btn) return;

    try {
      const book = JSON.parse(btn.dataset.book);

      if (selectedBookTitleEl) {
        selectedBookTitleEl.value = book.title ?? '';
      }
      if (selectedAuthorEl) {
        selectedAuthorEl.value = book.author ?? '';
      }
      if (selectedPublishedYearEl) {
        selectedPublishedYearEl.value = book.published_year ?? '';
      }
      if (selectedIsbnEl) {
        selectedIsbnEl.value = book.isbn ?? '';
      }

      document.getElementById('selected-book-form')?.scrollIntoView({
        behavior: 'smooth',
        block: 'nearest',
      });
    } catch (err) {
      console.error('failed to parse book data', err);
    }
  });


  // 借りるボタンのモーダル処理
  document.querySelectorAll('.borrow-form').forEach(form => {
    form.addEventListener('submit', function(e) {
      e.preventDefault();
      targetForm = form;
      const bookId = form.querySelector('input[name="id"]').value;
      const bookTitle = form.closest('tr').querySelector('td:nth-child(1)').textContent;
      const bookAuthor = form.closest('tr').querySelector('td:nth-child(2)').textContent;
      const modalBg = document.getElementById(`modal-bg-${bookId}-borrow`);
      const modalBookInfo = document.getElementById(`modal-bookinfo-${bookId}-borrow`);
      modalBookInfo.innerHTML =
        `<div><strong>タイトル：</strong>${bookTitle}</div><div><strong>著者：</strong>${bookAuthor}</div><input name="borrower" type="text" placeholder="貸出者の名前" id="modal-borrower-input-${bookId}" style="margin-top:1rem; width:100%; padding:0.5rem; border:1px solid #ccc; border-radius:0.5rem;">`;
      modalBg.classList.add('active');

      // OKボタン
      document.getElementById(`modal-confirm-${bookId}-borrow`).onclick = function() {
        // 入力値をformに追加
        const input = document.getElementById(`modal-borrower-input-${bookId}`);
        if (input && input.value.trim()) {
          let hidden = form.querySelector('input[name="borrower"]');
          if (!hidden) {
            hidden = document.createElement('input');
            hidden.type = 'hidden';
            hidden.name = 'borrower';
            form.appendChild(hidden);
          }
          hidden.value = input.value.trim();
          modalBg.classList.remove('active');
          targetForm.submit();
        } else {
          input.style.borderColor = 'red';
        }
      };

      // キャンセルボタン
      document.getElementById(`modal-cancel-${bookId}-borrow`).onclick = function() {
        modalBg.classList.remove('active');
        targetForm = null;
      };

      // モーダル外クリックで閉じる
      modalBg.addEventListener('click', function(e) {
        if(e.target === modalBg){
          modalBg.classList.remove('active');
          targetForm = null;
        }
      }, { once: true });
    });
  });

  // 返却ボタンのモーダル処理
  document.querySelectorAll('.return-form').forEach(form => {
    form.addEventListener('submit', function(e) {
      e.preventDefault();
      targetForm = form;
      const bookId = form.querySelector('input[name="id"]').value;
      const bookTitle = form.closest('tr').querySelector('td:nth-child(1)').textContent;
      const borrower = form.closest('tr').querySelector('td:nth-child(5)')?.textContent || '';
      const modalBg = document.getElementById(`modal-bg-${bookId}-return`);
      const modalBookInfo = document.getElementById(`modal-bookinfo-${bookId}-return`);
      if (modalBookInfo) {
        modalBookInfo.innerHTML =
          `<div><strong>タイトル：</strong>${bookTitle}</div><div><strong>利用者：</strong>${borrower}</div>`;
      }
      modalBg.classList.add('active');

      // OKボタン
      document.getElementById(`modal-confirm-${bookId}-return`).onclick = function() {
        modalBg.classList.remove('active');
        targetForm.submit();
      };

      // キャンセルボタン
      document.getElementById(`modal-cancel-${bookId}-return`).onclick = function() {
        modalBg.classList.remove('active');
        targetForm = null;
      };

      // モーダル外クリックで閉じる
      modalBg.addEventListener('click', function(e) {
        if(e.target === modalBg){
          modalBg.classList.remove('active');
          targetForm = null;
        }
      }, { once: true });
    });
  });
});
</script>

</body>
</html>