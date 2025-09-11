<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>貸出中の図書一覧</title>
  @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[var(--color-background)] font-sans text-[var(--color-textmain)] min-h-screen">
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
  <script>
    document.querySelectorAll('.return-form').forEach(form => {
      form.addEventListener('submit', function(e) {
        if(!confirm('本当に返却しますか？')) {
          e.preventDefault();
        }
      });
    });
  </script>
</body>
</html>
