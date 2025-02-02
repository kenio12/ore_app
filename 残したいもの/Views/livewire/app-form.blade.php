<div>
    <form wire:submit.prevent="save" class="space-y-8">
        {{-- 超豪華ヘッダー（既存のデザインを維持） --}}
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
                <input 
                    type="text"
                    wire:model.lazy="title"
                    class="w-full rounded-lg p-4 text-lg border-2 focus:border-violet-500 focus:ring-4 focus:ring-violet-500/20"
                    placeholder="まずはアプリ名を入力してください！"
                >
                @error('title') 
                    <span class="text-red-500">{{ $message }}</span>
                @enderror
            </div>
        </div>

        {{-- アプリの概要 --}}
        <div class="transform hover:scale-[1.02] transition-all duration-500">
            <div class="bg-white/50 backdrop-blur-lg rounded-xl p-6 shadow-xl hover:shadow-2xl border border-white/20">
                <label class="block text-xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-purple-600 to-blue-600">
                    アプリの概要
                </label>
                <textarea
                    wire:model.lazy="description"
                    rows="4"
                    class="w-full rounded-lg p-4 text-lg border-2 focus:border-violet-500 focus:ring-4 focus:ring-violet-500/20"
                    placeholder="アプリの概要を入力してください"
                ></textarea>
            </div>
        </div>
    </form>
</div> 