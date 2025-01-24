<div class="space-y-8">
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

    {{-- 開発動機 --}}
    <div class="transform hover:scale-[1.02] transition-all duration-500">
        <div class="bg-white/50 backdrop-blur-lg rounded-xl p-6 shadow-xl hover:shadow-2xl border border-white/20">
            <label class="block text-xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-pink-600 to-rose-600">
                開発のきっかけ
            </label>
            <div class="mt-4">
                <textarea
                    x-model="formData.story.motivation"
                    @input="autoSave"
                    rows="4"
                    class="w-full rounded-lg border-2 border-gray-200 p-4 text-lg
                           focus:border-pink-500 focus:ring-4 focus:ring-pink-500/20
                           hover:border-pink-300 transition-all duration-300"
                    placeholder="なぜこのアプリケーションの開発を始めたのですか？"></textarea>
            </div>
        </div>
    </div>

    {{-- 開発の課題 --}}
    <div class="transform hover:scale-[1.02] transition-all duration-500">
        <div class="bg-white/50 backdrop-blur-lg rounded-xl p-6 shadow-xl hover:shadow-2xl border border-white/20">
            <label class="block text-xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-rose-600 to-red-600">
                乗り越えた課題
            </label>
            <div class="mt-4">
                <textarea
                    x-model="formData.story.challenges"
                    @input="autoSave"
                    rows="4"
                    class="w-full rounded-lg border-2 border-gray-200 p-4 text-lg
                           focus:border-rose-500 focus:ring-4 focus:ring-rose-500/20
                           hover:border-rose-300 transition-all duration-300"
                    placeholder="開発中に直面した課題とその解決方法を教えてください"></textarea>
            </div>
        </div>
    </div>

    {{-- 今後の展望 --}}
    <div class="transform hover:scale-[1.02] transition-all duration-500">
        <div class="bg-white/50 backdrop-blur-lg rounded-xl p-6 shadow-xl hover:shadow-2xl border border-white/20">
            <label class="block text-xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-red-600 to-rose-600">
                今後の展望
            </label>
            <div class="mt-4">
                <textarea
                    x-model="formData.story.future"
                    @input="autoSave"
                    rows="4"
                    class="w-full rounded-lg border-2 border-gray-200 p-4 text-lg
                           focus:border-red-500 focus:ring-4 focus:ring-red-500/20
                           hover:border-red-300 transition-all duration-300"
                    placeholder="このアプリケーションの将来のビジョンを教えてください"></textarea>
            </div>
        </div>
    </div>
</div> 