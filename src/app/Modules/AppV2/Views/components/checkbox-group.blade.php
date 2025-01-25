@props([
    'items' => [],        // チェックボックスの項目配列
    'model' => '',        // x-modelのパス
    'colorScheme' => 1    // カラースキーム番号（1-7）
])

@php
    // カラースキームの定義
    $colorSchemes = [
        1 => ['border' => 'blue-500', 'bg' => 'blue-50', 'text' => 'blue-700', 'hover' => 'blue-300'],
        2 => ['border' => 'purple-500', 'bg' => 'purple-50', 'text' => 'purple-700', 'hover' => 'purple-300'],
        3 => ['border' => 'pink-500', 'bg' => 'pink-50', 'text' => 'pink-700', 'hover' => 'pink-300'],
        4 => ['border' => 'emerald-500', 'bg' => 'emerald-50', 'text' => 'emerald-700', 'hover' => 'emerald-300'],
        5 => ['border' => 'amber-500', 'bg' => 'amber-50', 'text' => 'amber-700', 'hover' => 'amber-300'],
        6 => ['border' => 'rose-500', 'bg' => 'rose-50', 'text' => 'rose-700', 'hover' => 'rose-300'],
        7 => ['border' => 'indigo-500', 'bg' => 'indigo-50', 'text' => 'indigo-700', 'hover' => 'indigo-300'],
    ];
    $colors = $colorSchemes[$colorScheme] ?? $colorSchemes[1];
    
    // 動的クラス名を事前に生成
    $activeClasses = "border-{$colors['border']} bg-{$colors['bg']} text-{$colors['text']}";
    $inactiveClasses = "border-gray-200 hover:border-{$colors['hover']}";
@endphp

<div class="mt-4 grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
    @foreach($items as $key => $value)
        <label class="relative cursor-pointer">
            <input 
                type="checkbox"
                x-model="formData.{{ $model }}"
                @change="autoSave"
                value="{{ $key }}"
                class="peer sr-only">
            <div 
                class="rounded-lg border-2 p-4 transition-all duration-300 flex items-center justify-center"
                :class="{
                    '{{ $activeClasses }}': formData.{{ $model }}.includes('{{ $key }}'),
                    '{{ $inactiveClasses }}': !formData.{{ $model }}.includes('{{ $key }}')
                }"
            >
                <span class="block text-center">{{ is_array($value) ? $value['label'] : $value }}</span>
            </div>
        </label>
    @endforeach
</div> 