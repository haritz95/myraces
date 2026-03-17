<x-app-layout>
    @section('page_title', __('races.add_race'))
    @section('back_url', route('races.index'))

    <form method="POST" action="{{ route('races.store') }}">
        @csrf
        @include('races.partials.form')
    </form>
</x-app-layout>
