<x-app-layout>
    @section('page_title', __('races.my_races'))

    {{-- Tabs (like example header tabs) --}}
    <div class="bg-bg-warm/80 backdrop-blur-md border-b border-slate-200 sticky top-[72px] md:top-[60px] z-20">
        <nav class="flex px-6 space-x-6 overflow-x-auto scrollbar-hide max-w-2xl mx-auto">
            <a href="{{ route('races.index') }}"
               class="pb-3 text-sm font-bold whitespace-nowrap border-b-2 transition-colors
                      {{ !request('status') ? 'border-primary text-primary' : 'border-transparent text-slate-500 hover:text-primary' }}">
                Todas
            </a>
            @foreach(['upcoming' => __('races.statuses.upcoming'), 'completed' => __('races.statuses.completed'), 'dnf' => __('races.statuses.dnf'), 'dns' => __('races.statuses.dns')] as $s => $label)
                <a href="{{ route('races.index', ['status' => $s] + (request('modality') ? ['modality' => request('modality')] : []) + (request('year') ? ['year' => request('year')] : [])) }}"
                   class="pb-3 text-sm font-medium whitespace-nowrap border-b-2 transition-colors
                          {{ request('status') === $s ? 'border-primary text-primary font-bold' : 'border-transparent text-slate-500 hover:text-primary' }}">
                    {{ $label }}
                </a>
            @endforeach
        </nav>
    </div>

    <main class="px-6 py-6 max-w-2xl mx-auto w-full">

        {{-- Secondary filters --}}
        <div class="flex gap-2 mb-6 overflow-x-auto scrollbar-hide">
            <form method="GET" action="{{ route('races.index') }}" class="contents">
                @if(request('status'))
                    <input type="hidden" name="status" value="{{ request('status') }}">
                @endif
                <select name="modality" onchange="this.form.submit()"
                        class="text-xs rounded-full px-4 py-2 border bg-white text-slate-600 focus:outline-none flex-shrink-0 cursor-pointer font-semibold
                               {{ request('modality') ? 'border-primary text-primary bg-primary/5' : 'border-slate-200' }}">
                    <option value="">Modalidad</option>
                    @foreach(['road', 'trail', 'mountain', 'track', 'cross', 'other'] as $m)
                        <option value="{{ $m }}" {{ request('modality') === $m ? 'selected' : '' }}>{{ __('races.modalities.' . $m) }}</option>
                    @endforeach
                </select>
                @if($years->isNotEmpty())
                    <select name="year" onchange="this.form.submit()"
                            class="text-xs rounded-full px-4 py-2 border bg-white text-slate-600 focus:outline-none flex-shrink-0 cursor-pointer font-semibold
                                   {{ request('year') ? 'border-primary text-primary bg-primary/5' : 'border-slate-200' }}">
                        <option value="">Año</option>
                        @foreach($years as $year)
                            <option value="{{ $year }}" {{ request('year') == $year ? 'selected' : '' }}>{{ $year }}</option>
                        @endforeach
                    </select>
                @endif
                @if(request()->hasAny(['modality', 'year']))
                    <a href="{{ route('races.index', request('status') ? ['status' => request('status')] : []) }}"
                       class="text-xs border border-slate-200 rounded-full px-4 py-2 text-slate-400 hover:text-slate-700 hover:bg-slate-50 flex-shrink-0 flex items-center gap-1.5 font-semibold bg-white">
                        <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                        Limpiar
                    </a>
                @endif
            </form>
        </div>

        @forelse($races as $race)
            <div class="mb-3">
                @include('races.partials.race-card', ['race' => $race])
            </div>
        @empty
            <div class="flex flex-col items-center justify-center py-20 text-center">
                <div class="w-20 h-20 bg-primary/10 rounded-xl flex items-center justify-center mb-5">
                    <svg class="w-10 h-10 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                </div>
                <p class="text-slate-800 font-bold text-xl">{{ __('races.no_races') }}</p>
                <p class="text-slate-400 text-sm mt-2 mb-6">
                    @if(request()->hasAny(['status', 'modality', 'year']))
                        No hay carreras con los filtros seleccionados.
                    @else
                        Aún no has registrado ninguna carrera.
                    @endif
                </p>
                @if(request()->hasAny(['status', 'modality', 'year']))
                    <a href="{{ route('races.index') }}" class="btn btn-secondary">Limpiar filtros</a>
                @else
                    <a href="{{ route('races.create') }}" class="btn btn-primary px-6">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                        {{ __('races.add_first') }}
                    </a>
                @endif
            </div>
        @endforelse
        <div class="mt-4 mb-20">{{ $races->links() }}</div>
    </main>
</x-app-layout>
