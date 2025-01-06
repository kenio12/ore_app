<!-- プログレスバー -->
<div class="mb-8">
    <div class="bg-gray-200 rounded-full h-2.5">
        <div class="bg-blue-600 h-2.5 rounded-full" x-bind:style="{ width: progress + '%' }"></div>
    </div>
</div>

<!-- フォームセクション（正しい順序で） -->
@include('App::Forms.01_BasicInfoForm')
@include('App::Forms.02_DevelopmentStoryForm')
@include('App::Forms.03_HardwareSection')
@include('App::Forms.04_1_BasicDevEnvironment')
@include('App::Forms.04_2_DevToolsEnvironment')
@include('App::Forms.04_3_ArchitectureSection')
@include('App::Forms.05_BackendSection')
@include('App::Forms.06_FrontendSection')
@include('App::Forms.07_DatabaseSection')

<!-- 送信ボタン -->
<div class="mt-8 flex justify-end space-x-4">
    <button type="button" onclick="history.back()" class="bg-gray-500 text-white px-6 py-2 rounded-md hover:bg-gray-600">
        キャンセル
    </button>
    <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-md hover:bg-blue-700">
        {{ isset($app) ? '更新する' : '投稿する' }}
    </button>
</div> 