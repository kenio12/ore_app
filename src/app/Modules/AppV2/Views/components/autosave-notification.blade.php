<div x-data="{ show: false, message: '' }"
     @autosave-success.window="show = true; message = $event.detail; setTimeout(() => show = false, 3000)"
     x-show="show"
     x-transition
     class="fixed bottom-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50">
    <span x-text="message"></span>
</div> 