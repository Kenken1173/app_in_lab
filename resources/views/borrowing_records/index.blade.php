<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>貸出中の図書一覧</title>
  <style>
    body {
      font-family: 'Segoe UI', 'ヒラギノ角ゴ ProN', 'Hiragino Kaku Gothic ProN', 'メイリオ', Meiryo, sans-serif;
      background: #f5f6fa;
      margin: 0;
      padding: 0;
    }
    .container {
      max-width: 700px;
      margin: 40px auto;
      background: #fff;
      border-radius: 10px;
      box-shadow: 0 2px 8px rgba(0,0,0,0.08);
      padding: 32px 40px 40px 40px;
    }
    h1 {
      text-align: center;
      color: #2d3e50;
      margin-bottom: 32px;
      letter-spacing: 2px;
    }
    table {
      width: 100%;
      border-collapse: collapse;
      background: #fafbfc;
      margin-bottom: 16px;
    }
    th, td {
      padding: 14px 10px;
      text-align: left;
    }
    th {
      background: #4a90e2;
      color: #fff;
      font-weight: 600;
      letter-spacing: 1px;
      border-bottom: 2px solid #357ab8;
    }
    tr:nth-child(even) {
      background: #f0f4f8;
    }
    tr:hover {
      background: #e6f0fa;
      transition: background 0.2s;
    }
    .no-data {
      text-align: center;
      color: #888;
      font-size: 1.1em;
      margin-top: 24px;
    }
  </style>
</head>
<body>
  <div class="container">
    <h1>貸出中の図書</h1>

    @if($books->isEmpty())
      <p class="no-data">現在、貸出中の図書はありません。</p>
    @else
      <table>
        <thead>
          <tr>
            <th>タイトル</th>
            <th>借用者</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($books as $b)
            <tr>
              <td>{{ $b->book_title }}</td>
              <td>{{ $b->borrower }}</td>
            </tr>
          @endforeach
        </tbody>
      </table>
    @endif
  </div>
</body>
</html>
