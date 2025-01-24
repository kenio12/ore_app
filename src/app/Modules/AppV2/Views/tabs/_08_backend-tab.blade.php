<div class="space-y-8">
    {{-- 超豪華ヘッダー --}}
    <div class="relative overflow-hidden rounded-2xl bg-gradient-to-r from-blue-600 via-indigo-600 to-violet-600 p-[2px]">
        <div class="relative bg-white/90 backdrop-blur-xl rounded-2xl p-8">
            <div class="absolute inset-0 bg-gradient-to-r from-blue-50/50 to-violet-50/50 animate-pulse"></div>
            <div class="relative">
                <h3 class="text-3xl font-black bg-clip-text text-transparent bg-gradient-to-r from-blue-600 to-violet-600">
                    バックエンド環境
                </h3>
                <p class="mt-3 text-lg text-gray-600">
                    以下のポイントを考慮して説明してください：
                    @foreach(config('appv2.constants.backend.package_hints') as $hint)
                        <span class="block">・{{ $hint }}</span>
                    @endforeach
                </p>
            </div>
        </div>
    </div>

    {{-- プログラミング言語 --}}
    <div class="transform hover:scale-[1.02] transition-all duration-500">
        <div class="bg-white/50 backdrop-blur-lg rounded-xl p-6 shadow-xl hover:shadow-2xl border border-white/20">
            <label class="block text-xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-blue-600 to-indigo-600">
                プログラミング言語
            </label>
            <div class="mt-4 grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                @foreach(config('appv2.constants.backend.languages') as $key => $value)
                    <label class="relative">
                        <input 
                            type="checkbox"
                            x-model="formData.backend.languages"
                            @change="autoSave"
                            value="{{ $key }}"
                            class="peer sr-only">
                        <div class="rounded-lg border-2 border-gray-200 p-4 cursor-pointer
                                  peer-checked:border-blue-500 peer-checked:bg-blue-50
                                  hover:border-blue-300 transition-all duration-300">
                            {{ $value }}
                        </div>
                    </label>
                @endforeach
            </div>
        </div>
    </div>

    {{-- フレームワーク --}}
    <div class="transform hover:scale-[1.02] transition-all duration-500">
        <div class="bg-white/50 backdrop-blur-lg rounded-xl p-6 shadow-xl hover:shadow-2xl border border-white/20">
            <label class="block text-xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-indigo-600 to-violet-600">
                フレームワーク
            </label>
            <div class="mt-4 grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                @foreach(config('appv2.constants.backend.frameworks') as $key => $value)
                    <label class="relative">
                        <input 
                            type="checkbox"
                            x-model="formData.backend.frameworks"
                            @change="autoSave"
                            value="{{ $key }}"
                            class="peer sr-only">
                        <div class="rounded-lg border-2 border-gray-200 p-4 cursor-pointer
                                  peer-checked:border-indigo-500 peer-checked:bg-indigo-50
                                  hover:border-indigo-300 transition-all duration-300">
                            {{ $value }}
                        </div>
                    </label>
                @endforeach
            </div>
        </div>
    </div>

    {{-- 主要なパッケージ・ライブラリ --}}
    <div class="transform hover:scale-[1.02] transition-all duration-500">
        <div class="bg-white/50 backdrop-blur-lg rounded-xl p-6 shadow-xl hover:shadow-2xl border border-white/20">
            <label class="block text-xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-violet-600 to-blue-600">
                主要なパッケージ・ライブラリ
            </label>
            <textarea
                x-model="formData.backend.packages"
                @input="autoSave"
                rows="4"
                class="mt-4 w-full rounded-lg border-2 border-gray-200 p-4 text-lg
                       focus:border-violet-500 focus:ring-4 focus:ring-violet-500/20
                       hover:border-violet-300 transition-all duration-300"
                placeholder="使用している主要なパッケージやライブラリを説明してください..."></textarea>
        </div>
    </div>
</div>