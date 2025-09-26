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
      max-width: 340px;
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
  @if (session('success'))
    <div style="color: green; margin-bottom: 10px;">
      {{ session('success') }}
    </div>
  @endif
  <div class="max-w-2xl mx-auto mt-12 bg-white rounded-xl shadow-lg p-8">
    <h1 class="text-center text-[var(--color-primary)] text-[var(--text-title)] font-bold mb-8 tracking-wide">すべての図書</h1>

    <table class="w-full border-collapse bg-[var(--color-background)] rounded-lg overflow-hidden shadow">
      <thead>
        <tr>
          <th class="bg-[var(--color-primary)] text-white font-semibold px-6 py-3 text-left text-[var(--text-heading)]">タイトル</th>
          <th class="bg-[var(--color-primary)] text-white font-semibold px-6 py-3 text-left text-[var(--text-heading)]">著者</th>
          <th class="bg-[var(--color-primary)] text-white font-semibold px-6 py-3 text-left text-[var(--text-heading)]">出版日</th>
          <th class="bg-[var(--color-primary)] text-white font-semibold px-6 py-3 text-center text-[var(--text-heading)]">借りる・返却</th>
        </tr>
      </thead>
      <tbody>
        @foreach($books as $book)
          <tr class="even:bg-gray-100 hover:bg-[var(--color-accent)]/10 transition">
            <td class="px-6 py-4 text-[var(--text-body)]">{{ $book->book_title }}</td>
            <td class="px-6 py-4 text-[var(--text-body)]">{{ $book->author }}</td>
            <td class="px-6 py-4 text-[var(--text-body)]">{{ $book->published_date }}</td>

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
                    <div class="modal-title">本を借りますか？</div>
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
                  <button type="submit" class="bg-[var(--color-accent)] hover:bg-cyan-400 text-white font-semibold py-2 px-4 rounded transition">返却する</button>
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

<script>
document.addEventListener('DOMContentLoaded', function() {
  let targetForm = null;

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
        `<div><strong>タイトル：</strong>${bookTitle}</div><div><strong>著者：</strong>${bookAuthor}</div><input name="borrower" type="text" placeholder="名前" id="modal-borrower-input-${bookId}" style="margin-top:1rem; width:100%; padding:0.5rem; border:1px solid #ccc; border-radius:0.5rem;">`;
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