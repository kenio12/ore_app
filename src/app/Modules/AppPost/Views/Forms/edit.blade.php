<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <form action="{{ route('app-posts.update', $post) }}" method="POST" class="space-y-8">
                @csrf
                @method('PUT')

                <!-- 基本情報フォーム -->
                @include('app-posts.forms.01_basic_info_form')

                <!-- 開発ストーリー -->
                @include('app-posts.forms.08_development_story_form')

                <!-- ハードウェア環境 -->
                @include('app-posts.forms.02_hardware_section')

                <!-- ソフトウェア環境 -->
                @include('app-posts.forms.03_software_environment_section')

                <!-- アーキテクチャパターン -->
                @include('app-posts.forms.09_architecture_section')

                <!-- バックエンド環境 -->
                @include('app-posts.forms.04_backend_section')

                <!-- フロントエンド環境 -->
                @include('app-posts.forms.05_frontend_section')

                <!-- データベース・ストレージ -->
                @include('app-posts.forms.06_database_section')

                <!-- その他の環境・ツール -->
                @include('app-posts.forms.07_others_section')

                <!-- 送信ボタン -->
                <div class="flex justify-end gap-4">
                    <button 
                        type="button" 
                        onclick="history.back()" 
                        class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded"
                    >
                        キャンセル
                    </button>
                    <button 
                        type="submit" 
                        class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"
                    >
                        更新する
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout> 