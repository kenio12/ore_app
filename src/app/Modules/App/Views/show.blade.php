<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- 各セクションを表示 -->
            @include('App::Forms.01_BasicInfoForm', ['app' => $app])
            @include('App::Forms.02_DevelopmentStoryForm', ['app' => $app])
            @include('App::Forms.03_HardwareSection', ['app' => $app])
            @include('App::Forms.04_BasicDevEnvironment', ['app' => $app])
            @include('App::Forms.05_DevToolsEnvironment', ['app' => $app])
            @include('App::Forms.06_ArchitectureSection', ['app' => $app])
            @include('App::Forms.07_SecuritySection', ['app' => $app])
            @include('App::Forms.08_BackendSection', ['app' => $app])
            @include('App::Forms.09_FrontendSection', ['app' => $app])
            @include('App::Forms.10_DatabaseSection', ['app' => $app])
        </div>
    </div>

    <!-- スクリーンショットモーダル -->
    <x-App::app-screenshot-modal />

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