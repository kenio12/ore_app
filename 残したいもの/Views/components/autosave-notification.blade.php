<div 
    x-data="{ 
        show: false, 
        message: '',
        lastMessage: '',
        lastShown: 0,
        showNotification(msg) {
            const now = Date.now();
            
            // 入力欄にフォーカスがある場合は表示しない
            if (document.activeElement && 
                (document.activeElement.tagName.toLowerCase() === 'input' || 
                 document.activeElement.tagName.toLowerCase() === 'textarea')) {
                return;
            }

            // 同じメッセージは5秒以内は表示しない
            if (this.lastMessage === msg && now - this.lastShown < 5000) {
                return;
            }
            
            this.show = true;
            this.message = msg;
            this.lastMessage = msg;
            this.lastShown = now;
            
            setTimeout(() => this.show = false, 2000);
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