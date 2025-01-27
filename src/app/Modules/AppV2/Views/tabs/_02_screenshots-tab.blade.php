@php
    use Illuminate\Support\Js;  
    use Illuminate\Support\Facades\Log;  // 追加：Logクラスのインポート
    
    // データの初期化を確実に
    $formData = $initialData ?? [];
    $screenshots = collect($formData['screenshots'] ?? [])->map(function($screenshot) {
        // nullチェックを追加
        if (!$screenshot) return null;
        
        return [
            'id' => $screenshot['id'] ?? null,
            'public_id' => $screenshot['public_id'] ?? null,
            'url' => $screenshot['url'] ?? null,
            'order' => $screenshot['order'] ?? 0
        ];
    })->filter()->values()->toArray();  // nullを除去して配列を再インデックス
    
    // デバッグ表示
    Log::debug('Screenshots初期化:', [
        'raw_data' => $formData['screenshots'] ?? [],
        'processed' => $screenshots
    ]);

    $app = $app ?? null;
    $viewOnly = $viewOnly ?? false;

    // デバッグ表示
    // dump([
    //     'processed_screenshots' => $screenshots  // 処理後のデータを確認
    // ]);

    // Alpine.jsのデータを定義
    $alpineData = [
        'screenshots' => $screenshots ?? [],
        'getAppId' => $app->id,  // 直接IDを渡す
        'init' => 'function() { 
            this.screenshots = this.screenshots || [];
        }',
    ];
@endphp

{{-- モーダルコンポーネントをインクルード --}}
@include('AppV2::components.screenshot-modal')

<div class="space-y-8" 
    x-data="{
        screenshots: {{ Js::from($screenshots) }},
        init() {
            console.log('Initial screenshots:', this.screenshots);
        },
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
                        // 新しい画像を配列に追加
                        this.screenshots.push({
                            id: result.id,
                            public_id: result.public_id,
                            url: result.url,
                            order: this.screenshots.length
                        });

                        // イベントをディスパッチ（形式を修正）
                        this.$dispatch('screenshots-updated', this.screenshots);  // オブジェクトではなく配列を直接渡す
                        
                        // 自動保存をトリガー
                        this.$dispatch('auto-save');
                        
                        // メッセージ表示
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
        async updateOrder(index, direction) {
            if (direction === 'up' && index > 0) {
                // 上に移動
                const temp = this.screenshots[index].order;
                this.screenshots[index].order = this.screenshots[index - 1].order;
                this.screenshots[index - 1].order = temp;
                
                // 配列内の要素を入れ替え
                [this.screenshots[index], this.screenshots[index - 1]] = 
                [this.screenshots[index - 1], this.screenshots[index]];
            } else if (direction === 'down' && index < this.screenshots.length - 1) {
                // 下に移動
                const temp = this.screenshots[index].order;
                this.screenshots[index].order = this.screenshots[index + 1].order;
                this.screenshots[index + 1].order = temp;
                
                // 配列内の要素を入れ替え
                [this.screenshots[index], this.screenshots[index + 1]] = 
                [this.screenshots[index + 1], this.screenshots[index]];
            }

            // orderでソート
            this.screenshots.sort((a, b) => a.order - b.order);
            
            // 変更を保存
            this.$dispatch('screenshots-updated', this.screenshots);
            this.$dispatch('auto-save');
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
                        // 削除された要素より後ろのorderを詰める
                        const deletedOrder = this.screenshots[index].order;
                        this.screenshots = this.screenshots
                            .filter((_, i) => i !== index)
                            .map(screenshot => ({
                                ...screenshot,
                                order: screenshot.order > deletedOrder 
                                    ? screenshot.order - 1 
                                    : screenshot.order
                            }));
                        
                        // orderでソート
                        this.screenshots.sort((a, b) => a.order - b.order);
                        
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
    <div class="flex flex-col space-y-8">
        <template x-for="(screenshot, index) in screenshots" :key="index">
            <div class="relative group bg-white rounded-lg shadow-md p-4"
                 :class="{ 'bg-gradient-to-br from-amber-50 to-yellow-50': index === 0 }">
                
                {{-- サムネイル表示バッジ（index=0の時のみ表示） --}}
                <div x-show="index === 0" 
                     class="mb-6"> 
                    <div class="bg-gradient-to-r from-amber-500 to-yellow-500 rounded-lg p-3">
                        <div style="background-color: #fef9c3 !important;" class="rounded-lg px-4 py-2 shadow-md">
                            <div class="flex items-center justify-center space-x-2">
                                <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                <span class="text-lg font-bold !bg-yellow-200 !text-amber-800 px-3 py-1.5 rounded-md">
                                    このスクショがカードのサムネイルになります
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                @if(!$viewOnly)
                    <button 
                        @click="removeScreenshot(index)"
                        class="absolute top-2 right-2 bg-red-500 hover:bg-red-600 text-white p-2 rounded-full shadow-lg z-20"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                @endif

                {{-- 画像コンテナ --}}
                <div class="flex flex-col space-y-4">
                    <div class="flex justify-center">
                        <img 
                            :src="screenshot.url" 
                            class="object-contain rounded-lg cursor-pointer"
                            style="max-height: 70vh; max-width: 80vw;"
                            :style="window.innerWidth < 768 ? 'max-height: 90vh; max-width: 90vw;' : 'max-height: 70vh; max-width: 80vw;'"
                            :class="{ 'ring-4 ring-amber-400 ring-offset-4 shadow-xl': index === 0 }"
                            x-init="
                                window.addEventListener('resize', () => {
                                    if (window.innerWidth < 768) {
                                        $el.style.maxHeight = '90vh';
                                        $el.style.maxWidth = '90vw';
                                    } else {
                                        $el.style.maxHeight = '70vh';
                                        $el.style.maxWidth = '80vw';
                                    }
                                })
                            "
                            @click="$dispatch('open-app-screenshot-modal', { src: screenshot.url })"
                        >
                    </div>
                    <div class="flex justify-center items-center space-x-2">
                        <button 
                            @click="updateOrder(index, 'up')"
                            class="bg-gray-100 hover:bg-gray-200 p-2 rounded-full"
                            :disabled="index === 0"
                        >
                            ↑
                        </button>
                        <button 
                            @click="updateOrder(index, 'down')"
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