<div class="bg-white p-8 rounded-lg shadow mb-8">
    <h2 class="text-2xl font-bold mb-6">データベース・ストレージ</h2>

    <!-- データベース -->
    <div class="mb-6">
        <label for="database" class="block text-sm font-medium text-gray-700">
            データベース <span class="text-red-500">*</span>
        </label>
        <input 
            type="text" 
            name="database" 
            id="database"
            value="{{ old('database', $post->database ?? '') }}"
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
            placeholder="例：MySQL 8.0"
            required
        >
        @error('database')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <!-- キャッシュ -->
    <div class="mb-6">
        <label for="cache" class="block text-sm font-medium text-gray-700">
            キャッシュ
        </label>
        <input 
            type="text" 
            name="cache" 
            id="cache"
            value="{{ old('cache', $post->cache ?? '') }}"
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
            placeholder="例：Redis"
        >
        @error('cache')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <!-- ストレージ -->
    <div class="mb-6">
        <label for="storage_service" class="block text-sm font-medium text-gray-700">
            ストレージサービス
        </label>
        <input 
            type="text" 
            name="storage_service" 
            id="storage_service"
            value="{{ old('storage_service', $post->storage_service ?? '') }}"
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
            placeholder="例：Cloudinary, AWS S3"
        >
        @error('storage_service')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <!-- その他のデータ関連サービス -->
    <div class="mb-6">
        <label for="other_data_services" class="block text-sm font-medium text-gray-700">
            その他のデータ関連サービス
        </label>
        <textarea 
            name="other_data_services" 
            id="other_data_services"
            rows="4"
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
            placeholder="例：
- Elasticsearch (全文検索)
- Amazon SQS (キュー)
- Firebase Realtime Database (リアルタイム通信)"
        >{{ old('other_data_services', $post->other_data_services ?? '') }}</textarea>
        @error('other_data_services')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>
</div> 