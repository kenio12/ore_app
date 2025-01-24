<div class="space-y-8">
    {{-- 超豪華ヘッダー --}}
    <div class="relative overflow-hidden rounded-2xl bg-gradient-to-r from-violet-600 via-purple-600 to-fuchsia-600 p-[2px]">
        <div class="relative bg-white/90 backdrop-blur-xl rounded-2xl p-8">
            <div class="absolute inset-0 bg-gradient-to-r from-violet-50/50 to-fuchsia-50/50 animate-pulse"></div>
            <div class="relative">
                <h3 class="text-3xl font-black bg-clip-text text-transparent bg-gradient-to-r from-violet-600 to-fuchsia-600">
                    開発環境
                </h3>
                <p class="mt-3 text-lg text-gray-600">アプリケーションの開発環境について教えてください。</p>
            </div>
        </div>
    </div>

    {{-- IDE/エディタ --}}
    <div class="transform hover:scale-[1.02] transition-all duration-500">
        <div class="bg-white/50 backdrop-blur-lg rounded-xl p-6 shadow-xl hover:shadow-2xl border border-white/20">
            <label class="block text-xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-violet-600 to-purple-600">
                使用したIDE/エディタ
            </label>
            <div class="mt-4">
                <input type="text"
                       x-model="formData.dev_env.editor"
                       @input="autoSave"
                       class="w-full rounded-lg border-2 border-gray-200 p-4 text-lg
                              focus:border-violet-500 focus:ring-4 focus:ring-violet-500/20
                              hover:border-violet-300 transition-all duration-300"
                       placeholder="例: Visual Studio Code, IntelliJ IDEA">
            </div>
        </div>
    </div>

    {{-- バージョン管理 --}}
    <div class="transform hover:scale-[1.02] transition-all duration-500">
        <div class="bg-white/50 backdrop-blur-lg rounded-xl p-6 shadow-xl hover:shadow-2xl border border-white/20">
            <label class="block text-xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-purple-600 to-fuchsia-600">
                バージョン管理システム
            </label>
            <div class="mt-4">
                <input type="text"
                       x-model="formData.dev_env.version_control"
                       @input="autoSave"
                       class="w-full rounded-lg border-2 border-gray-200 p-4 text-lg
                              focus:border-purple-500 focus:ring-4 focus:ring-purple-500/20
                              hover:border-purple-300 transition-all duration-300"
                       placeholder="例: Git, GitHub">
            </div>
        </div>
    </div>

    {{-- CI/CD --}}
    <div class="transform hover:scale-[1.02] transition-all duration-500">
        <div class="bg-white/50 backdrop-blur-lg rounded-xl p-6 shadow-xl hover:shadow-2xl border border-white/20">
            <label class="block text-xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-fuchsia-600 to-pink-600">
                CI/CDツール
            </label>
            <div class="mt-4">
                <input type="text"
                       x-model="formData.dev_env.ci_cd"
                       @input="autoSave"
                       class="w-full rounded-lg border-2 border-gray-200 p-4 text-lg
                              focus:border-fuchsia-500 focus:ring-4 focus:ring-fuchsia-500/20
                              hover:border-fuchsia-300 transition-all duration-300"
                       placeholder="例: GitHub Actions, Jenkins">
            </div>
        </div>
    </div>
</div>