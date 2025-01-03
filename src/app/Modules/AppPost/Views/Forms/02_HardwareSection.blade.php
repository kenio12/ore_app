<div class="bg-white p-8 rounded-lg shadow mb-8">
    <h2 class="text-2xl font-bold mb-6">ハードウェア環境</h2>

    <!-- CPU -->
    <div class="mb-6">
        <label for="cpu" class="block text-sm font-medium text-gray-700">
            CPU
        </label>
        <input 
            type="text" 
            name="cpu" 
            id="cpu"
            value="{{ old('cpu', $post->cpu ?? '') }}"
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
            placeholder="例：Apple M1 Max"
        >
        @error('cpu')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <!-- メモリ -->
    <div class="mb-6">
        <label for="memory" class="block text-sm font-medium text-gray-700">
            メモリ
        </label>
        <input 
            type="text" 
            name="memory" 
            id="memory"
            value="{{ old('memory', $post->memory ?? '') }}"
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
            placeholder="例：32GB"
        >
        @error('memory')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <!-- ストレージ -->
    <div class="mb-6">
        <label for="storage" class="block text-sm font-medium text-gray-700">
            ストレージ
        </label>
        <input 
            type="text" 
            name="storage" 
            id="storage"
            value="{{ old('storage', $post->storage ?? '') }}"
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
            placeholder="例：1TB SSD"
        >
        @error('storage')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <!-- その他のハードウェア -->
    <div class="mb-6">
        <label for="other_hardware" class="block text-sm font-medium text-gray-700">
            その他のハードウェア
        </label>
        <textarea 
            name="other_hardware" 
            id="other_hardware"
            rows="4"
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
            placeholder="例：外付けディスプレイ、キーボード、マウスなど"
        >{{ old('other_hardware', $post->other_hardware ?? '') }}</textarea>
        @error('other_hardware')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>
</div> 