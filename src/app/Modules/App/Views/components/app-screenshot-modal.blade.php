@props([
    'name',
    'show' => false,
])

<div
    x-data="{
        show: @js($show),
        aspectRatio: 0,
        calculateSize() {
            const img = this.$refs.appScreenshotImage;
            this.aspectRatio = img.naturalWidth / img.naturalHeight;
            
            if (this.aspectRatio < 1) {  // 縦長画像（スマホスクショ）
                const viewportHeight = window.innerHeight;
                img.style.height = `${Math.floor(viewportHeight * 0.8)}px`;
                img.style.width = 'auto';
            } else {  // 横長画像（パソコンスクショ）
                const viewportWidth = window.innerWidth;
                img.style.width = `${Math.floor(viewportWidth * 0.9)}px`;
                img.style.height = 'auto';
            }
        }
    }"
    x-on:open-app-screenshot-modal.window="
        show = true;
        $refs.appScreenshotImage.src = $event.detail.src;
        $nextTick(() => calculateSize());
    "
    x-on:close.stop="show = false"
    x-on:keydown.escape.window="show = false"
    x-show="show"
    class="fixed inset-0 overflow-y-auto px-4 py-6 sm:px-0 z-50"
    style="display: none;"
>
    <div
        x-show="show"
        class="fixed inset-0 transform transition-all"
        x-on:click="show = false"
        x-transition:enter="ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
    >
        <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
    </div>

    <div
        x-show="show"
        class="flex items-center justify-center min-h-screen"
    >
        <img
            x-ref="appScreenshotImage"
            class="object-contain"
            style="max-width: 90vw; max-height: 90vh;"
            alt="アプリのスクリーンショット"
        />
    </div>
</div> 