<x-app-layout>
    @section('page_title', __('races.edit_race'))
    @section('back_url', route('races.show', $race))

    <form method="POST" action="{{ route('races.update', $race) }}">
        @csrf
        @method('PATCH')
        @include('races.partials.form')
    </form>
</x-app-layout>
