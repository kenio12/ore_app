{{-- 基本情報タブ全体のコンテナ --}}
<div class="space-y-8">
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

    {{-- アプリ名入力部分 --}}
    <div class="transform hover:scale-[1.02] transition-all duration-500">
        <div class="bg-white/50 backdrop-blur-lg rounded-xl p-6 shadow-xl hover:shadow-2xl border border-white/20">
            <label class="block text-xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-pink-600 to-purple-600">
                アプリ名
                <span class="ml-2 text-sm text-red-600 animate-pulse">※ 必須</span>
            </label>
            <div class="relative mt-4">
                <input 
                    type="text"
                    x-model="formData.basic.title"
                    x-ref="titleInput"
                    @input="handleInput"
                    @blur="checkDefaultTitle()"
                    class="w-full rounded-lg p-4 text-lg
                           border-4 border-yellow-300
                           bg-yellow-50
                           shadow-[0_0_30px_rgba(255,200,0,0.7)]
                           focus:border-yellow-400
                           focus:shadow-[0_0_50px_rgba(255,200,0,0.9)]
                           focus:outline-none
                           animate-pulse"
                    placeholder="まずはアプリ名を入力してください！"
                    autofocus
                >
            </div>
            <p class="mt-2 text-red-500 text-sm font-bold">
                ※ アプリ名を書き換えないと保存できませんよ！！
            </p>
        </div>
    </div>

    {{-- 作者名（シンプルに） --}}
    <div class="transform hover:scale-[1.02] transition-all duration-500">
        <div class="bg-white/50 backdrop-blur-lg rounded-xl p-6 shadow-xl hover:shadow-2xl border border-white/20">
            <label class="block text-xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-pink-600 to-purple-600">
                アプリの作者名
            </label>
            <input 
                type="text"
                value="{{ auth()->user()->name ?? '未設定' }}"
                class="mt-4 w-full rounded-lg border-2 border-gray-200 p-4 text-lg
                       bg-gray-50 text-gray-700"
                disabled
                placeholder="作者名が自動的に表示されます"
            >
        </div>
    </div>

    {{-- アプリの概要 --}}
    <div class="transform hover:scale-[1.02] transition-all duration-500">
        <div class="bg-white/50 backdrop-blur-lg rounded-xl p-6 shadow-xl hover:shadow-2xl border border-white/20">
            <label class="block text-xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-purple-600 to-blue-600">
                アプリの概要
            </label>
            <textarea
                x-model="formData.basic.description"
                @input="handleInput"
                rows="8"
                class="mt-4 w-full rounded-lg border-2 border-gray-200 p-4 text-lg
                       focus:border-purple-500 focus:ring-4 focus:ring-purple-500/20
                       hover:border-purple-300 transition-all duration-300"
                placeholder="あなたのアプリの特徴や魅力を説明してください..."></textarea>
        </div>
    </div>

    {{-- このアプリを作るきっかけ --}}
    <div class="transform hover:scale-[1.02] transition-all duration-500">
        <div class="bg-white/50 backdrop-blur-lg rounded-xl p-6 shadow-xl hover:shadow-2xl border border-white/20">
            <label class="block text-xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-pink-600 to-blue-600">
                このアプリを作るきっかけ
            </label>
            <textarea
                x-model="formData.basic.motivation"
                @input="handleInput"
                rows="8"
                class="mt-4 w-full rounded-lg border-2 border-gray-200 p-4 text-lg
                       focus:border-pink-500 focus:ring-4 focus:ring-pink-500/20
                       hover:border-pink-300 transition-all duration-300"
                placeholder="このアプリを作るきっかけを教えてください..."></textarea>
        </div>
    </div>

    {{-- このアプリの目指すところ --}}
    <div class="transform hover:scale-[1.02] transition-all duration-500">
        <div class="bg-white/50 backdrop-blur-lg rounded-xl p-6 shadow-xl hover:shadow-2xl border border-white/20">
            <label class="block text-xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-blue-600 to-pink-600">
                このアプリの目指すところ
            </label>
            <textarea
                x-model="formData.basic.purpose"
                @input="handleInput"
                rows="8"
                class="mt-4 w-full rounded-lg border-2 border-gray-200 p-4 text-lg
                       focus:border-blue-500 focus:ring-4 focus:ring-blue-500/20
                       hover:border-blue-300 transition-all duration-300"
                placeholder="このアプリの目指すところを教えてください..."></textarea>
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

    {{-- 開発期間 --}}
    <div class="transform hover:scale-[1.02] transition-all duration-500">
        <div class="bg-white/50 backdrop-blur-lg rounded-xl p-6 shadow-xl hover:shadow-2xl border border-white/20">
            <label class="block text-xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-violet-600 to-pink-600">
                開発期間
            </label>
            <div class="mt-4 space-y-6">
                {{-- 開発開始日と終了日 --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    {{-- 開発開始日 --}}
                    <div>
                        <label class="block text-gray-700 mb-2">開発開始日</label>
                        <input 
                            type="date"
                            x-model="formData.basic.development_start_date"
                            x-init="$watch('formData.basic.development_start_date', value => {
                                if (value && value.includes('T')) {
                                    formData.basic.development_start_date = value.split('T')[0];
                                }
                            })"
                            class="w-full rounded-lg border-2 border-gray-200 p-4 text-lg
                                   focus:border-violet-500 focus:ring-4 focus:ring-violet-500/20
                                   hover:border-violet-300 transition-all duration-300"
                        >
                        {{-- デバッグ用に値を表示 --}}
                        <div x-text="formData.basic.development_start_date" class="mt-2 text-sm text-gray-500"></div>
                    </div>
                    {{-- 開発終了日 --}}
                    <div>
                        <label class="block text-gray-700 mb-2">開発終了日</label>
                        <input 
                            type="date"
                            x-model="formData.basic.development_end_date"
                            x-init="$watch('formData.basic.development_end_date', value => {
                                if (value && value.includes('T')) {
                                    formData.basic.development_end_date = value.split('T')[0];
                                }
                            })"
                            class="w-full rounded-lg border-2 border-gray-200 p-4 text-lg
                                   focus:border-violet-500 focus:ring-4 focus:ring-violet-500/20
                                   hover:border-violet-300 transition-all duration-300"
                        >
                        {{-- デバッグ用に値を表示 --}}
                        <div x-text="formData.basic.development_end_date" class="mt-2 text-sm text-gray-500"></div>
                    </div>
                </div>

                {{-- 開発期間（年月） --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-gray-700 mb-2">開発期間（年）</label>
                        <input 
                            type="number"
                            min="0"
                            max="99"
                            x-model.number="formData.basic.development_period_years"
                            @input="handleInput"
                            class="w-full rounded-lg border-2 border-gray-200 p-4 text-lg
                                   focus:border-violet-500 focus:ring-4 focus:ring-violet-500/20
                                   hover:border-violet-300 transition-all duration-300"
                            placeholder="0"
                        >
                    </div>
                    <div>
                        <label class="block text-gray-700 mb-2">開発期間（月）</label>
                        <input 
                            type="number"
                            min="0"
                            max="11"
                            x-model.number="formData.basic.development_period_months"
                            @input="handleInput"
                            class="w-full rounded-lg border-2 border-gray-200 p-4 text-lg
                                   focus:border-violet-500 focus:ring-4 focus:ring-violet-500/20
                                   hover:border-violet-300 transition-all duration-300"
                            placeholder="0"
                        >
                    </div>
                </div>
            </div>
        </div>
    </div>
</div> 