<div class="bg-white p-8 rounded-lg shadow mb-8">
    <h2 class="text-2xl font-bold mb-6">ハードウェア環境</h2>

    <!-- デバイス種類 -->
    <div class="mb-6">
        <label class="block text-sm font-medium text-gray-700 mb-2">
            デバイス種類
        </label>
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 bg-gray-50 p-4 rounded-lg">
            @foreach([
                'macbook' => 'MacBook',
                'imac' => 'iMac',
                'mac_mini' => 'Mac mini',
                'mac_pro' => 'Mac Pro',
                'windows_laptop' => 'Windowsノート',
                'windows_desktop' => 'Windowsデスクトップ',
                'linux_laptop' => 'Linuxノート',
                'linux_desktop' => 'Linuxデスクトップ',
                'other' => 'その他'
            ] as $value => $label)
                <label class="flex items-center gap-2">
                    <input
                        type="checkbox"
                        name="device_types[]"
                        value="{{ $value }}"
                        {{ in_array($value, old('device_types', $app->device_types ?? [])) ? 'checked' : '' }}
                        class="rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                    >
                    <span class="text-gray-700">{{ $label }}</span>
                </label>
            @endforeach
        </div>
        <!-- その他の場合の入力欄 -->
        <div class="mt-2">
            <input 
                type="text" 
                name="other_device" 
                id="other_device"
                value="{{ old('other_device', $app->other_device ?? '') }}"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                placeholder="その他のデバイスを入力"
            >
        </div>
        @error('device_types')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <!-- CPU -->
    <div class="mb-6">
        <label class="block text-sm font-medium text-gray-700 mb-2">
            CPU
        </label>
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 bg-gray-50 p-4 rounded-lg">
            @foreach([
                'apple_silicon' => 'Apple Silicon',
                'intel' => 'Intel',
                'amd' => 'AMD',
                'other' => 'その他'
            ] as $value => $label)
                <label class="flex items-center gap-2">
                    <input
                        type="radio"
                        name="cpu_type"
                        value="{{ $value }}"
                        {{ old('cpu_type', $app->cpu_type ?? '') == $value ? 'checked' : '' }}
                        class="rounded-full border-gray-300 text-blue-600 focus:ring-blue-500"
                    >
                    <span class="text-gray-700">{{ $label }}</span>
                </label>
            @endforeach
        </div>
        <!-- CPU詳細情報 -->
        <div class="mt-2">
            <input 
                type="text" 
                name="cpu" 
                id="cpu"
                value="{{ old('cpu', $app->cpu ?? '') }}"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                placeholder="例：Apple M1 Max, Intel Core i9-12900K, AMD Ryzen 9 5950X"
            >
        </div>
        @error('cpu')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <!-- メモリ -->
    <div class="mb-6">
        <label class="block text-sm font-medium text-gray-700 mb-2">
            メモリ
        </label>
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 bg-gray-50 p-4 rounded-lg">
            @foreach([
                '8gb' => '8GB',
                '16gb' => '16GB',
                '32gb' => '32GB',
                '64gb' => '64GB',
                '128gb' => '128GB以上',
                'other' => 'その他'
            ] as $value => $label)
                <label class="flex items-center gap-2">
                    <input
                        type="radio"
                        name="memory_size"
                        value="{{ $value }}"
                        {{ old('memory_size', $app->memory_size ?? '') == $value ? 'checked' : '' }}
                        class="rounded-full border-gray-300 text-blue-600 focus:ring-blue-500"
                    >
                    <span class="text-gray-700">{{ $label }}</span>
                </label>
            @endforeach
        </div>
        <!-- メモリ詳細情報 -->
        <div class="mt-2">
            <input 
                type="text" 
                name="memory" 
                id="memory"
                value="{{ old('memory', $app->memory ?? '') }}"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                placeholder="例：32GB DDR4-3200, 64GB DDR5-4800"
            >
        </div>
        @error('memory')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <!-- ストレージ -->
    <div class="mb-6">
        <label class="block text-sm font-medium text-gray-700 mb-2">
            ストレージ
        </label>
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 bg-gray-50 p-4 rounded-lg">
            @foreach([
                'ssd' => 'SSD',
                'hdd' => 'HDD',
                'nvme' => 'NVMe',
                'other' => 'その他'
            ] as $value => $label)
                <label class="flex items-center gap-2">
                    <input
                        type="checkbox"
                        name="storage_types[]"
                        value="{{ $value }}"
                        {{ in_array($value, old('storage_types', $app->storage_types ?? [])) ? 'checked' : '' }}
                        class="rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                    >
                    <span class="text-gray-700">{{ $label }}</span>
                </label>
            @endforeach
        </div>
        <!-- ストレージ詳細情報 -->
        <div class="mt-2">
            <input 
                type="text" 
                name="storage" 
                id="storage"
                value="{{ old('storage', $app->storage ?? '') }}"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                placeholder="例：1TB NVMe SSD + 4TB HDD"
            >
        </div>
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
            rows="8"
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
            placeholder="例：
- ディスプレイ：Dell U2720Q 4K 27インチ
- キーボード：HHKB Professional HYBRID Type-S
- マウス：Logicool MX Master 3
- その他の周辺機器"
        >{{ old('other_hardware', $app->other_hardware ?? '') }}</textarea>
        @error('other_hardware')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>
</div> 