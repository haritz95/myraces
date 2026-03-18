<x-app-layout>
    @section('page_title', 'Editar gasto')
    @section('back_url', route('expenses.index'))

    <main class="px-5 py-6 max-w-2xl mx-auto w-full">
        <form method="POST" action="{{ route('expenses.update', $expense) }}" class="space-y-4">
            @csrf
            @method('PATCH')
            @include('expenses.partials.form', ['expense' => $expense])
        </form>
    </main>
</x-app-layout>
