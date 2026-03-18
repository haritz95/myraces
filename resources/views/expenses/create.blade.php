<x-app-layout>
    @section('page_title', 'Nuevo gasto')
    @section('back_url', route('expenses.index'))

    <main class="px-5 py-6 max-w-2xl mx-auto w-full">
        <form method="POST" action="{{ route('expenses.store') }}" class="space-y-4">
            @csrf
            @include('expenses.partials.form', ['expense' => null])
        </form>
    </main>
</x-app-layout>
