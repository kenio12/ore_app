@extends('App::Forms._form', [
    'currentSection' => 'hardware',
    'sections' => [
        '01_basic' => ['title' => '基本情報', 'required' => true],
        '02_development' => ['title' => '開発期間', 'required' => true],
        '03_hardware' => ['title' => 'ハードウェア', 'required' => false],
        '04_basic_dev' => ['title' => '基本開発環境', 'required' => true],
        '05_dev_tools' => ['title' => '開発ツール', 'required' => false],
        '06_architecture' => ['title' => 'アーキテクチャ', 'required' => false],
        '07_security' => ['title' => 'セキュリティ', 'required' => false],
        '08_backend' => ['title' => 'バックエンド', 'required' => false],
        '09_frontend' => ['title' => 'フロントエンド', 'required' => false],
        '10_database' => ['title' => 'データベース', 'required' => false]
    ],
    'sectionTitle' => 'ハードウェア環境',
    'previousSection' => 'development',
    'nextSection' => 'basic-dev',
    'routeName' => 'hardware'
])

@php
    // デバイスタイプ定義
    $deviceTypes = [
        'smartphone' => 'スマートフォン',
        'tablet' => 'タブレット',
        'laptop' => 'ノートPC',
        'desktop' => 'デスクトップPC',
        'wearable' => 'ウェアラブル',
        'iot' => 'IoTデバイス',
        'game_console' => 'ゲーム機',
        'other' => 'その他'
    ];
@endphp

@section('content')
<div class="bg-white p-6 rounded-lg shadow-sm">
    <h2 class="text-xl font-bold mb-4">ハードウェア環境</h2>
    
    {{-- デバイスタイプ --}}
    <div class="mb-4">
        <label class="block font-medium mb-2">対応デバイス</label>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            @foreach($deviceTypes as $value => $label)
                <label class="flex items-center space-x-2">
                    <input type="checkbox" 
                           name="device_types[]" 
                           value="{{ $value }}"
                           @if($app->hardware && in_array($value, json_decode($app->hardware->device_types ?? '[]', true) ?? [])) checked @endif
                           class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                    <span>{{ $label }}</span>
                </label>
            @endforeach
        </div>
    </div>

    {{-- CPU情報 --}}
    <div class="space-y-2">
        <h3 class="text-lg font-semibold">CPU</h3>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            @php
                $cpuTypes = [
                    'arm' => 'ARM',
                    'x86' => 'x86',
                    'x64' => 'x64',
                    'other' => 'その他'
                ];
            @endphp
            @foreach($cpuTypes as $value => $label)
                <label class="flex items-center space-x-2">
                    <input type="radio" 
                           name="cpu_type" 
                           value="{{ $value }}"
                           @if($app->hardware && $app->hardware->cpu_type === $value) checked @endif
                           class="border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                    <span>{{ $label }}</span>
                </label>
            @endforeach
        </div>
        <div class="mt-2">
            <label class="block text-sm font-medium text-gray-700">CPU詳細情報</label>
            <textarea name="cpu" 
                      rows="3"
                      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ $app->hardware->cpu ?? '' }}</textarea>
        </div>
    </div>

    {{-- メモリ情報 --}}
    <div class="space-y-2">
        <h3 class="text-lg font-semibold">メモリ</h3>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            @php
                $memorySizes = [
                    '8gb' => '8GB',
                    '16gb' => '16GB',
                    '32gb' => '32GB',
                    '64gb' => '64GB',
                    '128gb' => '128GB以上',
                    'other' => 'その他'
                ];
            @endphp
            @foreach($memorySizes as $value => $label)
                <label class="flex items-center space-x-2">
                    <input type="radio" 
                           name="memory_size" 
                           value="{{ $value }}"
                           @if($app->hardware && $app->hardware->memory_size === $value) checked @endif
                           class="border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                    <span>{{ $label }}</span>
                </label>
            @endforeach
        </div>
        <div class="mt-2">
            <label class="block text-sm font-medium text-gray-700">メモリ詳細情報</label>
            <textarea name="memory" 
                      rows="3"
                      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ $app->hardware->memory ?? '' }}</textarea>
        </div>
    </div>

    {{-- ストレージ情報 --}}
    <div class="space-y-2">
        <h3 class="text-lg font-semibold">ストレージ</h3>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            @php
                $storageTypes = [
                    'ssd' => 'SSD',
                    'hdd' => 'HDD',
                    'nvme' => 'NVMe',
                    'other' => 'その他'
                ];
            @endphp
            @foreach($storageTypes as $value => $label)
                <label class="flex items-center space-x-2">
                    <input type="checkbox" 
                           name="storage_types[]" 
                           value="{{ $value }}"
                           @if($app->hardware && in_array($value, json_decode($app->hardware->storage_types ?? '[]', true) ?? [])) checked @endif
                           class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                    <span>{{ $label }}</span>
                </label>
            @endforeach
        </div>
        <div class="mt-2">
            <label class="block text-sm font-medium text-gray-700">ストレージ詳細情報</label>
            <textarea name="storage" 
                      rows="3"
                      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ $app->hardware->storage ?? '' }}</textarea>
        </div>
    </div>

    {{-- その他のハードウェア情報 --}}
    <div class="space-y-2">
        <h3 class="text-lg font-semibold">その他のハードウェア情報</h3>
        <textarea name="other_hardware" 
                  rows="3"
                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                  placeholder="その他のハードウェア要件や特記事項があれば入力してください">{{ $app->hardware->other_hardware ?? '' }}</textarea>
    </div>
</div>
@endsection 