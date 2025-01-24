<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold">アプリ一覧（新バージョン）</h1>
                <a href="{{ route('apps-v2.create') }}" 
                   class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg">
                    新規作成
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($apps as $app)
                    <div class="bg-white rounded-lg shadow p-6">
                        <h2 class="text-xl font-bold mb-2">{{ $app->title }}</h2>
                        <p class="text-gray-600 mb-4">
                            {{ Str::limit($app->description, 100) }}
                        </p>
                        <div class="flex justify-end space-x-4">
                            <a href="{{ route('apps-v2.edit', $app) }}" 
                               class="text-blue-500 hover:text-blue-700">
                                編集する
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="col-span-3 text-center py-12 text-gray-500">
                        まだアプリが登録されていません。
                        <a href="{{ route('apps-v2.create') }}" class="text-blue-500 hover:text-blue-700">
                            新しく作成してみましょう！
                        </a>
                    </div>
                @endforelse
            </div>

            @if($apps->hasPages())
                <div class="mt-6">
                    {{ $apps->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout> 