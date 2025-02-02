<div class="space-y-8">
    <div class="relative overflow-hidden rounded-2xl bg-gradient-to-r from-red-600 via-orange-600 to-amber-600 p-[2px]">
        <div class="relative bg-white/90 backdrop-blur-xl rounded-2xl p-8">
            <div class="absolute inset-0 bg-gradient-to-r from-red-50/50 to-amber-50/50 animate-pulse"></div>
            <div class="relative">
                <h3 class="text-3xl font-black bg-clip-text text-transparent bg-gradient-to-r from-red-600 to-amber-600">セキュリティ</h3>
                <p class="mt-3 text-lg text-gray-600">セキュリティ対策について教えてください。</p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 gap-8">
        <div class="transform hover:scale-[1.02] transition-all duration-500">
            <div class="bg-white/50 backdrop-blur-lg rounded-xl p-6 shadow-xl hover:shadow-2xl border border-white/20">
                <label class="block text-xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-red-600 to-orange-600">認証方式</label>
                <input type="text" x-model="formData.security.auth" @input="autoSave" class="mt-4 w-full rounded-lg border-2 border-gray-200 p-4 text-lg focus:border-red-500 focus:ring-4 focus:ring-red-500/20 hover:border-red-300 transition-all duration-300" placeholder="例: JWT, OAuth2.0">
            </div>
        </div>

        <div class="transform hover:scale-[1.02] transition-all duration-500">
            <div class="bg-white/50 backdrop-blur-lg rounded-xl p-6 shadow-xl hover:shadow-2xl border border-white/20">
                <label class="block text-xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-orange-600 to-amber-600">セキュリティ対策</label>
                <textarea x-model="formData.security.measures" @input="autoSave" rows="4" class="mt-4 w-full rounded-lg border-2 border-gray-200 p-4 text-lg focus:border-orange-500 focus:ring-4 focus:ring-orange-500/20 hover:border-orange-300 transition-all duration-300" placeholder="実装したセキュリティ対策について説明してください..."></textarea>
            </div>
        </div>
    </div>
</div>