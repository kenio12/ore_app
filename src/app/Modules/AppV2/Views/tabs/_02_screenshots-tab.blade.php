@php
    use Illuminate\Support\Js;  // 追加：Jsクラスのインポート
    
    // データの初期化
    $formData = $initialData ?? [];
    $screenshots = collect($formData['screenshots'] ?? [])->map(function($screenshot) {
        return [
            'id' => $screenshot['id'] ?? null,
            'public_id' => $screenshot['public_id'] ?? null,
            'url' => $screenshot['url'] ?? null,
            'order' => $screenshot['order'] ?? 0
        ];
    })->toArray();
    
    $app = $app ?? null;
    $viewOnly = $viewOnly ?? false;

    // デバッグ表示
    dump([
        'processed_screenshots' => $screenshots  // 処理後のデータを確認
    ]);

    // Alpine.jsのデータを定義
    $alpineData = [
        'screenshots' => $screenshots ?? [],
        'getAppId' => $app->id,  // 直接IDを渡す
        'init' => 'function() { 
            this.screenshots = this.screenshots || [];
            console.log("Initialized screenshots:", this.screenshots); 
        }',
    ];
@endphp

{{-- モーダルコンポーネントをインクルード --}}
@include('AppV2::components.screenshot-modal')

<div class="space-y-8" 
    x-data="{
        screenshots: {{ Js::from($screenshots) }},
        getAppId() { 
            return {{ $app->id }}; 
        },
        async handleFiles(files) {
            const app_id = this.getAppId();
            
            // 3枚制限のチェック
            if (this.screenshots.length >= 3) {
                alert('スクリーンショットは最大3枚までです！');
                return;
            }

            // 追加可能な残り枚数を計算
            const remainingSlots = 3 - this.screenshots.length;
            const filesToUpload = Array.from(files).slice(0, remainingSlots);

            for (const file of filesToUpload) {
                if (!file.type.startsWith('image/')) {
                    alert('画像ファイルのみアップロード可能です');
                    continue;
                }
                
                const formData = new FormData();
                formData.append('screenshot', file);
                formData.append('app_id', app_id);
                
                try {
                    const response = await fetch('/apps-v2/screenshots/upload', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content
                        },
                        body: formData
                    });
                    
                    const result = await response.json();
                    
                    if (result.success) {
                        this.screenshots.push({
                            id: result.id,
                            public_id: result.public_id,
                            url: result.url,
                            order: this.screenshots.length
                        });
                        
                        this.$dispatch('screenshots-updated', this.screenshots);
                        this.$dispatch('auto-save');
                        
                        // 3枚目をアップロードした時のメッセージを変更
                        if (this.screenshots.length === 3) {
                            this.$dispatch('autosave-success', '3枚目の画像を追加しました。これで上限です！');
                        } else {
                            this.$dispatch('autosave-success', '画像を追加しました');
                        }
                    }
                } catch (error) {
                    console.error('Upload error:', error);
                    alert('アップロードに失敗しました');
                }
            }
        },
        async removeScreenshot(index) {
            if (!confirm('このスクリーンショットを削除してもええんか？')) {
                return;
            }

            const screenshot = this.screenshots[index];
            const app_id = this.getAppId();
            
            if (screenshot && screenshot.public_id) {
                try {
                    const response = await fetch('/apps-v2/screenshots/delete', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content
                        },
                        body: JSON.stringify({ 
                            public_id: screenshot.public_id,
                            screenshot_id: screenshot.id,
                            app_id: app_id
                        })
                    });
                    
                    const result = await response.json();
                    
                    if (result.success) {
                        // 指定されたインデックスの画像を削除
                        this.screenshots.splice(index, 1);
                        
                        // orderを振り直し
                        this.screenshots = this.screenshots.map((screenshot, idx) => ({
                            ...screenshot,
                            order: idx
                        }));

                        this.$dispatch('screenshots-updated', this.screenshots);
                        this.$dispatch('auto-save');
                        this.$dispatch('autosave-success', 'よっしゃ！スクショ削除したで！');
                    }
                } catch (error) {
                    console.error('Delete failed:', error);
                    this.$dispatch('autosave-error', 'あかん...スクショ消されへんかったわ...');
                }
            }
        }
    }"
    x-init="init"
