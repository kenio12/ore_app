<x-app-layout>
    <div class="my-apps-container">
        <div class="header-section">
            <h1 class="title">マイアプリ一覧</h1>
            <div class="header-decoration"></div>
        </div>

        <div class="container">
            <div class="apps-grid">
                @forelse($apps as $app)
                    <div class="app-card" style="border-color: {{ $appTypeColors[$app->app_type] ?? '#9CA3AF' }}">
                        <div class="app-card-content">
                            <div class="badges">
                                <div class="app-types">
                                    @foreach($app->app_types as $appType)
                                        <span 
                                            class="app-type-badge"
                                            style="background-color: {{ $appTypeColors[$appType] ?? '#9CA3AF' }}"
                                        >
                                            {{ $appTypeLabels[$appType] ?? 'その他' }}
                                        </span>
                                    @endforeach
                                </div>
                            </div>

                            <div class="app-header">
                                <h3 class="app-title">{{ $app->title }}</h3>
                                <span class="status-badge {{ $app->status }}">
                                    {{ $statusLabels[$app->status] ?? '開発中' }}
                                </span>
                            </div>

                            <div class="screenshot-container">
                                @if($app->screenshots && count($app->screenshots) > 0)
                                    <img 
                                        class="screenshot" 
                                        src="{{ $app->screenshots[0] }}" 
                                        alt="{{ $app->title }}"
                                        onerror="this.src='/default-app-image.png'"
                                    >
                                @endif
                            </div>

                            <div class="app-actions">
                                <a href="{{ route('app-posts.edit', $app) }}" class="edit-btn">
                                    編集する
                                </a>
                                <form action="{{ route('app-posts.destroy', $app) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="delete-btn" onclick="return confirm('本当に削除しますか？この操作は取り消せません。')">
                                        削除する
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="no-apps">
                        <p>まだアプリの投稿がありません</p>
                        <a href="{{ route('app-posts.create') }}" class="create-btn">
                            新しいアプリを投稿する
                        </a>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <x-slot name="styles">
        <style>
            .my-apps-container {
                padding-top: 4rem;
                min-height: 100vh;
                background: #f7fafc;
            }

            .header-section {
                position: relative;
                background: linear-gradient(135deg, #667eea, #764ba2);
                padding: 2.5rem 0;
                color: white;
                text-align: center;
                margin-bottom: 2rem;
                overflow: hidden;
            }

            .header-decoration {
                position: absolute;
                bottom: 0;
                left: 0;
                right: 0;
                height: 4rem;
                background: linear-gradient(135deg, rgba(255,255,255,0.1) 0%, transparent 100%);
                transform: skewY(-3deg);
                transform-origin: bottom right;
            }

            .title {
                position: relative;
                font-size: 2rem;
                font-weight: bold;
                text-shadow: 2px 2px 4px rgba(0,0,0,0.2);
            }

            .container {
                max-width: 1200px;
                margin: 0 auto;
                padding: 0 1rem;
            }

            .apps-grid {
                display: grid;
                grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
                gap: 2rem;
                margin-bottom: 3rem;
            }

            .app-card {
                background: white;
                border-radius: 1rem;
                overflow: hidden;
                box-shadow: 0 4px 6px rgba(0,0,0,0.05);
                transition: transform 0.2s, box-shadow 0.2s;
            }

            .app-card:hover {
                transform: translateY(-5px);
                box-shadow: 0 8px 15px rgba(0,0,0,0.1);
            }

            .app-actions {
                display: flex;
                gap: 1rem;
                margin-top: 1rem;
                padding-top: 1rem;
                border-top: 1px solid #e2e8f0;
            }

            .edit-btn, .delete-btn {
                flex: 1;
                padding: 0.5rem;
                border-radius: 0.5rem;
                text-align: center;
                font-weight: 500;
                transition: all 0.2s;
            }

            .edit-btn {
                background-color: #3182ce;
                color: white;
            }

            .delete-btn {
                background-color: transparent;
                border: 1px solid #e53e3e;
                color: #e53e3e;
            }

            .edit-btn:hover {
                background-color: #2c5282;
            }

            .delete-btn:hover {
                background-color: #e53e3e;
                color: white;
            }

            .no-apps {
                grid-column: 1 / -1;
                text-align: center;
                padding: 3rem;
                background: white;
                border-radius: 1rem;
                box-shadow: 0 4px 6px rgba(0,0,0,0.05);
            }

            .create-btn {
                display: inline-block;
                margin-top: 1rem;
                padding: 0.75rem 1.5rem;
                background: linear-gradient(135deg, #667eea, #764ba2);
                color: white;
                border-radius: 0.5rem;
                font-weight: 500;
                transition: transform 0.2s;
            }

            .create-btn:hover {
                transform: translateY(-2px);
            }

            @media (max-width: 768px) {
                .header-section {
                    padding: 1.5rem 0;
                }

                .title {
                    font-size: 1.5rem;
                }

                .apps-grid {
                    grid-template-columns: 1fr;
                }
            }
        </style>
    </x-slot>
</x-app-layout> 