<div class="bg-white p-8 rounded-lg shadow mb-8">
    <h2 class="text-2xl font-bold mb-6">パソコン環境</h2>
        <!-- PC詳細情報 -->
        <div class="mb-6">
            <label for="pc_details" class="block text-sm font-medium text-gray-700">
                PC詳細情報
            </label>
            @if(isset($isShow) && $isShow)
                <textarea 
                    readonly
                    rows="8"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"
                >{{ $hardware->pc_details ?? '' }}</textarea>
            @else
                <textarea 
                    name="pc_details" 
                    id="pc_details"
                    rows="8"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                    placeholder="例：
- メイン機
  - メーカー：Apple
  - 製品名：MacBook Pro 16インチ
  - 製造年：2023年モデル
  - カスタマイズ：CPU/メモリ増強モデル
- サブ機
  - メーカー：Lenovo
  - 製品名：ThinkPad X1 Carbon
  - モデル：Gen 10
  - カスタマイズ：なし"
                >{{ old('pc_details', $hardware->pc_details ?? '') }}</textarea>
                @error('pc_details')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            @endif
        </div>
    <!-- デバイスタイプ -->
    <div class="mb-6">
        <label class="block text-sm font-medium text-gray-700 mb-2">
            デバイスタイプ
        </label>
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 bg-gray-50 p-4 rounded-lg">
            @foreach(config('app-module.constants.hardware.device_types') as $value => $label)
                @if($viewOnly ?? false)
                    <div class="{{ in_array($value, $hardware->device_types ?? []) 
                        ? 'text-blue-600 font-medium bg-blue-50 py-1 px-2 rounded' 
                        : 'text-gray-400' }}">
                        {{ $label }}
                    </div>
                @else
                    <label class="flex items-center gap-2">
                        <input
                            type="checkbox"
                            name="device_types[]"
                            value="{{ $value }}"
                            {{ in_array($value, old('device_types', $hardware->device_types ?? [])) ? 'checked' : '' }}
                            class="rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                        >
                        <span class="text-gray-700">{{ $label }}</span>
                    </label>
                @endif
            @endforeach
        </div>
        @error('device_types')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>
    <!-- OS -->
    <div class="mb-6">
        <label for="os" class="block text-sm font-medium text-gray-700">
            OS
        </label>
        @if(isset($isShow) && $isShow)
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 bg-gray-50 p-4 rounded-lg">
                @foreach([
                    'windows' => 'Windows',
                    'macos' => 'macOS',
                    'linux' => 'Linux',
                    'other' => 'その他'
                ] as $value => $label)
                    <div class="{{ ($app->os_type ?? '') == $value 
                        ? 'text-blue-600 font-medium bg-blue-50 py-1 px-2 rounded' 
                        : 'text-gray-400' }}">
                        {{ $label }}
                    </div>
                @endforeach
            </div>
            @if($app->os_version)
                <div class="mt-2 text-gray-700">
                    バージョン: {{ $app->os_version }}
                </div>
            @endif
        @else
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 bg-gray-50 p-4 rounded-lg">
                @foreach([
                    'windows' => 'Windows',
                    'macos' => 'macOS',
                    'linux' => 'Linux',
                    'other' => 'その他'
                ] as $value => $label)
                    <label class="flex items-center gap-2">
                        <input
                            type="radio"
                            name="os_type"
                            value="{{ $value }}"
                            {{ old('os_type', $app->os_type ?? '') == $value ? 'checked' : '' }}
                            class="rounded-full border-gray-300 text-blue-600 focus:ring-blue-500"
                        >
                        <span class="text-gray-700">{{ $label }}</span>
                    </label>
                @endforeach
            </div>
            <!-- その他の場合のバージョン入力 -->
            <div class="mt-2">
                <input 
                    type="text" 
                    name="os_version" 
                    id="os_version"
                    value="{{ old('os_version', $app->os_version ?? '') }}"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                    placeholder="バージョンを入力（例：Windows 11、macOS Ventura 13.4）"
                >
            </div>
            @error('os_type')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
            @error('os_version')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        @endif
    </div>

    <!-- CPU種類 -->
    <div class="mb-6">
        <label class="block text-sm font-medium text-gray-700 mb-2">
            CPU種類
        </label>
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 bg-gray-50 p-4 rounded-lg">
            @foreach(config('app-module.constants.hardware.cpu_types') as $value => $label)
                @if($viewOnly ?? false)
                    <div class="{{ in_array($value, $hardware->cpu_types ?? []) 
                        ? 'text-blue-600 font-medium bg-blue-50 py-1 px-2 rounded' 
                        : 'text-gray-400' }}">
                        {{ $label }}
                    </div>
                @else
                    <label class="flex items-center gap-2">
                        <input
                            type="checkbox"
                            name="cpu_types[]"
                            value="{{ $value }}"
                            {{ in_array($value, old('cpu_types', $hardware->cpu_types ?? [])) ? 'checked' : '' }}
                            class="rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                        >
                        <span class="text-gray-700">{{ $label }}</span>
                    </label>
                @endif
            @endforeach
        </div>
        @error('cpu_types')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <!-- メモリサイズ -->
    <div class="mb-6">
        <label class="block text-sm font-medium text-gray-700 mb-2">
            メモリサイズ
        </label>
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 bg-gray-50 p-4 rounded-lg">
            @foreach(config('app-module.constants.hardware.memory_sizes') as $value => $label)
                @if($viewOnly ?? false)
                    <div class="{{ in_array($value, $hardware->memory_sizes ?? []) 
                        ? 'text-blue-600 font-medium bg-blue-50 py-1 px-2 rounded' 
                        : 'text-gray-400' }}">
                        {{ $label }}
                    </div>
                @else
                    <label class="flex items-center gap-2">
                        <input
                            type="checkbox"
                            name="memory_sizes[]"
                            value="{{ $value }}"
                            {{ in_array($value, old('memory_sizes', $hardware->memory_sizes ?? [])) ? 'checked' : '' }}
                            class="rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                        >
                        <span class="text-gray-700">{{ $label }}</span>
                    </label>
                @endif
            @endforeach
        </div>
        @error('memory_sizes')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <!-- ストレージ種類 -->
    <div class="mb-6">
        <label class="block text-sm font-medium text-gray-700 mb-2">
            ストレージ種類
        </label>
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 bg-gray-50 p-4 rounded-lg">
            @foreach(config('app-module.constants.hardware.storage_types') as $value => $label)
                @if($viewOnly ?? false)
                    <div class="{{ in_array($value, $hardware->storage_types ?? []) 
                        ? 'text-blue-600 font-medium bg-blue-50 py-1 px-2 rounded' 
                        : 'text-gray-400' }}">
                        {{ $label }}
                    </div>
                @else
                    <label class="flex items-center gap-2">
                        <input
                            type="checkbox"
                            name="storage_types[]"
                            value="{{ $value }}"
                            {{ in_array($value, old('storage_types', $hardware->storage_types ?? [])) ? 'checked' : '' }}
                            class="rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                        >
                        <span class="text-gray-700">{{ $label }}</span>
                    </label>
                @endif
            @endforeach
        </div>
        @error('storage_types')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <!-- その他のハードウェア情報 -->
    <div class="mb-6">
        <label class="block text-sm font-medium text-gray-700">
            その他のパソコン自体の情報
        </label>
        <textarea readonly rows="8" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ $hardware->other_hardware ?? '' }}</textarea>
    </div>

</div> 