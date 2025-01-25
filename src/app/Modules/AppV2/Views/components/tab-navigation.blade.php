{{-- タブナビゲーション専用コンポーネント --}}
<div class="bg-white/50 backdrop-blur-lg rounded-xl p-4 shadow-lg mb-6">
    <nav class="flex flex-wrap gap-2">
        @foreach(config('appv2.constants.tabs') as $tabKey => $tab)
            <button
                @click="activeTab = '{{ $tabKey }}'"
                :class="{
                    'bg-blue-500 text-white ring-2 ring-blue-300': activeTab === '{{ $tabKey }}',
                    'bg-white text-gray-600 hover:bg-gray-50': activeTab !== '{{ $tabKey }}'
                }"
                class="flex items-center gap-2 px-4 py-2 rounded-lg transition-all duration-200 font-medium"
            >
                @if(isset($tab['icon']))
                    <span class="text-lg">
                        <i class="fas fa-{{ $tab['icon'] }}"></i>
                    </span>
                @endif
                {{ $tab['label'] }}
            </button>
        @endforeach
    </nav>
</div> 