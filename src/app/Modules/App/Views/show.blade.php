<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- 各セクションを表示 -->
            @include('app::Forms.01_BasicInfoForm', ['app' => $app])
            @include('app::Forms.02_DevelopmentStoryForm', ['app' => $app])
            @include('app::Forms.03_HardwareSection', ['app' => $app])
            @include('app::Forms.04_BasicDevEnvironment', ['app' => $app])
            @include('app::Forms.05_DevToolsEnvironment', ['app' => $app])
            @include('app::Forms.06_ArchitectureSection', ['app' => $app])
            @include('app::Forms.07_SecuritySection', ['app' => $app])
            @include('app::Forms.08_BackendSection', ['app' => $app])
            @include('app::Forms.09_FrontendSection', ['app' => $app])
            @include('app::Forms.10_DatabaseSection', ['app' => $app])
        </div>
    </div>

    <!-- スクリーンショットモーダル -->
    <x-app::app-screenshot-modal />

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // 全てのフォーム要素を無効化
            const formElements = document.querySelectorAll('input, textarea, select, button');
            formElements.forEach(element => {
                if (element.type !== 'hidden') {
                    element.disabled = true;
                    element.style.backgroundColor = '#f3f4f6';  // gray-100
                    element.style.cursor = 'default';
                }
            });

            // ドラッグ&ドロップエリアを非表示
            const dropZones = document.querySelectorAll('[x-data]');
            dropZones.forEach(zone => {
                if (zone.hasAttribute('x-on:dragover.prevent')) {
                    zone.style.display = 'none';
                }
            });
        });
    </script>
</x-app-layout> 