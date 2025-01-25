<div class="space-y-8"
    x-data="{
        debouncedAutoSave: Alpine.debounce(function() {
            this.autoSave();
        }, 1000),
        handleInput() {
            this.debouncedAutoSave();
        }
    }"
>
    {{-- 超豪華ヘッダー --}}
    <div class="relative overflow-hidden rounded-2xl bg-gradient-to-r from-pink-600 via-purple-600 to-blue-600 p-[2px]">
        <div class="relative bg-white/90 backdrop-blur-xl rounded-2xl p-8">
            <div class="absolute inset-0 bg-gradient-to-r from-pink-50/50 to-blue-50/50 animate-pulse"></div>
            <div class="relative">
                <h3 class="text-3xl font-black bg-clip-text text-transparent bg-gradient-to-r from-pink-600 to-blue-600">
                    基本情報
                </h3>
                <p class="mt-3 text-lg text-gray-600">
                    アプリケーションの基本的な情報を入力してください
                </p>
            </div>
        </div>
    </div>

    {{-- アプリ名 --}}
    <div class="transform hover:scale-[1.02] transition-all duration-500">
        <div class="bg-white/50 backdrop-blur-lg rounded-xl p-6 shadow-xl hover:shadow-2xl border border-white/20">
            <label class="block text-xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-pink-600 to-purple-600">
                アプリ名
            </label>
            <input 
                type="text"
                x-model="formData.basic.title"
                @input="handleInput"
                class="mt-4 w-full rounded-lg border-2 border-gray-200 p-4 text-lg
                       focus:border-pink-500 focus:ring-4 focus:ring-pink-500/20
                       hover:border-pink-300 transition-all duration-300"
                placeholder="あなたのアプリの名前を入力してください..."
            >
        </div>
    </div>

    {{-- アプリの説明 --}}
    <div class="transform hover:scale-[1.02] transition-all duration-500">
        <div class="bg-white/50 backdrop-blur-lg rounded-xl p-6 shadow-xl hover:shadow-2xl border border-white/20">
            <label class="block text-xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-purple-600 to-blue-600">
                アプリの説明
            </label>
            <textarea
                x-model="formData.basic.description"
                @input="handleInput"
                rows="4"
                class="mt-4 w-full rounded-lg border-2 border-gray-200 p-4 text-lg
                       focus:border-purple-500 focus:ring-4 focus:ring-purple-500/20
                       hover:border-purple-300 transition-all duration-300"
                placeholder="あなたのアプリの特徴や魅力を説明してください..."></textarea>
        </div>
    </div>

    {{-- アプリの種類 --}}
    <div class="transform hover:scale-[1.02] transition-all duration-500">
        <div class="bg-white/50 backdrop-blur-lg rounded-xl p-6 shadow-xl hover:shadow-2xl border border-white/20">
            <label class="block text-xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-blue-600 to-pink-600">
                アプリの種類
            </label>
            <div class="mt-4 grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                @foreach(config('appv2.constants.app_types') as $key => $value)
                    <label class="relative cursor-pointer">
                        <input 
                            type="checkbox"
                            x-model="formData.basic.types"
                            value="{{ $key }}"
                            class="peer sr-only"
                            @change="$nextTick(() => handleInput())"
                        >
                        <div 
                            :class="{
                                'border-blue-500 bg-blue-50 text-blue-700': formData.basic.types.includes('{{ $key }}'),
                                'border-gray-200 hover:border-blue-300': !formData.basic.types.includes('{{ $key }}')
                            }"
                            class="rounded-lg border-2 p-4 transition-all duration-300 flex items-center justify-center"
                        >
                            <span class="block text-center">{{ $value }}</span>
                        </div>
                    </label>
                @endforeach
            </div>
        </div>
    </div>

    {{-- ジャンル --}}
    <div class="transform hover:scale-[1.02] transition-all duration-500">
        <div class="bg-white/50 backdrop-blur-lg rounded-xl p-6 shadow-xl hover:shadow-2xl border border-white/20">
            <label class="block text-xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-indigo-600 to-violet-600">
                ジャンル
            </label>
            <div class="mt-4 grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                @foreach(config('appv2.constants.genres') as $key => $value)
                    <label class="relative cursor-pointer">
                        <input 
                            type="checkbox"
                            x-model="formData.basic.genres"
                            value="{{ $key }}"
                            class="peer sr-only"
                            @change="$nextTick(() => handleInput())"
                        >
                        <div 
                            :class="{
                                'border-indigo-500 bg-indigo-50 text-indigo-700': formData.basic.genres.includes('{{ $key }}'),
                                'border-gray-200 hover:border-indigo-300': !formData.basic.genres.includes('{{ $key }}')
                            }"
                            class="rounded-lg border-2 p-4 transition-all duration-300 flex items-center justify-center"
                        >
                            <span class="block text-center">{{ $value }}</span>
                        </div>
                    </label>
                @endforeach
            </div>
        </div>
    </div>

    {{-- 開発状況 --}}
    <div class="transform hover:scale-[1.02] transition-all duration-500">
        <div class="bg-white/50 backdrop-blur-lg rounded-xl p-6 shadow-xl hover:shadow-2xl border border-white/20">
            <label class="block text-xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-violet-600 to-pink-600">
                開発状況
            </label>
            <div class="mt-4">
                <select x-model="formData.basic.app_status"
                        @change="handleInput"
                        class="mt-4 w-full rounded-lg border-2 border-gray-200 p-4 text-lg
                               focus:border-violet-500 focus:ring-4 focus:ring-violet-500/20
                               hover:border-violet-300 transition-all duration-300">
                    <option value="">選択してください</option>
                    @foreach(config('appv2.constants.app_status') as $key => $value)
                        <option value="{{ $key }}">{{ $value }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    {{-- デモURL追加 --}}
    <div class="transform hover:scale-[1.02] transition-all duration-500">
        <div class="bg-white/50 backdrop-blur-lg rounded-xl p-6 shadow-xl hover:shadow-2xl border border-white/20">
            <label class="block text-xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-pink-600 to-blue-600">
                アプリへのアクセス
            </label>
            <input 
                type="url" 
                x-model="formData.basic.demo_url"
                @input="handleInput"
                class="mt-4 w-full rounded-lg border-2 border-gray-200 p-4 text-lg
                       focus:border-pink-500 focus:ring-4 focus:ring-pink-500/20
                       hover:border-pink-300 transition-all duration-300"
                placeholder="https://your-demo-site.com"
            >
        </div>
    </div>

    {{-- GitHubリポジトリURL追加 --}}
    <div class="transform hover:scale-[1.02] transition-all duration-500">
        <div class="bg-white/50 backdrop-blur-lg rounded-xl p-6 shadow-xl hover:shadow-2xl border border-white/20">
            <label class="block text-xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-pink-600 to-blue-600">
                GitHubリポジトリURL
            </label>
            <input 
                type="url" 
                x-model="formData.basic.github_url"
                @input="handleInput"
                class="mt-4 w-full rounded-lg border-2 border-gray-200 p-4 text-lg
                       focus:border-pink-500 focus:ring-4 focus:ring-pink-500/20
                       hover:border-pink-300 transition-all duration-300"
                placeholder="https://github.com/username/repo"
            >
        </div>
    </div>

    {{-- 開発期間追加 --}}
    <div class="transform hover:scale-[1.02] transition-all duration-500">
        <div class="bg-white/50 backdrop-blur-lg rounded-xl p-6 shadow-xl hover:shadow-2xl border border-white/20">
            <label class="block text-xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-pink-600 to-blue-600">
                開発期間
            </label>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                <div>
                    <label class="block text-sm text-gray-600 mb-1">開発開始日</label>
                    <input 
                        type="date" 
                        x-model="formData.basic.development_start_date"
                        @input="handleInput"
                        class="w-full rounded-lg border-2 border-gray-200 p-4
                               focus:border-pink-500 focus:ring-4 focus:ring-pink-500/20"
                    >
                </div>
                <div>
                    <label class="block text-sm text-gray-600 mb-1">開発終了日</label>
                    <input 
                        type="date" 
                        x-model="formData.basic.development_end_date"
                        @input="handleInput"
                        class="w-full rounded-lg border-2 border-gray-200 p-4
                               focus:border-pink-500 focus:ring-4 focus:ring-pink-500/20"
                    >
                </div>
            </div>
            <div class="flex items-center gap-4 mt-4">
                <div class="flex items-center gap-2">
                    <input 
                        type="number" 
                        x-model="formData.basic.development_period_years"
                        @input="handleInput"
                        class="w-20 rounded-lg border-2 border-gray-200 p-4
                               focus:border-pink-500 focus:ring-4 focus:ring-pink-500/20"
                        min="0"
                    >
                    <span class="text-gray-700">年</span>
                </div>
                <div class="flex items-center gap-2">
                    <input 
                        type="number" 
                        x-model="formData.basic.development_period_months"
                        @input="handleInput"
                        class="w-20 rounded-lg border-2 border-gray-200 p-4
                               focus:border-pink-500 focus:ring-4 focus:ring-pink-500/20"
                        min="0"
                        max="11"
                    >
                    <span class="text-gray-700">ヶ月</span>
                </div>
            </div>
        </div>
    </div>
</div> 