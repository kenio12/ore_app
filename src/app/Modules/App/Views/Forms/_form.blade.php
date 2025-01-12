@php
    // セッションから現在のセクションのデータを取得
    $sectionData = session("app_form.{$currentSection}", []);
@endphp

{{-- プログレスバー --}}
<div class="mb-8">
    <div class="flex justify-between mb-2">
        @foreach($sections as $key => $title)
            <div class="flex flex-col items-center">
                <div class="w-8 h-8 rounded-full flex items-center justify-center 
                    {{ $key === $currentSection ? 'bg-blue-500 text-white' : 
                       (array_search($key, $sections) < array_search($currentSection, $sections) ? 'bg-green-500 text-white' : 'bg-gray-200') }}">
                    {{ $loop->iteration }}
                </div>
                <span class="text-xs mt-1 {{ $key === $currentSection ? 'text-blue-500 font-bold' : 'text-gray-500' }}">
                    {{ $title }}
                </span>
            </div>
            @if(!$loop->last)
                <div class="flex-1 h-1 mt-4 mx-2 
                    {{ array_search($key, $sections) < array_search($currentSection, $sections) ? 'bg-green-500' : 'bg-gray-200' }}">
                </div>
            @endif
        @endforeach
    </div>
</div>

<form method="POST" action="{{ route('app.store', ['section' => $currentSection]) }}" class="space-y-6">
    @csrf
    
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
                {{-- 他のセクションの説明も追加 --}}
            @endswitch
        </p>
    </div>

    {{-- フォームの内容 --}}
    @if($currentSection === 'basic-info')
        @include('app::Forms.01_BasicInfoForm', ['data' => $sectionData])
    @elseif($currentSection === 'development-story')
        @include('app::Forms.02_DevelopmentStoryForm', ['data' => $sectionData])
    @endif
    
    {{-- ナビゲーションボタン --}}
    <div class="flex justify-between mt-8 pt-6 border-t">
        @if($previousSection)
            <a href="{{ route('app.create', ['section' => $previousSection]) }}" 
               class="px-6 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                前のセクションへ戻る
            </a>
        @else
            <div></div>
        @endif
        
        <button type="submit" class="px-6 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors flex items-center">
            @if($nextSection)
                <span class="mr-2">このセクションを保存して次へ</span>
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            @else
                <span>すべての入力を完了して登録</span>
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