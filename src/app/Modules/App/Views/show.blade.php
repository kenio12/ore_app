@foreach($app->screenshots as $screenshot)
    <div class="relative group">
        <!-- サムネイル表示 -->
        <img src="{{ $screenshot['thumbnail'] }}" 
             alt="アプリのスクリーンショット" 
             class="w-full h-48 object-cover rounded-lg cursor-pointer"
             onclick="showFullImage('{{ $screenshot['original'] }}')"
        >
    </div>
@endforeach

<!-- モーダル用のJavaScript -->
<script>
function showFullImage(url) {
    // モーダルで大きい画像を表示
    const modal = document.createElement('div');
    modal.className = 'fixed inset-0 bg-black bg-opacity-75 flex items-center justify-center z-50';
    modal.innerHTML = `
        <div class="relative">
            <img src="${url}" class="max-w-[90vw] max-h-[90vh] object-contain">
            <button onclick="this.closest('.fixed').remove()" 
                    class="absolute top-4 right-4 text-white text-xl">&times;</button>
        </div>
    `;
    document.body.appendChild(modal);
}
</script> 