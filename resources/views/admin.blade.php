<!DOCTYPE html>
<html lang="ja">
@include('layouts.header')
<body class="bg-[var(--color-background)] min-h-screen font-sans">
    <div class="max-w-5xl mx-auto mt-10 p-6">
        <p class="text-[var(--color-textmain)] font-sans text-title mb-12 text-center">
            管理者ページです。ここでは書籍やユーザーの管理が行えます。
        </p>

        <div class="mb-12">
            <h2 class="text-xl font-bold text-[var(--color-textmain)] border-b-2 border-[var(--color-primary)] pb-2 mb-6 flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18a8.967 8.967 0 0 0-6 2.292m0-14.25v14.25" />
                </svg>
                書籍管理
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <button class="open-modal flex flex-col items-center justify-center p-8 
                                  bg-transparent border-2 border-primary text-primary rounded-[var(--btn-radius)] 
                                  transition duration-200 hover:bg-primary hover:text-white hover:scale-105
                                  focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[var(--color-primary)]"
                        data-modal-target="#bookModal">
                    <span class="text-heading font-bold mb-2">書籍を新しく追加</span>
                    <span class="text-label opacity-80">新しい書籍情報を登録します</span>
                </button>

                <!-- 書籍追加モーダル -->
                <div id="bookModal" class="modal fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-40 hidden">
                    <div class="bg-white rounded-lg shadow-lg p-8 w-full max-w-md relative">
                        <button type="button" class="close-modal absolute top-2 right-2 text-gray-400 hover:text-gray-700 text-2xl">&times;</button>
                        <h3 class="text-lg font-bold mb-4 text-[var(--color-primary)]">書籍情報の登録</h3>
                        <!-- ToDo ここにフォームを後で追加 -->
                        <div class="text-gray-700">ここにフォームが入ります</div>
                    </div>
                </div>

                <button class="open-modal flex flex-col items-center justify-center p-8
                                  bg-transparent border-2 border-red-500 text-red-500 rounded-[var(--btn-radius)]
                                  transition duration-200 hover:bg-red-500 hover:text-white hover:scale-105
                                  focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500"
                        data-modal-target="#deleteModal">
                    <span class="text-heading font-bold mb-2 flex items-center gap-1">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z" />
                        </svg>
                        書籍を削除
                    </span>
                    <span class="text-label">既存の書籍情報を削除します</span>
                </button>

                <!-- 書籍削除モーダル -->
                <div id="deleteModal" class="modal fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-40 hidden">
                    <div class="bg-white rounded-lg shadow-lg p-8 w-full max-w-md relative">
                        <button type="button" class="close-modal absolute top-2 right-2 text-gray-400 hover:text-gray-700 text-2xl">&times;</button>
                        <h3 class="text-lg font-bold mb-4 text-red-500">書籍情報の削除</h3>
                        <div class="text-gray-700">
                            <ul>
                                @foreach($books as $book)
                                    <li>
                                        {{ $book->book_title }}（{{ $book->author }} / {{ $book->published_date }}）
                                        <!-- TODO 削除ボタンなどもここに設置可能 -->
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div>
            <h2 class="text-xl font-bold text-textmain border-b-2 border-accent pb-2 mb-6 flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                 <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                </svg>
                ユーザー管理
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <button class="open-modal flex flex-col items-center justify-center p-8 
                                 bg-transparent border-2 border-accent text-accent rounded-[var(--btn-radius)] 
                                 transition-transform duration-200 hover:bg-accent hover:text-white hover:scale-105
                                 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-cyan-500"
                                 data-modal-target="#userModal">
                    <span class="text-heading font-bold mb-2">ユーザー登録</span>
                    <span class="text-label opacity-80">新しいユーザーを登録します</span>
                </button>

                <!-- ユーザー登録モーダル -->
                <div id="userModal" class="modal fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-40 hidden">
                    <div class="bg-white rounded-lg shadow-lg p-8 w-full max-w-md relative">
                        <button type="button" class="close-modal absolute top-2 right-2 text-gray-400 hover:text-gray-700 text-2xl">&times;</button>
                        <h3 class="text-lg font-bold mb-4 text-accent">ユーザー情報の登録</h3>
                        <!-- ToDo ここにフォームを後で追加 -->
                        <div class="text-gray-700">ここにフォームが入ります</div>
                    </div>
                </div>

                <button class="open-modal flex flex-col items-center justify-center p-8 
                                 bg-transparent border-2 border-red-600 text-red-600 rounded-[var(--btn-radius)] 
                                 transition-transform duration-200 hover:scale-105 hover:bg-red-600 hover:text-white
                                 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-600"
                                 data-modal-target="#deleteUserModal">
                    <span class="text-heading font-bold mb-2 flex items-center gap-1">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z" />
                        </svg>
                        ユーザー削除
                    </span>
                    <span class="text-label opacity-80">登録済みユーザーを削除します</span>
                </button>

                <!-- ユーザー削除モーダル -->
                <div id="deleteUserModal" class="modal fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-40 hidden">
                    <div class="bg-white rounded-lg shadow-lg p-8 w-full max-w-md relative">
                        <button type="button" class="close-modal absolute top-2 right-2 text-gray-400 hover:text-gray-700 text-2xl">&times;</button>
                        <h3 class="text-lg font-bold mb-4 text-red-500">ユーザー情報の削除</h3>
                        <!-- ToDo ここにフォームを後で追加 -->
                        <div class="text-gray-700">ここにフォームが入ります</div>
                    </div>
                </div>
            </div>
        </div>

    </div>
<script>
    // 複数モーダルをクラスとdata属性で制御
    document.addEventListener('DOMContentLoaded', function() {
        // 開くボタン
        document.querySelectorAll('.open-modal').forEach(function(btn) {
            btn.addEventListener('click', function() {
                const target = btn.getAttribute('data-modal-target');
                if (target) {
                    const modal = document.querySelector(target);
                    if (modal) modal.classList.remove('hidden');
                }
            });
        });
        // 閉じるボタン
        document.querySelectorAll('.modal .close-modal').forEach(function(btn) {
            btn.addEventListener('click', function() {
                const modal = btn.closest('.modal');
                if (modal) modal.classList.add('hidden');
            });
        });
        // モーダル外クリックで閉じる
        document.querySelectorAll('.modal').forEach(function(modal) {
            modal.addEventListener('click', function(e) {
                if (e.target === modal) {
                    modal.classList.add('hidden');
                }
            });
        });
    });
</script>
</body>
</html>