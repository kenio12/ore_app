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
    action="{{ $app->exists 
        ? route('app.sections.' . $currentSection . '.update', ['app' => $app->id]) 
        : route('app.sections.' . $currentSection . '.store') }}"
    enctype="multipart/form-data">
    @csrf
    @if($app->exists)
        @method('PUT')
    @endif
    
    {{-- セクション情報を追加 --}}
    <input type="hidden" name="current_section" value="{{ $currentSection }}">
    
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
                    ハードウェア環境に関する情報を入力してください。
                    @break
                @case('basic-dev')
                    基本的な開発環境について入力してください。
                    @break
                {{-- 他のセクションの説明も追加 --}}
            @endswitch
        </p>
    </div>

    {{-- フォームの内容（ここで1回だけinclude） --}}
    @switch($currentSection)
        @case('basic-info')
            @include('App::Forms.01_BasicInfoForm', [
                'data' => $sectionData,
                'app' => $app ?? null,
                'viewOnly' => $viewOnly ?? false
            ])
            @break
        @case('development-story')
            @include('App::Forms.02_DevelopmentStoryForm', [
                'data' => $sectionData,
                'app' => $app ?? null
            ])
            @break
        @case('hardware')
            @include('App::Forms.03_Hardware', [
                'data' => $sectionData,
                'app' => $app ?? null
            ])
            @break
        @case('basic-dev')
            @include('App::Forms.04_BasicDevSection', ['data' => $sectionData])
            @break
        {{-- 他のセクションも同様に追加 --}}
    @endswitch
    
    {{-- ナビゲーションボタン --}}
    @unless($viewOnly ?? false)
        <div class="flex justify-between mt-8">
            @if($currentSection !== array_key_first($sections))
                <a href="{{ route('app.sections.' . array_keys($sections)[array_search($currentSection, array_keys($sections)) - 1] . '.edit', ['app' => $app->id]) }}" 
                    class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded">
                    前のセクションへ
                </a>
            @else
                <div></div>
            @endif

            <button type="submit" 
                    class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded">
                @if($currentSection === array_key_last($sections))
                    保存する
                @else
                    次のセクションへ
                @endif
            </button>
        </div>
    @endunless

    {{-- 保存状態の表示 --}}
    @if(session('success'))
        <div class="fixed bottom-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg">
            {{ session('success') }}
        </div>
    @endif
</form>