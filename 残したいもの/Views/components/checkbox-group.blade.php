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
    x-data="{
        updateValue(value) {
            if (!Array.isArray(formData.{{ $section }}.{{ $field }})) {
                formData.{{ $section }}.{{ $field }} = [];
            }
            const index = formData.{{ $section }}.{{ $field }}.indexOf(value);
            if (index === -1) {
                formData.{{ $section }}.{{ $field }}.push(value);
            } else {
                formData.{{ $section }}.{{ $field }}.splice(index, 1);
            }
            this.$dispatch('form-updated');
        }
    }"
>
    @foreach($items as $key => $value)
        <label class="relative cursor-pointer">
            <input 
                type="checkbox"
                :checked="formData.{{ $section }}.{{ $field }}.includes('{{ $key }}')"
                @change="updateValue('{{ $key }}')"
                class="peer sr-only"
            >
            <div 
                :class="{
                    'border-blue-500 bg-blue-50 text-blue-700': formData.{{ $section }}.{{ $field }}.includes('{{ $key }}'),
                    'border-gray-200 hover:border-blue-300 bg-gray-50 text-gray-700': !formData.{{ $section }}.{{ $field }}.includes('{{ $key }}')
                }"
                class="rounded-lg border-2 p-4 transition-all duration-300 flex items-center justify-center"
            >
                <span class="block text-center">{{ is_array($value) ? $value['label'] : $value }}</span>
            </div>
        </label>
    @endforeach
</div> 