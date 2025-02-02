<div class="space-y-8">
    <div class="relative overflow-hidden rounded-2xl bg-gradient-to-r from-green-600 via-emerald-600 to-teal-600 p-[2px]">
        <div class="relative bg-white/90 backdrop-blur-xl rounded-2xl p-8">
            <div class="absolute inset-0 bg-gradient-to-r from-green-50/50 to-teal-50/50 animate-pulse"></div>
            <div class="relative">
                <h3 class="text-3xl font-black bg-clip-text text-transparent bg-gradient-to-r from-green-600 to-teal-600">データベース</h3>
                <p class="mt-3 text-lg text-gray-600">使用しているデータベースについて教えてください。</p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 gap-8">
        <div class="transform hover:scale-[1.02] transition-all duration-500">
            <div class="bg-white/50 backdrop-blur-lg rounded-xl p-6 shadow-xl hover:shadow-2xl border border-white/20">
                <label class="block text-xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-green-600 to-emerald-600">データベース種類</label>
                <input type="text" x-model="formData.database.type" @input="autoSave" class="mt-4 w-full rounded-lg border-2 border-gray-200 p-4 text-lg focus:border-green-500 focus:ring-4 focus:ring-green-500/20 hover:border-green-300 transition-all duration-300" placeholder="例: MySQL, PostgreSQL, MongoDB">
            </div>
        </div>

        <div class="transform hover:scale-[1.02] transition-all duration-500">
            <div class="bg-white/50 backdrop-blur-lg rounded-xl p-6 shadow-xl hover:shadow-2xl border border-white/20">
                <label class="block text-xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-emerald-600 to-teal-600">データベース設計</label>
                <textarea x-model="formData.database.design" @input="autoSave" rows="4" class="mt-4 w-full rounded-lg border-2 border-gray-200 p-4 text-lg focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/20 hover:border-emerald-300 transition-all duration-300" placeholder="データベース設計について説明してください..."></textarea>
            </div>
        </div>
    </div>
</div>