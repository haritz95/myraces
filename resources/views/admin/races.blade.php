<x-app-layout>
    @section('page_title', 'Todas las Carreras')
    @section('back_url', route('admin.dashboard'))

    <div class="max-w-4xl mx-auto px-4 py-6 space-y-4">

        {{-- Filters --}}
        <form method="GET" action="{{ route('admin.races') }}" class="flex gap-2">
            <div class="relative flex-1">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="Buscar carreras..."
                       class="input-field pl-10">
            </div>
            <select name="status" onchange="this.form.submit()"
                    class="border border-slate-200 rounded-xl px-3 py-3 text-sm text-slate-700 bg-white focus:outline-none focus:ring-2 focus:ring-orange-400">
                <option value="">Todos los estados</option>
                @foreach(['upcoming' => 'Próxima', 'completed' => 'Completada', 'dnf' => 'DNF', 'dns' => 'DNS'] as $val => $label)
                    <option value="{{ $val }}" {{ request('status') === $val ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>
        </form>

        {{-- Races list --}}
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
            <div class="px-5 py-3 border-b border-slate-100 bg-slate-50">
                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wide">{{ $races->total() }} carreras</p>
            </div>
            <div class="divide-y divide-slate-100">
                @forelse($races as $race)
                    @php
                        $statusColors = [
                            'upcoming' => 'bg-blue-50 text-blue-600',
                            'completed' => 'bg-emerald-50 text-emerald-600',
                            'dnf' => 'bg-red-50 text-red-500',
                            'dns' => 'bg-slate-100 text-slate-500',
                        ];
                    @endphp
                    <div class="flex items-center gap-3 px-5 py-3.5">
                        <div class="w-10 text-center flex-shrink-0">
                            <p class="text-base font-bold text-slate-700 leading-none">{{ $race->date->format('d') }}</p>
                            <p class="text-xs text-slate-400 uppercase">{{ $race->date->translatedFormat('M y') }}</p>
                        </div>
                        <div class="min-w-0 flex-1">
                            <p class="text-sm font-semibold text-slate-800 truncate">{{ $race->name }}</p>
                            <p class="text-xs text-slate-400 truncate">{{ $race->user->name }} · {{ $race->distance }} km · {{ __('races.modalities.' . $race->modality) }}</p>
                        </div>
                        <span class="text-xs font-medium px-2 py-1 rounded-full flex-shrink-0 {{ $statusColors[$race->status] ?? 'bg-slate-100 text-slate-500' }}">
                            {{ __('races.statuses.' . $race->status) }}
                        </span>
                    </div>
                @empty
                    <div class="px-5 py-10 text-center text-slate-400 text-sm">No se encontraron carreras.</div>
                @endforelse
            </div>
        </div>

        {{ $races->withQueryString()->links() }}
    </div>
</x-app-layout>