>
    {{-- ヘッダー部分 --}}
    <div class="relative overflow-hidden rounded-2xl bg-gradient-to-r from-amber-600 via-orange-600 to-yellow-600 p-[2px]">
        <div class="relative bg-white/90 backdrop-blur-xl rounded-2xl p-8">
            <div class="absolute inset-0 bg-gradient-to-r from-amber-50/50 to-yellow-50/50 animate-pulse"></div>
            <div class="relative">
                <h3 class="text-3xl font-black bg-clip-text text-transparent bg-gradient-to-r from-amber-600 to-yellow-600">
                    スクリーンショット
                </h3>
                <p class="mt-3 text-lg text-gray-600">アプリの画面を最大3枚まで追加できます</p>
            </div>
        </div>
    </div>

    {{-- アップロードエリア --}}
    @if(!$viewOnly)
        <div class="mb-4">
            {{-- ドラッグ&ドロップエリア --}}
            <div
                class="border-2 border-dashed border-gray-300 rounded-lg p-8 text-center hover:border-blue-500 transition-colors duration-200"
                @dragover.prevent
                @drop.prevent="handleFiles($event.dataTransfer.files)"
            >
                <input 
                    type="file" 
                    id="screenshot-upload"
                    class="hidden" 
                    accept="image/*" 
                    multiple
                    @change="handleFiles($event.target.files)"
                >
                <div class="text-gray-600">
                    <p class="mb-2">ドラッグ&ドロップ</p>
                    <p>または</p>
                    <label 
                        for="screenshot-upload"
                        class="inline-block mt-2 px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 cursor-pointer"
                    >
                        ファイルを選択
                    </label>
                </div>
            </div>
        </div>
    @endif

    {{-- 画像グリッド --}}
    <div class="flex flex-col space-y-4">
        <template x-for="(screenshot, index) in screenshots" :key="index">
            <div class="relative group bg-white rounded-lg shadow-md p-4">
                @if(!$viewOnly)
                    <button 
                        @click="removeScreenshot(index)"
                        class="absolute top-2 right-2 bg-red-500 hover:bg-red-600 text-white p-2 rounded-full shadow-lg"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                @endif
                <div class="flex flex-col space-y-4">
                    <div class="flex justify-center">
                        <img 
                            :src="screenshot.url" 
                            class="max-h-[90vh] max-w-[90vw] md:max-h-[70vh] md:max-w-[80vw] object-contain rounded-lg cursor-pointer"
                            @click="$dispatch('open-app-screenshot-modal', { src: screenshot.url })"
                            style="max-height: 90vh; max-width: 90vw; @media (min-width: 768px) { max-height: 70vh; max-width: 80vw; }"
                        >
                    </div>
                    <div class="flex justify-center items-center space-x-2">
                        <button 
                            @click="if(index > 0) { 
                                [screenshots[index-1], screenshots[index]] = [screenshots[index], screenshots[index-1]];
                                $dispatch('screenshots-updated', screenshots);
                            }"
                            class="bg-gray-100 hover:bg-gray-200 p-2 rounded-full"
                            :disabled="index === 0"
                        >
                            ↑
                        </button>
                        <button 
                            @click="if(index < screenshots.length-1) { 
                                [screenshots[index], screenshots[index+1]] = [screenshots[index+1], screenshots[index]];
                                $dispatch('screenshots-updated', screenshots);
                            }"
                            class="bg-gray-100 hover:bg-gray-200 p-2 rounded-full"
                            :disabled="index === screenshots.length-1"
                        >
                            ↓
                        </button>
                    </div>
                </div>
            </div>
        </template>
    </div>
</div> 