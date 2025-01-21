<div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 space-y-6">
    <h2 class="text-2xl font-bold text-gray-900">ハードウェア環境</h2>

    {{-- デバイスタイプ --}}
    @if($app->hardware && $app->hardware->device_types)
        <div class="space-y-2">
            <h3 class="text-lg font-semibold">対応デバイス</h3>
            <div class="flex flex-wrap gap-2">
                @foreach(json_decode($app->hardware->device_types) as $device)
                    <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm">
                        {{ config('constants.hardware.device_types')[$device] ?? $device }}
                    </span>
                @endforeach
                @if($app->hardware->other_device)
                    <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm">
                        {{ $app->hardware->other_device }}
                    </span>
                @endif
            </div>
        </div>
    @endif

    {{-- CPU情報 --}}
    @if($app->hardware && $app->hardware->cpu_type)
        <div class="space-y-2">
            <h3 class="text-lg font-semibold">CPU</h3>
            <div class="flex flex-wrap gap-2">
                <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-sm">
                    {{ config('constants.hardware.cpu_types')[$app->hardware->cpu_type] ?? $app->hardware->cpu_type }}
                </span>
            </div>
            @if($app->hardware->cpu)
                <p class="text-gray-600">{{ $app->hardware->cpu }}</p>
            @endif
        </div>
    @endif

    {{-- メモリ情報 --}}
    @if($app->hardware && $app->hardware->memory_size)
        <div class="space-y-2">
            <h3 class="text-lg font-semibold">メモリ</h3>
            <div class="flex flex-wrap gap-2">
                <span class="px-3 py-1 bg-purple-100 text-purple-800 rounded-full text-sm">
                    {{ config('constants.hardware.memory_sizes')[$app->hardware->memory_size] ?? $app->hardware->memory_size }}
                </span>
            </div>
            @if($app->hardware->memory)
                <p class="text-gray-600">{{ $app->hardware->memory }}</p>
            @endif
        </div>
    @endif

    {{-- ストレージ情報 --}}
    @if($app->hardware && $app->hardware->storage_types)
        <div class="space-y-2">
            <h3 class="text-lg font-semibold">ストレージ</h3>
            <div class="flex flex-wrap gap-2">
                @foreach(json_decode($app->hardware->storage_types) as $storage)
                    <span class="px-3 py-1 bg-yellow-100 text-yellow-800 rounded-full text-sm">
                        {{ config('constants.hardware.storage_types')[$storage] ?? $storage }}
                    </span>
                @endforeach
            </div>
            @if($app->hardware->storage)
                <p class="text-gray-600">{{ $app->hardware->storage }}</p>
            @endif
        </div>
    @endif

    {{-- その他のハードウェア情報 --}}
    @if($app->hardware && $app->hardware->other_hardware)
        <div class="space-y-2">
            <h3 class="text-lg font-semibold">その他のハードウェア情報</h3>
            <p class="text-gray-600">{{ $app->hardware->other_hardware }}</p>
        </div>
    @endif
</div> 