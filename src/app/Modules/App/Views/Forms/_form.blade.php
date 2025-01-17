@php
    // セッションから現在のセクションのデータを取得
    $sectionData = session("app_form.{$currentSection}", []);
@endphp

{{-- プログレスバー --}}
<div class="mb-8">
    <div class="flex justify-between mb-2">
        @foreach($sections as $key => $section)
            <div class="flex flex-col items-center">
                <div class="w-8 h-8 rounded-full flex items-center justify-center 
                    {{ $key === $currentSection ? 'bg-blue-500 text-white' : 
                       (array_search($key, array_keys($sections)) < array_search($currentSection, array_keys($sections)) ? 'bg-green-500 text-white' : 'bg-gray-200') }}">
                    {{ $loop->iteration }}
                    @if($section['required'])
                        <span class="text-red-500 text-xs">*</span>
                    @endif
                </div>
                <span class="text-xs mt-1 {{ $key === $currentSection ? 'text-blue-500 font-bold' : 'text-gray-500' }}">
                    {{ $section['title'] }}
                </span>
            </div>
            @if(!$loop->last)
                <div class="flex-1 h-1 mt-4 mx-2 
                    {{ array_search($key, array_keys($sections)) < array_search($currentSection, array_keys($sections)) ? 'bg-green-500' : 'bg-gray-200' }}">
                </div>
            @endif
        @endforeach
    </div>
</div>

{{-- メインのフォーム --}}
<form method="POST" 
    action="{{ $app->exists ? route('app.sections.' . $currentSection . '.update', $app) : route('apps.create') }}" 
    enctype="multipart/form-data">
    @csrf
    @if($app->exists)
        @method('PUT')
    @endif
    
    {{-- 現在のセクションの説明 --}}
    <div class="bg-blue-50 p-4 rounded-lg mb-6">
        <h3 class="text-lg font-semibold text-blue-800 mb-2">
            {{ $sectionTitle }}の入力
        </h3>
        <p class="text-blue-600">
            @switch($currentSection)
                @case('basic-info')
                    アプリの基本的な情報を入力してください。この情報は一覧ページやカード表示で使用されます。
                    @break
                @case('development-story')
                    アプリ開発の経緯や苦労した点、工夫した点などを共有してください。
                    @break
                @case('hardware')
                    @include('App::Forms.03_HardwareSection', ['data' => $sectionData])
                    @break
                {{-- 他のセクションの説明も追加 --}}
            @endswitch
        </p>
    </div>

    {{-- フォームの内容 --}}
    @switch($currentSection)
        @case('basic-info')
            @include('App::Forms.01_BasicInfoForm', ['data' => $sectionData])
            @break
        @case('development-story')
            @include('App::Forms.02_DevelopmentStoryForm', ['data' => $sectionData])
            @break
        @case('hardware')
            @include('App::Forms.03_HardwareSection', ['data' => $sectionData])
            @break
        {{-- 他のセクションも同様に追加 --}}
    @endswitch
    
    {{-- ナビゲーションボタンのコンテナ --}}
    <div class="flex justify-between items-center mt-8 space-x-4">
        {{-- 「前へ」ボタン --}}
        @if($previousSection)
            <a href="{{ route('apps.create', ['section' => $previousSection]) }}" 
               class="flex items-center px-6 py-3 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg transition-colors duration-200 ease-in-out">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                前のセクションへ
            </a>
        @else
            <div></div>
        @endif

        {{-- 「次へ/保存」ボタン --}}
        <button type="submit" 
                class="flex items-center px-6 py-3 bg-blue-500 hover:bg-blue-600 text-white rounded-lg transition-colors duration-200 ease-in-out">
            {{ $nextSection ? '次のセクションへ' : '保存する' }}
            @if($nextSection)
                <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            @endif
        </button>
    </div>

    {{-- 保存状態の表示 --}}
    @if(session('success'))
        <div class="fixed bottom-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg">
            {{ session('success') }}
        </div>
    @endif
</form>