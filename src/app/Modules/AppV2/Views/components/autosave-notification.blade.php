<div x-data="{ show: false, message: '' }"
     x-show="show"
     x-transition:enter="transition ease-out duration-300 transform"
     x-transition:enter-start="opacity-0 scale-95 translate-y-2"
     x-transition:enter-end="opacity-100 scale-100 translate-y-0"
     x-transition:leave="transition ease-in duration-200 transform"
     x-transition:leave-start="opacity-100 scale-100 translate-y-0"
     x-transition:leave-end="opacity-0 scale-95 translate-y-2"
     @autosave-success.window="
        show = true;
        message = $event.detail;
        setTimeout(() => show = false, 3000)
     "
     class="fixed bottom-4 right-4 bg-gradient-to-r from-green-400 to-blue-500 text-white px-6 py-3 rounded-lg shadow-2xl z-50 backdrop-blur-sm border border-white/10">
    <div class="flex items-center space-x-2">
        <svg class="w-6 h-6 animate-spin-slow" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
        </svg>
        <span x-text="message" class="font-medium text-white/90"></span>
    </div>
</div> 