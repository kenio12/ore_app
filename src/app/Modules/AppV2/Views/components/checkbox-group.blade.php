@props([
    'items' => [],        // チェックボックスの項目配列
    'model' => '',        // x-modelのパス
    'colorScheme' => 1    // カラースキーム番号（1-7）
])

@php
    $modelPath = explode('.', $model);
    $section = $modelPath[0];
    $field = $modelPath[1];
@endphp

<div 
    class="mt-4 grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4"
>
    @foreach($items as $key => $value)
        <label class="relative cursor-pointer">
            <input 
                type="checkbox"
                x-model="formData.{{ $section }}.{{ $field }}"
                value="{{ $key }}"
                @change="autoSave"
                class="peer sr-only"
            >
            <div 
                class="rounded-lg border-2 p-4 transition-all duration-300 flex items-center justify-center"
                :class="formData?.{{ $section }}?.{{ $field }}?.includes('{{ $key }}')
                    ? 'border-blue-500 bg-blue-50 text-blue-700'
                    : 'border-gray-200 bg-gray-50 text-gray-700 hover:border-gray-300'"
            >
                <span class="block text-center">{{ is_array($value) ? $value['label'] : $value }}</span>
            </div>
        </label>
    @endforeach
</div> 