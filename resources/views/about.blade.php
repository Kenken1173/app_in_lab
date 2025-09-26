<!DOCTYPE html>
<html lang="ja">
<head>
	<meta charset="utf-8">
	<title>このサイトに関して</title>
	@vite(['resources/css/app.css'])
</head>
<body class="bg-[var(--color-background)] font-sans text-[var(--color-textmain)] min-h-screen">
	@include('layouts.header')
	<div class="max-w-3xl mx-auto mt-20 bg-white rounded-2xl shadow-2xl p-14 border border-blue-100">
		<h1 class="text-center text-[var(--color-primary)] text-2xl font-extrabold mb-8 tracking-wide flex items-center justify-center gap-2">
			<svg xmlns="http://www.w3.org/2000/svg" class="inline-block w-7 h-7 text-[var(--color-primary)]" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6l4 2" /></svg>
			このサイトに関して
		</h1>
		<p class="mb-8 text-[var(--text-body)] text-center text-lg leading-relaxed">
			このサイトは、松枝研究室の図書の貸出管理を目的としたWebアプリケーションです。<br>
			利用者が図書の貸出状況を確認したり、貸出・返却操作を行うことができます。
		</p>
		<div class="mb-8">
			<h2 class="text-[var(--color-primary)] text-xl font-semibold mb-3 flex items-center gap-2">
				<svg xmlns="http://www.w3.org/2000/svg" class="inline-block w-5 h-5 text-cyan-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2" /></svg>
				主な機能
			</h2>
			<ul class="list-disc pl-8 text-[var(--text-body)] text-base">
				<li>貸出中の図書一覧表示</li>
				<li>返却操作</li>
				<li>利用者・貸出日・経過日数の表示</li>
			</ul>
		</div>
		<div class="mb-8">
			<h2 class="text-[var(--color-primary)] text-xl font-semibold mb-3 flex items-center gap-2">
				<svg xmlns="http://www.w3.org/2000/svg" class="inline-block w-5 h-5 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 15c2.5 0 4.847.657 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
				運営者情報
			</h2>
			<p class="text-[var(--text-body)] text-base">運営者：安西賢吾, ILDESA MIGUEL, 新垣翔大</p>
			<p class="text-[var(--text-body)] text-base">連絡先：anzai.kengo.r6@dc.tohoku.ac.jp</p>
		</div>
		<div class="text-xs text-gray-500 text-center mt-10">
			&copy; {{ date('Y') }} 松枝研図書管理サイト運営委員会
		</div>
	</div>
</body>
</html>
