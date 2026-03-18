<x-app-layout>
    @section('page_title', 'Editar carrera')
    @section('back_url', route('admin.events.index'))

    <div class="max-w-lg mx-auto px-4 py-6">
        <form method="POST" action="{{ route('admin.events.update', $event) }}" enctype="multipart/form-data" class="space-y-6">
            @csrf @method('PATCH')
            @include('admin.events._form')
            <div class="flex gap-3">
                <button type="submit" class="btn btn-primary flex-1">Guardar cambios</button>
                <a href="{{ route('admin.events.index') }}" class="btn btn-secondary flex-1">Cancelar</a>
            </div>
        </form>
    </div>
</x-app-layout>
