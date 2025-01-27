{{-- タブナビゲーション専用コンポーネント --}}
<div class="bg-white/50 backdrop-blur-lg rounded-xl p-4 shadow-lg mb-6">
    <nav class="relative mb-8">
        {{-- タブコンテナ --}}
        <div class="relative bg-white/90 backdrop-blur-xl rounded-2xl p-4 shadow-xl border border-white/20">
            <div class="flex flex-wrap gap-2 justify-center">
                @foreach ($sections as $id => $section)
                    <button
                        @click="switchTab('{{ $id }}')"
                        :class="{
                            'relative px-6 py-3 rounded-xl font-bold text-sm transition-all duration-300': true,
                            'bg-gradient-to-br from-violet-500 via-fuchsia-500 to-pink-500 shadow-xl scale-105': activeTab === '{{ $id }}',
                            'bg-white/50 hover:bg-white/80': activeTab !== '{{ $id }}'
                        }"
                    >
                        {{-- アイコンとテキストのコンテナ --}}
                        <div class="flex items-center gap-2">
                            {{-- タブごとのアイコン --}}
                            <span class="text-lg" :class="{
                                'opacity-100': activeTab === '{{ $id }}',
                                'opacity-70': activeTab !== '{{ $id }}'
                            }">
                                @switch($id)
                                    @case('basic')
                                        📝
                                        @break
                                    @case('screenshots')
                                        📸
                                        @break
                                    @case('story')
                                        📖
                                        @break
                                    @case('hardware')
                                        💻
                                        @break
                                    @case('dev_env')
                                        🛠️
                                        @break
                                    @case('architecture')
                                        🏗️
                                        @break
                                    @case('frontend')
                                        🎨
                                        @break
                                    @case('backend')
                                        ⚙️
                                        @break
                                    @case('database')
                                        💾
                                        @break
                                    @case('security')
                                        🔒
                                        @break
                                @endswitch
                            </span>
                            
                            {{-- タブのタイトル --}}
                            <span class="whitespace-nowrap font-bold text-base" :class="{
                                'text-black drop-shadow-[0_2px_2px_rgba(255,255,255,0.7)]': activeTab === '{{ $id }}',
                                'text-gray-600': activeTab !== '{{ $id }}'
                            }">{{ $section['title'] }}</span>

                            {{-- アクティブ時のバッジ --}}
                            <span x-show="activeTab === '{{ $id }}'" 
                                  class="ml-2 text-[10px] bg-white/30 backdrop-blur-sm px-2 py-0.5 rounded-full text-black tracking-wider uppercase font-medium">
                                active
                            </span>
                        </div>

                        {{-- アクティブタブのインジケーター --}}
                        <div
                            x-show="activeTab === '{{ $id }}'"
                            class="absolute -bottom-2 left-1/2 transform -translate-x-1/2 w-12 h-1 bg-gradient-to-r from-violet-500 to-fuchsia-500 rounded-full shadow-lg"
                        ></div>
                    </button>
                @endforeach
            </div>
        </div>

        {{-- 装飾的な要素 --}}
        <div class="absolute -inset-1 bg-gradient-to-r from-violet-500/10 via-fuchsia-500/10 to-pink-500/10 rounded-2xl blur-xl -z-10"></div>
    </nav>
</div>

<style>
    /* よりスムーズなアニメーション */
    @keyframes subtle-glow {
        0%, 100% { 
            box-shadow: 0 0 15px rgba(139, 92, 246, 0.3),
                       inset 0 0 15px rgba(236, 72, 153, 0.3);
        }
        50% { 
            box-shadow: 0 0 30px rgba(139, 92, 246, 0.5),
                       inset 0 0 30px rgba(236, 72, 153, 0.5);
        }
    }

    button[class*="scale-105"] {
        animation: subtle-glow 3s ease-in-out infinite;
    }
</style> 