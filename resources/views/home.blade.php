<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Document</title>
</head>
<body>
  @if (session('success'))
    <div style="color: green; margin-bottom: 10px;">
      {{ session('success') }}
    </div>
  @endif
  @foreach($books as $book)
    <div style="border: 3px solid black;">
      <h2>{{ $book->book_title }}</h2>
      <p>著者: {{ $book->author }}</p>
      <p>出版日: {{ $book->published_date }}</p>
      <p>created_at: {{ $book->created_at }}</p>
      <p>updated_at: {{ $book->updated_at }} </p>
      @if (!is_null($book->borrower))
        <p>Borrower: {{ $book->borrower }}</p>
        <form action="/return" method="POST">
          @csrf
          <input type="hidden" name="id" value="{{ $book->id }}">
          <button>返却する</button>
        </form>
      @else
        <p>貸出可能<p>
        <form action="/borrow" method="POST">
          @csrf
          <input name="borrower" type="text" placeholder="名前">
          <input type="hidden" name="id" value="{{ $book->id }}">
          <button>借りる</button>
        </form>
      @endif
    </div>
  @endforeach
</body>
</html>