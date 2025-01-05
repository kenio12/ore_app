<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <form action="{{ route('apps.update', $app) }}" method="POST" class="space-y-8">
                @csrf
                @method('PUT')

                <!-- フォームの全セクションをインクルード -->
                @include('App::Forms._form')
            </form>
        </div>
    </div>
</x-app-layout> 