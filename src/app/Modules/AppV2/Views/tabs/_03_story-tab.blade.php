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
    <div class="relative overflow-hidden rounded-2xl bg-gradient-to-r from-pink-600 via-rose-600 to-red-600 p-[2px]">
        <div class="relative bg-white/90 backdrop-blur-xl rounded-2xl p-8">
            <div class="absolute inset-0 bg-gradient-to-r from-pink-50/50 to-red-50/50 animate-pulse"></div>
            <div class="relative">
                <h3 class="text-3xl font-black bg-clip-text text-transparent bg-gradient-to-r from-pink-600 to-red-600">
                    開発ストーリー
                </h3>
                <p class="mt-3 text-lg text-gray-600">アプリケーション開発の物語を教えてください。</p>
            </div>
        </div>
    </div>

    {{-- 開発のきっかけ --}}
    <div class="transform hover:scale-[1.02] transition-all duration-500">
        <div class="bg-white/50 backdrop-blur-lg rounded-xl p-6 shadow-xl hover:shadow-2xl border border-white/20">
            <label class="block text-xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-pink-600 to-rose-600">
                開発のきっかけ
            </label>
            <div class="mt-4">
                <textarea
                    x-model="formData.basic.development_trigger"
                    @input="handleInput"
                    rows="8"
                    class="w-full rounded-lg border-2 border-gray-200 p-4 text-lg
                           focus:border-pink-500 focus:ring-4 focus:ring-pink-500/20
                           hover:border-pink-300 transition-all duration-300"
                    placeholder="なぜこのアプリケーションの開発を始めたのですか？"></textarea>
            </div>
        </div>
    </div>

    {{-- 開発エピソード --}}
    <div class="transform hover:scale-[1.02] transition-all duration-500">
        <div class="bg-white/50 backdrop-blur-lg rounded-xl p-6 shadow-xl hover:shadow-2xl border border-white/20">
            <label class="block text-xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-rose-600 to-red-600">
                開発エピソード
            </label>
            <div class="mt-4 space-y-4">
                <textarea
                    x-model="formData.basic.development_hardship"
                    @input="handleInput"
                    rows="8"
                    class="w-full rounded-lg border-2 border-gray-200 p-4 text-lg
                           focus:border-rose-500 focus:ring-4 focus:ring-rose-500/20
                           hover:border-rose-300 transition-all duration-300"
                    placeholder="開発の苦しい話を教えてください"></textarea>

                <textarea
                    x-model="formData.basic.development_tearful"
                    @input="handleInput"
                    rows="8"
                    class="w-full rounded-lg border-2 border-gray-200 p-4 text-lg
                           focus:border-rose-500 focus:ring-4 focus:ring-rose-500/20
                           hover:border-rose-300 transition-all duration-300"
                    placeholder="開発の泣ける話を教えてください"></textarea>

                <textarea
                    x-model="formData.basic.development_enjoyable"
                    @input="handleInput"
                    rows="8"
                    class="w-full rounded-lg border-2 border-gray-200 p-4 text-lg
                           focus:border-rose-500 focus:ring-4 focus:ring-rose-500/20
                           hover:border-rose-300 transition-all duration-300"
                    placeholder="開発の楽しい話を教えてください"></textarea>

                <textarea
                    x-model="formData.basic.development_funny"
                    @input="handleInput"
                    rows="8"
                    class="w-full rounded-lg border-2 border-gray-200 p-4 text-lg
                           focus:border-rose-500 focus:ring-4 focus:ring-rose-500/20
                           hover:border-rose-300 transition-all duration-300"
                    placeholder="開発の笑える話を教えてください"></textarea>
            </div>
        </div>
    </div>

    {{-- 開発を通しての気づき --}}
    <div class="transform hover:scale-[1.02] transition-all duration-500">
        <div class="bg-white/50 backdrop-blur-lg rounded-xl p-6 shadow-xl hover:shadow-2xl border border-white/20">
            <label class="block text-xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-red-600 to-rose-600">
                開発を通しての気づき
            </label>
            <div class="mt-4 space-y-4">
                <textarea
                    x-model="formData.basic.development_impression"
                    @input="handleInput"
                    rows="8"
                    class="w-full rounded-lg border-2 border-gray-200 p-4 text-lg
                           focus:border-red-500 focus:ring-4 focus:ring-red-500/20
                           hover:border-red-300 transition-all duration-300"
                    placeholder="開発を通して感じたことを教えてください"></textarea>

                <textarea
                    x-model="formData.basic.development_oneword"
                    @input="handleInput"
                    rows="8"
                    class="w-full rounded-lg border-2 border-gray-200 p-4 text-lg
                           focus:border-red-500 focus:ring-4 focus:ring-red-500/20
                           hover:border-red-300 transition-all duration-300"
                    placeholder="開発を終えての一言をお願いします"></textarea>
            </div>
        </div>
    </div>
</div> 