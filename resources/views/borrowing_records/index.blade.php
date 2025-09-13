<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>貸出中の図書一覧</title>
  @vite(['resources/css/app.css', 'resources/js/app.js'])
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
<body class="bg-[var(--color-background)] font-sans text-[var(--color-textmain)] min-h-screen">
  @include('layouts.header')
  <div class="max-w-2xl mx-auto mt-12 bg-white rounded-xl shadow-lg p-8">
    <h1 class="text-center text-[var(--color-primary)] text-[var(--text-title)] font-bold mb-8 tracking-wide">貸出中の図書</h1>

    @if($books->isEmpty())
      <p class="no-data text-center text-[var(--color-textmain)] text-[var(--text-body)] py-8">現在、貸出中の図書はありません。</p>
    @else
      <table class="w-full border-collapse bg-[var(--color-background)] rounded-lg overflow-hidden shadow">
        <thead>
          <tr>
            <th class="bg-[var(--color-primary)] text-white font-semibold px-6 py-3 text-left text-[var(--text-heading)]">タイトル</th>
            <th class="bg-[var(--color-primary)] text-white font-semibold px-6 py-3 text-left text-[var(--text-heading)]">借用者</th>
            <th class="bg-[var(--color-primary)] text-white font-semibold px-6 py-3 text-left text-[var(--text-heading)]">貸出日</th>
            <th class="bg-[var(--color-primary)] text-white font-semibold px-6 py-3 text-center text-[var(--text-heading)]">返却</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($books as $b)
            <tr class="even:bg-gray-100 hover:bg-[var(--color-accent)]/10 transition">
              <td class="px-6 py-4 text-[var(--text-body)]">{{ $b->book_title }}</td>
              <td class="px-6 py-4 text-[var(--text-body)]">{{ $b->borrower }}</td>
              <td class="px-6 py-4 text-[var(--text-body)]">
                {{ $b->updated_at ? \Carbon\Carbon::parse($b->updated_at)->format('Y-m-d') : '-' }}
              </td>
              <td class="px-6 py-4 text-center">
                <form method="POST" action="{{ route('books.return', ['id' => $b->id]) }}" class="inline return-form">
                  @csrf
                  <button type="submit" class="bg-[var(--color-accent)] hover:bg-cyan-400 text-white font-semibold py-2 px-4 rounded transition">返却</button>
                </form>
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    @endif
  </div>

  <!-- モーダル -->
  <div id="modal-bg" class="modal-bg">
    <div class="modal-content">
      <div class="modal-title">本当に返却しますか？</div>
      <div id="modal-bookinfo" style="color:#222; font-size:1rem; margin-bottom:1.5rem; line-height:1.7;">
        <!-- 本の情報が入る場所 -->
      </div>
      <div class="modal-btns">
        <button id="modal-confirm" class="modal-btn confirm">OK</button>
        <button id="modal-cancel" class="modal-btn cancel">キャンセル</button>
      </div>
    </div>
  </div>

  <script>
    let targetForm = null;
    document.querySelectorAll('.return-form').forEach(form => {
      form.addEventListener('submit', function(e) {
        e.preventDefault();
        targetForm = form;
        // 本の名前と借用者を取得
        const row = form.closest('tr');
        const title = row.querySelector('td:nth-child(1)').textContent.trim();
        const borrower = row.querySelector('td:nth-child(2)').textContent.trim();
        document.getElementById('modal-bookinfo').innerHTML =
          `<div><strong>タイトル：</strong>${title}</div><div><strong>借用者：</strong>${borrower}</div>`;
        document.getElementById('modal-bg').classList.add('active');
      });
    });
    document.getElementById('modal-cancel').onclick = function() {
      document.getElementById('modal-bg').classList.remove('active');
      targetForm = null;
    };
    document.getElementById('modal-confirm').onclick = function() {
      if(targetForm) targetForm.submit();
    };
    // モーダル外クリックで閉じる
    document.getElementById('modal-bg').addEventListener('click', function(e) {
      if(e.target === this){
        this.classList.remove('active');
        targetForm = null;
      }
    });
  </script>
</body>
</html>
