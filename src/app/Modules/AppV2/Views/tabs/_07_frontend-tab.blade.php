<div class="space-y-8">
    {{-- 超豪華ヘッダー --}}
    <div class="relative overflow-hidden rounded-2xl bg-gradient-to-r from-rose-600 via-pink-600 to-fuchsia-600 p-[2px]">
        <div class="relative bg-white/90 backdrop-blur-xl rounded-2xl p-8">
            <div class="absolute inset-0 bg-gradient-to-r from-rose-50/50 to-fuchsia-50/50 animate-pulse"></div>
            <div class="relative">
                <h3 class="text-3xl font-black bg-clip-text text-transparent bg-gradient-to-r from-rose-600 to-fuchsia-600">
                    フロントエンド
                </h3>
                <p class="mt-3 text-lg text-gray-600">フロントエンドの技術スタックについて教えてください。</p>
            </div>
        </div>
    </div>

    {{-- フレームワーク --}}
    <div class="transform hover:scale-[1.02] transition-all duration-500">
        <div class="bg-white/50 backdrop-blur-lg rounded-xl p-6 shadow-xl hover:shadow-2xl border border-white/20">
            <label class="block text-xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-rose-600 to-pink-600">
                使用フレームワーク
            </label>
            <div class="mt-4">
                <input type="text"
                       x-model="formData.frontend.framework"
                       @input="autoSave"
                       class="w-full rounded-lg border-2 border-gray-200 p-4 text-lg
                              focus:border-rose-500 focus:ring-4 focus:ring-rose-500/20
                              hover:border-rose-300 transition-all duration-300"
                       placeholder="例: React, Vue.js, Angular">
            </div>
        </div>
    </div>

    {{-- UIライブラリ --}}
    <div class="transform hover:scale-[1.02] transition-all duration-500">
        <div class="bg-white/50 backdrop-blur-lg rounded-xl p-6 shadow-xl hover:shadow-2xl border border-white/20">
            <label class="block text-xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-pink-600 to-fuchsia-600">
                UIライブラリ
            </label>
            <div class="mt-4">
                <input type="text"
                       x-model="formData.frontend.ui_library"
                       @input="autoSave"
                       class="w-full rounded-lg border-2 border-gray-200 p-4 text-lg
                              focus:border-pink-500 focus:ring-4 focus:ring-pink-500/20
                              hover:border-pink-300 transition-all duration-300"
                       placeholder="例: Tailwind CSS, Material-UI">
            </div>
        </div>
    </div>
</div> 