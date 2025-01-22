<div class="bg-white p-8 rounded-lg shadow mb-8">
    <h2 class="text-2xl font-bold mb-6">ハードウェア環境</h2>

    <!-- デバイスタイプ -->
    <div class="mb-6">
        <label class="block text-sm font-medium text-gray-700 mb-2">
            デバイスタイプ
        </label>
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 bg-gray-50 p-4 rounded-lg">
            @foreach(config('app-module.constants.hardware.device_types') as $value => $label)
                <div class="{{ in_array($value, $hardware->device_types ?? []) 
                    ? 'text-blue-600 font-medium bg-blue-50 py-1 px-2 rounded' 
                    : 'text-gray-400' }}">
                    {{ $label }}
                </div>
            @endforeach
        </div>
    </div>

    <!-- CPU詳細情報 -->
    <div class="mb-6">
        <label class="block text-sm font-medium text-gray-700">
            CPU詳細情報
        </label>
        <textarea readonly rows="8" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ $hardware->cpu ?? '' }}</textarea>
    </div>

    <!-- メモリ詳細情報 -->
    <div class="mb-6">
        <label class="block text-sm font-medium text-gray-700">
            メモリ詳細情報
        </label>
        <textarea readonly rows="8" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ $hardware->memory ?? '' }}</textarea>
    </div>

    <!-- ストレージ詳細情報 -->
    <div class="mb-6">
        <label class="block text-sm font-medium text-gray-700">
            ストレージ詳細情報
        </label>
        <textarea readonly rows="8" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ $hardware->storage ?? '' }}</textarea>
    </div>

    <!-- その他のハードウェア情報 -->
    <div class="mb-6">
        <label class="block text-sm font-medium text-gray-700">
            その他のハードウェア情報
        </label>
        <textarea readonly rows="8" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ $hardware->other_hardware ?? '' }}</textarea>
    </div>
</div> 