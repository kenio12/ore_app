<div class="space-y-8">
    {{-- 超豪華ヘッダー --}}
    <div class="relative overflow-hidden rounded-2xl bg-gradient-to-r from-indigo-600 via-blue-600 to-cyan-600 p-[2px]">
        <div class="relative bg-white/90 backdrop-blur-xl rounded-2xl p-8">
            <div class="absolute inset-0 bg-gradient-to-r from-indigo-50/50 to-cyan-50/50 animate-pulse"></div>
            <div class="relative">
                <h3 class="text-3xl font-black bg-clip-text text-transparent bg-gradient-to-r from-indigo-600 to-cyan-600">
                    ハードウェア要件
                </h3>
                <p class="mt-3 text-lg text-gray-600">アプリケーションの動作に必要なハードウェア要件を教えてください。</p>
            </div>
        </div>
    </div>

    {{-- CPU要件 --}}
    <div class="transform hover:scale-[1.02] transition-all duration-500">
        <div class="bg-white/50 backdrop-blur-lg rounded-xl p-6 shadow-xl hover:shadow-2xl border border-white/20">
            <label class="block text-xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-indigo-600 to-blue-600">
                CPU要件
            </label>
            <div class="mt-4">
                <input type="text"
                       x-model="formData.hardware.cpu"
                       @input="autoSave"
                       class="w-full rounded-lg border-2 border-gray-200 p-4 text-lg
                              focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/20
                              hover:border-indigo-300 transition-all duration-300"
                       placeholder="例: Intel Core i5 以上">
            </div>
        </div>
    </div>

    {{-- メモリ要件 --}}
    <div class="transform hover:scale-[1.02] transition-all duration-500">
        <div class="bg-white/50 backdrop-blur-lg rounded-xl p-6 shadow-xl hover:shadow-2xl border border-white/20">
            <label class="block text-xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-blue-600 to-cyan-600">
                メモリ要件
            </label>
            <div class="mt-4">
                <input type="text"
                       x-model="formData.hardware.memory"
                       @input="autoSave"
                       class="w-full rounded-lg border-2 border-gray-200 p-4 text-lg
                              focus:border-blue-500 focus:ring-4 focus:ring-blue-500/20
                              hover:border-blue-300 transition-all duration-300"
                       placeholder="例: 8GB RAM 以上">
            </div>
        </div>
    </div>

    {{-- ストレージ要件 --}}
    <div class="transform hover:scale-[1.02] transition-all duration-500">
        <div class="bg-white/50 backdrop-blur-lg rounded-xl p-6 shadow-xl hover:shadow-2xl border border-white/20">
            <label class="block text-xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-cyan-600 to-teal-600">
                ストレージ要件
            </label>
            <div class="mt-4">
                <input type="text"
                       x-model="formData.hardware.storage"
                       @input="autoSave"
                       class="w-full rounded-lg border-2 border-gray-200 p-4 text-lg
                              focus:border-cyan-500 focus:ring-4 focus:ring-cyan-500/20
                              hover:border-cyan-300 transition-all duration-300"
                       placeholder="例: 256GB SSD 以上">
            </div>
        </div>
    </div>
</div>