<!-- プログレスバー -->
<div class="mb-8">
    <div class="bg-gray-200 rounded-full h-2.5">
        <div class="bg-blue-600 h-2.5 rounded-full" x-bind:style="{ width: progress + '%' }"></div>
    </div>
</div>

<!-- フォームセクション（正しい順序で） -->
@include('AppPost::Forms.01_BasicInfoForm')
@include('AppPost::Forms.08_DevelopmentStoryForm')
@include('AppPost::Forms.02_HardwareSection')
@include('AppPost::Forms.03_SoftwareEnvironmentSection')
@include('AppPost::Forms.09_ArchitectureSection')
@include('AppPost::Forms.04_BackendSection')
@include('AppPost::Forms.05_FrontendSection')
@include('AppPost::Forms.06_DatabaseSection')
@include('AppPost::Forms.07_OthersSection')

<!-- 送信ボタン -->
<div class="mt-8 flex justify-end space-x-4">
    @if(isset($post))
        <a href="{{ route('app-posts.show', $post) }}" class="bg-gray-500 text-white px-6 py-2 rounded-md hover:bg-gray-600">
            キャンセル
        </a>
        <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-md hover:bg-blue-700">
            更新する
        </button>
    @else
        <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-md hover:bg-blue-700">
            投稿する
        </button>
    @endif
</div> 