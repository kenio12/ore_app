<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- ヘッダー -->
            <div class="bg-white p-6 rounded-lg shadow mb-8">
                <div class="flex items-center justify-between mb-4">
                    <h1 class="text-3xl font-bold text-gray-900">{{ $app->title }}</h1>
                    @if(auth()->id() === $app->user_id)
                        <div class="flex items-center gap-4">
                            <a 
                                href="{{ route('app-posts.edit', $app) }}" 
                                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg"
                            >
                                編集
                            </a>
                            <form 
                                action="{{ route('app-posts.destroy', $app) }}" 
                                method="POST" 
                                onsubmit="return confirm('本当に削除しますか？この操作は取り消せません。')"
                            >
                                @csrf
                                @method('DELETE')
                                <button 
                                    type="submit"
                                    class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg"
                                >
                                    削除
                                </button>
                            </form>
                        </div>
                    @endif
                </div>

                <!-- アプリ情報 -->
                <div class="flex flex-wrap gap-4 mb-4">
                    <!-- アプリタイプ -->
                    <div class="flex flex-wrap gap-2">
                        @foreach($app->app_types as $type)
                            <span 
                                class="px-3 py-1 text-sm font-medium rounded-full"
                                style="background-color: {{ $appTypeColors[$type] ?? '#9CA3AF' }}; color: white;"
                            >
                                {{ $appTypeLabels[$type] ?? 'その他' }}
                            </span>
                        @endforeach
                    </div>

                    <!-- 開発状況 -->
                    <span class="px-3 py-1 text-sm font-medium rounded-full {{ $app->status === 'completed' ? 'bg-green-500' : 'bg-blue-500' }} text-white">
                        {{ $statusLabels[$app->status] ?? '開発中' }}
                    </span>

                    <!-- 投稿日時 -->
                    <span class="text-gray-500 text-sm">
                        投稿: {{ $app->created_at->format('Y年m月d日') }}
                        @if($app->updated_at->ne($app->created_at))
                            (更新: {{ $app->updated_at->format('Y年m月d日') }})
                        @endif
                    </span>
                </div>

                <!-- 説明文 -->
                <p class="text-gray-600 whitespace-pre-line">{{ $app->description }}</p>

                <!-- リンク -->
                @if($app->github_url || $app->demo_url)
                    <div class="flex gap-4 mt-4">
                        @if($app->github_url)
                            <a 
                                href="{{ $app->github_url }}" 
                                target="_blank"
                                rel="noopener noreferrer"
                                class="inline-flex items-center text-gray-600 hover:text-gray-900"
                            >
                                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 0C5.37 0 0 5.37 0 12c0 5.3 3.438 9.8 8.205 11.385.6.113.82-.258.82-.577 0-.285-.01-1.04-.015-2.04-3.338.724-4.042-1.61-4.042-1.61-.546-1.385-1.335-1.755-1.335-1.755-1.087-.744.084-.729.084-.729 1.205.084 1.838 1.236 1.838 1.236 1.07 1.835 2.809 1.305 3.495.998.108-.776.417-1.305.76-1.605-2.665-.3-5.466-1.332-5.466-5.93 0-1.31.465-2.38 1.235-3.22-.135-.303-.54-1.523.105-3.176 0 0 1.005-.322 3.3 1.23.96-.267 1.98-.399 3-.405 1.02.006 2.04.138 3 .405 2.28-1.552 3.285-1.23 3.285-1.23.645 1.653.24 2.873.12 3.176.765.84 1.23 1.91 1.23 3.22 0 4.61-2.805 5.625-5.475 5.92.42.36.81 1.096.81 2.22 0 1.605-.015 2.896-.015 3.286 0 .315.21.69.825.57C20.565 21.795 24 17.295 24 12c0-6.63-5.37-12-12-12"/>
                                </svg>
                                GitHub
                            </a>
                        @endif
                        @if($app->demo_url)
                            <a 
                                href="{{ $app->demo_url }}" 
                                target="_blank"
                                rel="noopener noreferrer"
                                class="inline-flex items-center text-gray-600 hover:text-gray-900"
                            >
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                </svg>
                                デモサイト
                            </a>
                        @endif
                    </div>
                @endif
            </div>

            <!-- 開発ストーリー -->
            <div class="bg-white p-6 rounded-lg shadow mb-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">開発ストーリー</h2>
                
                <!-- 開発動機 -->
                <div class="mb-8">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">開発動機</h3>
                    <p class="text-gray-600 whitespace-pre-line">{{ $app->motivation }}</p>
                </div>

                <!-- 苦労した点・課題 -->
                <div class="mb-8">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">苦労した点・課題</h3>
                    <p class="text-gray-600 whitespace-pre-line">{{ $app->challenges }}</p>
                </div>

                <!-- 工夫した点 -->
                <div class="mb-8">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">工夫した点</h3>
                    <p class="text-gray-600 whitespace-pre-line">{{ $app->devised_points }}</p>
                </div>

                <!-- 学んだこと -->
                <div class="mb-8">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">学んだこと</h3>
                    <p class="text-gray-600 whitespace-pre-line">{{ $app->learnings }}</p>
                </div>

                @if($app->future_plans)
                    <!-- 今後の展望 -->
                    <div class="mb-8">
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">今後の展望</h3>
                        <p class="text-gray-600 whitespace-pre-line">{{ $app->future_plans }}</p>
                    </div>
                @endif
            </div>

            <!-- 開発環境 -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- ハードウェア環境 -->
                <div class="bg-white p-6 rounded-lg shadow">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6">ハードウェア環境</h2>
                    
                    @if($app->cpu)
                        <div class="mb-4">
                            <h3 class="text-sm font-medium text-gray-500">CPU</h3>
                            <p class="text-gray-900">{{ $app->cpu }}</p>
                        </div>
                    @endif

                    @if($app->memory)
                        <div class="mb-4">
                            <h3 class="text-sm font-medium text-gray-500">メモリ</h3>
                            <p class="text-gray-900">{{ $app->memory }}</p>
                        </div>
                    @endif

                    @if($app->storage)
                        <div class="mb-4">
                            <h3 class="text-sm font-medium text-gray-500">ストレージ</h3>
                            <p class="text-gray-900">{{ $app->storage }}</p>
                        </div>
                    @endif

                    @if($app->other_hardware)
                        <div class="mb-4">
                            <h3 class="text-sm font-medium text-gray-500">その他のハードウェア</h3>
                            <p class="text-gray-600 whitespace-pre-line">{{ $app->other_hardware }}</p>
                        </div>
                    @endif
                </div>

                <!-- ソフトウェア環境 -->
                <div class="bg-white p-6 rounded-lg shadow">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6">ソフトウェア環境</h2>
                    
                    <div class="mb-4">
                        <h3 class="text-sm font-medium text-gray-500">OS</h3>
                        <p class="text-gray-900">{{ $app->os }}</p>
                    </div>

                    <div class="mb-4">
                        <h3 class="text-sm font-medium text-gray-500">開発環境</h3>
                        <p class="text-gray-600 whitespace-pre-line">{{ $app->development_environment }}</p>
                    </div>

                    <div class="mb-4">
                        <h3 class="text-sm font-medium text-gray-500">主要な言語・フレームワークのバージョン</h3>
                        <p class="text-gray-600 whitespace-pre-line">{{ $app->language_versions }}</p>
                    </div>

                    @if($app->other_tools)
                        <div class="mb-4">
                            <h3 class="text-sm font-medium text-gray-500">その他のツール・ライブラリ</h3>
                            <p class="text-gray-600 whitespace-pre-line">{{ $app->other_tools }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- アーキテクチャ -->
            <div class="bg-white p-6 rounded-lg shadow mt-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">アーキテクチャパターン</h2>
                
                <div class="mb-4">
                    <h3 class="text-sm font-medium text-gray-500">採用したアーキテクチャパターン</h3>
                    <p class="text-gray-900">{{ $architecturePatterns[$app->architecture_pattern] ?? $app->architecture_pattern }}</p>
                </div>

                <div class="mb-4">
                    <h3 class="text-sm font-medium text-gray-500">採用理由</h3>
                    <p class="text-gray-600 whitespace-pre-line">{{ $app->architecture_reason }}</p>
                </div>

                @if($app->architecture_details)
                    <div class="mb-4">
                        <h3 class="text-sm font-medium text-gray-500">実装の工夫点</h3>
                        <p class="text-gray-600 whitespace-pre-line">{{ $app->architecture_details }}</p>
                    </div>
                @endif
            </div>

            <!-- バックエンド環境 -->
            <div class="bg-white p-6 rounded-lg shadow mt-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">バックエンド環境</h2>
                
                <div class="mb-4">
                    <h3 class="text-sm font-medium text-gray-500">プログラミング言語</h3>
                    <p class="text-gray-900">{{ $app->backend_language }}</p>
                </div>

                <div class="mb-4">
                    <h3 class="text-sm font-medium text-gray-500">フレームワーク</h3>
                    <p class="text-gray-900">{{ $app->backend_framework }}</p>
                </div>

                <div class="mb-4">
                    <h3 class="text-sm font-medium text-gray-500">主要なライブラリ・パッケージ</h3>
                    <p class="text-gray-600 whitespace-pre-line">{{ $app->backend_packages }}</p>
                </div>

                @if($app->backend_services)
                    <div class="mb-4">
                        <h3 class="text-sm font-medium text-gray-500">利用したAPI・外部サービス</h3>
                        <p class="text-gray-600 whitespace-pre-line">{{ $app->backend_services }}</p>
                    </div>
                @endif
            </div>

            <!-- フロントエンド環境 -->
            <div class="bg-white p-6 rounded-lg shadow mt-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">フロントエンド環境</h2>
                
                <div class="mb-4">
                    <h3 class="text-sm font-medium text-gray-500">プログラミング言語</h3>
                    <p class="text-gray-900">{{ $app->frontend_language }}</p>
                </div>

                <div class="mb-4">
                    <h3 class="text-sm font-medium text-gray-500">フレームワーク</h3>
                    <p class="text-gray-900">{{ $app->frontend_framework }}</p>
                </div>

                <div class="mb-4">
                    <h3 class="text-sm font-medium text-gray-500">CSSフレームワーク</h3>
                    <p class="text-gray-900">{{ $app->css_framework }}</p>
                </div>

                @if($app->frontend_packages)
                    <div class="mb-4">
                        <h3 class="text-sm font-medium text-gray-500">主要なライブラリ・パッケージ</h3>
                        <p class="text-gray-600 whitespace-pre-line">{{ $app->frontend_packages }}</p>
                    </div>
                @endif
            </div>

            <!-- データベース・ストレージ -->
            <div class="bg-white p-6 rounded-lg shadow mt-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">データベース・ストレージ</h2>
                
                <div class="mb-4">
                    <h3 class="text-sm font-medium text-gray-500">データベース</h3>
                    <p class="text-gray-900">{{ $app->database }}</p>
                </div>

                @if($app->cache)
                    <div class="mb-4">
                        <h3 class="text-sm font-medium text-gray-500">キャッシュ</h3>
                        <p class="text-gray-900">{{ $app->cache }}</p>
                    </div>
                @endif

                @if($app->storage_service)
                    <div class="mb-4">
                        <h3 class="text-sm font-medium text-gray-500">ストレージサービス</h3>
                        <p class="text-gray-900">{{ $app->storage_service }}</p>
                    </div>
                @endif

                @if($app->other_data_services)
                    <div class="mb-4">
                        <h3 class="text-sm font-medium text-gray-500">その他のデータ関連サービス</h3>
                        <p class="text-gray-600 whitespace-pre-line">{{ $app->other_data_services }}</p>
                    </div>
                @endif
            </div>

            <!-- その他の環境・ツール -->
            <div class="bg-white p-6 rounded-lg shadow mt-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">その他の環境・ツール</h2>
                
                <div class="mb-4">
                    <h3 class="text-sm font-medium text-gray-500">バージョン管理</h3>
                    <p class="text-gray-900">{{ $app->version_control }}</p>
                </div>

                @if($app->ci_cd)
                    <div class="mb-4">
                        <h3 class="text-sm font-medium text-gray-500">CI/CD</h3>
                        <p class="text-gray-900">{{ $app->ci_cd }}</p>
                    </div>
                @endif

                @if($app->container)
                    <div class="mb-4">
                        <h3 class="text-sm font-medium text-gray-500">コンテナ技術</h3>
                        <p class="text-gray-900">{{ $app->container }}</p>
                    </div>
                @endif

                @if($app->other_tools)
                    <div class="mb-4">
                        <h3 class="text-sm font-medium text-gray-500">その他のツール・サービス</h3>
                        <p class="text-gray-600 whitespace-pre-line">{{ $app->other_tools }}</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout> 