<div x-data="{ 
        show: false,
        imageSrc: '',
        init() {
            window.addEventListener('open-app-screenshot-modal', (e) => {
                this.imageSrc = e.detail.src;
                this.show = true;
            });
        }
    }"
    x-show="show"
    x-cloak
    class="fixed inset-0 z-50 overflow-hidden"
    style="background-color: rgba(0, 0, 0, 0.75);"
    @click.self="show = false"
    @keydown.escape.window="show = false">
    
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="relative transform transition-all duration-300"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-95">
            
            <button @click="show = false"
                    class="absolute -top-4 -right-4 bg-red-500 hover:bg-red-600 text-white rounded-full w-8 h-8 flex items-center justify-center shadow-lg transition-all duration-200 z-50 transform hover:scale-110">
                ×
            </button>
            
            <img :src="imageSrc"
                 class="max-w-[80vw] max-h-[80vh] object-contain rounded-lg shadow-2xl border-2 border-white/10"
                 alt="拡大画像">
        </div>
    </div>
</div> 