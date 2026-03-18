<x-app-layout>
    @section('page_title', 'Nueva carrera')
    @section('back_url', route('admin.events.index'))

    <div class="max-w-lg mx-auto px-4 py-6">
        <form method="POST" action="{{ route('admin.events.store') }}" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @include('admin.events._form')
            <button type="submit" class="btn btn-primary w-full">Crear carrera</button>
        </form>
    </div>
</x-app-layout>
