<x-app-layout>
    <div class="home-container">
        <div class="hero-section">
            <h1 class="title">🗡️ 俺は 🏴‍☠️</h1>
            <p class="subtitle">アプリになる！</p>
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

                            <div class="app-meta">
                                <div class="creator">
                                    <div 
                                        class="initial-avatar"
                                        style="background-color: {{ generateColorFromString($app->user->name) }}"
                                    >
                                        {{ substr($app->user->name, 0, 1) }}
                                    </div>
                                    <span>{{ $app->user->name }}</span>
                                </div>
                                <div class="date">{{ $app->created_at->format('Y年n月j日') }}</div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="no-apps">
                        投稿がありません
                    </div>
                @endforelse
            </div>

            @auth
                <div class="account-actions">
                    <form action="{{ route('profile.destroy') }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="delete-account-btn" onclick="return confirm('本当にアカウントを削除しますか？\n\n⚠️ この操作は取り消せません。\n- すべての投稿が削除されます\n- アカウント情報が完全に削除されます')">
                            退会する
                        </button>
                    </form>
                </div>
            @endauth
        </div>
    </div>

    <x-slot name="styles">
        <style>
            .home-container {
                background: linear-gradient(135deg, #f6f8fc 0%, #e9f1f9 100%);
                min-height: 100vh;
                padding-top: 0;
            }

            .hero-section {
                text-align: center;
                padding: 3rem 0 2rem;
                background: linear-gradient(135deg, #6366f1, #7c3aed);
                margin-bottom: 3rem;
                color: white;
                box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            }

            .title {
                font-size: 4rem;
                font-weight: 800;
                margin-bottom: 1rem;
                text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.2);
            }

            .subtitle {
                font-size: 1.8rem;
                opacity: 0.9;
            }

            .container {
                max-width: 800px;
                margin: 0 auto;
                padding: 0 2rem;
            }

            .apps-grid {
                display: flex;
                flex-direction: column;
                gap: 2rem;
                margin-bottom: 3rem;
            }

            .app-card {
                width: 100%;
                background: white;
                border-radius: 1rem;
                overflow: hidden;
                box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
                transition: transform 0.2s, box-shadow 0.2s;
            }

            .app-card:hover {
                transform: translateY(-5px);
                box-shadow: 0 8px 15px rgba(0, 0, 0, 0.1);
            }

            .app-card-content {
                padding: 1.5rem;
            }

            .app-header {
                display: flex;
                justify-content: space-between;
                align-items: center;
                margin: 1rem 0;
            }

            .app-title {
                margin: 0;
                font-size: 1.4rem;
                font-weight: 600;
            }

            .screenshot-container {
                position: relative;
                width: 100%;
                max-height: 80vh;
                overflow: hidden;
                background: #f7fafc;
                margin: 1rem 0;
                display: flex;
                justify-content: center;
                align-items: center;
            }

            .screenshot {
                max-width: 100%;
                max-height: 80vh;
                width: auto;
                height: auto;
                object-fit: contain;
            }

            .app-meta {
                display: flex;
                justify-content: space-between;
                align-items: center;
                margin-top: 1rem;
                padding-top: 1rem;
                border-top: 1px solid #e2e8f0;
            }

            .creator {
                display: flex;
                align-items: center;
                gap: 0.5rem;
            }

            .initial-avatar {
                width: 2rem;
                height: 2rem;
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                font-weight: bold;
                color: white;
                font-size: 1rem;
            }

            .date {
                color: #718096;
                font-size: 0.9rem;
            }

            .delete-account-btn {
                padding: 0.75rem 1.5rem;
                background-color: transparent;
                border: 1px solid #ff4444;
                color: #ff4444;
                border-radius: 0.5rem;
                cursor: pointer;
                transition: all 0.2s;
            }

            .delete-account-btn:hover {
                background-color: #ff4444;
                color: white;
            }

            .badges {
                display: flex;
                justify-content: space-between;
                align-items: center;
                margin-bottom: 0.75rem;
            }

            .app-types {
                display: flex;
                gap: 0.5rem;
                flex-wrap: wrap;
                margin-bottom: 0.5rem;
            }

            .app-type-badge {
                padding: 0.25rem 0.75rem;
                border-radius: 2rem;
                font-size: 0.8rem;
                font-weight: 500;
                color: white;
            }

            .status-badge {
                padding: 0.25rem 0.75rem;
                border-radius: 4px;
                font-size: 0.8rem;
                font-weight: 500;
                color: white;
            }

            .status-badge.completed {
                background-color: #059669;
            }

            .status-badge.in_development {
                background-color: #d97706;
            }

            @media (max-width: 768px) {
                .hero-section {
                    padding: 2rem 0 1rem;
                }

                .title {
                    font-size: 2.5rem;
                }

                .subtitle {
                    font-size: 1.2rem;
                }

                .container {
                    padding: 0 1rem;
                }

                .screenshot-container {
                    max-height: 70vh;
                }

                .screenshot {
                    max-height: 70vh;
                }
            }
        </style>
    </x-slot>
</x-app-layout> 