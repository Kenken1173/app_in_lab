<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ボタン配色テスト</title>
    <!-- Tailwind を読み込む -->
    <!-- @theme を書いた CSS を読み込む -->
    @vite('resources/css/app.css')
</head>
<body>

    <button class="bg-primary text-white font-sans rounded-[var(--btn-radius)] px-[var(--btn-padding-x)] py-[var(--btn-padding-y)]">
        メインボタン
    </button>

    <p class="text-textmain font-sans text-title">
        これはテストページです。
    </p>

    <p class="text-accent font-sans text-body">
        これはサブテキストです。
    </p>

</body>
</html>