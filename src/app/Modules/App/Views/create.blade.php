<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @include('app::Forms._form', [
                'app' => $app
            ])
        </div>
    </div>
</x-app-layout> 