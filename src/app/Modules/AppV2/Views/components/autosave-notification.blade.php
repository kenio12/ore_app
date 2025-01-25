<div 
    x-data="{ 
        show: false, 
        message: '',
        showNotification(msg) {
            this.show = true;
            this.message = msg;
            setTimeout(() => this.show = false, 3000);
        }
    }"
    @autosave-success.window="showNotification($event.detail)"
    @autosave-error.window="showNotification($event.detail)"
    x-show="show"
    x-transition
    class="fixed bottom-4 right-4 z-50"
    style="display: none;"
>
    <div 
        :class="message.includes('失敗') ? 'bg-red-500' : 'bg-green-500'"
        class="text-white px-6 py-3 rounded-lg shadow-lg"
    >
        <span x-text="message"></span>
    </div>
</div> 