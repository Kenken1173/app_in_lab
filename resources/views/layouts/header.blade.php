<!-- resources/views/layouts/header.blade.php -->
<header class="bg-primary font-sans text-white p-4 flex items-center justify-between">
    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><g fill="none" stroke="#fff" stroke-width="1.5"><path d="M19.898 16h-12c-.93 0-1.395 0-1.777.102A3 3 0 0 0 4 18.224"/><path stroke-linecap="round" d="M8 7h8m-8 3.5h5M10 22c-2.828 0-4.243 0-5.121-.879C4 20.243 4 18.828 4 16V8c0-2.828 0-4.243.879-5.121C5.757 2 7.172 2 10 2h4c2.828 0 4.243 0 5.121.879C20 3.757 20 5.172 20 8m-6 14c2.828 0 4.243 0 5.121-.879C20 20.243 20 18.828 20 16v-4"/></g></svg>
    <h1 class="text-title">研究室図書管理システム</h1>
    <nav class="ml-auto">
        <a href="/" class="mr-4">ホーム</a>
        <a href="/about">このサイトについて</a>
    </nav>
    <!-- Tailwind を読み込む -->
    <!-- @theme を書いた CSS を読み込む -->
    @vite('resources/css/app.css')
</header>