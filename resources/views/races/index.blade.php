<x-app-layout>
    @section('page_title', __('races.my_races'))
    @section('header_action')
        <a href="{{ route('races.create') }}" class="btn btn-primary text-sm py-2 px-4">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" />
            </svg>
            Añadir
        </a>
    @endsection

    {{-- Status tabs --}}
    <div class="sticky top-[58px] md:top-[60px] z-20"
        style="background:rgba(10,10,10,0.90);backdrop-filter:blur(16px);border-bottom:1px solid rgba(255,255,255,0.06)">
        <nav class="flex px-5 space-x-1 overflow-x-auto scrollbar-hide max-w-2xl mx-auto">
            <a href="{{ route('races.index') }}"
                class="pb-3 pt-2 px-2 text-sm font-bold whitespace-nowrap border-b-2 transition-colors
                      {{ !request('status') ? 'border-primary text-primary' : 'border-transparent hover:text-white' }}"
                style="{{ request('status') ? 'color:rgba(255,255,255,0.40)' : '' }}">
                Todas
            </a>
            @foreach (['upcoming' => __('races.statuses.upcoming'), 'completed' => __('races.statuses.completed'), 'dnf' => __('races.statuses.dnf'), 'dns' => __('races.statuses.dns')] as $s => $label)
                <a href="{{ route('races.index', ['status' => $s] + (request('modality') ? ['modality' => request('modality')] : []) + (request('year') ? ['year' => request('year')] : [])) }}"
                    class="pb-3 pt-2 px-2 text-sm font-bold whitespace-nowrap border-b-2 transition-colors
                          {{ request('status') === $s ? 'border-primary text-primary' : 'border-transparent hover:text-white' }}"
                    style="{{ request('status') !== $s ? 'color:rgba(255,255,255,0.40)' : '' }}">
                    {{ $label }}
                </a>
            @endforeach
        </nav>
    </div>

    <main class="px-5 py-5 max-w-2xl mx-auto w-full">

        {{-- Filters --}}
        <div class="flex gap-2 mb-5 overflow-x-auto scrollbar-hide">
            <form method="GET" action="{{ route('races.index') }}" class="contents">
                @if (request('status'))
                    <input type="hidden" name="status" value="{{ request('status') }}">
                @endif
                <div class="relative flex-shrink-0">
                    <select name="modality" onchange="this.form.submit()"
                        class="appearance-none text-xs rounded-full pl-3.5 pr-7 py-2 font-bold cursor-pointer transition-colors
                               {{ request('modality') ? 'bg-primary text-black' : 'text-white/60 hover:text-white' }}"
                        style="{{ request('modality') ? 'border:1px solid transparent' : 'background:rgba(255,255,255,0.07);border:1px solid rgba(255,255,255,0.10)' }}">
                        <option value="">Modalidad</option>
                        @foreach (['road', 'trail', 'mountain', 'track', 'cross', 'other'] as $m)
                            <option value="{{ $m }}" {{ request('modality') === $m ? 'selected' : '' }}>{{ __('races.modalities.' . $m) }}</option>
                        @endforeach
                    </select>
                    <svg class="pointer-events-none absolute right-2.5 top-1/2 -translate-y-1/2 w-3 h-3"
                         fill="none" viewBox="0 0 24 24" stroke="currentColor"
                         style="{{ request('modality') ? 'color:#000' : 'color:rgba(255,255,255,0.50)' }}">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/>
                    </svg>
                </div>
                @if ($years->isNotEmpty())
                    <div class="relative flex-shrink-0">
                        <select name="year" onchange="this.form.submit()"
                            class="appearance-none text-xs rounded-full pl-3.5 pr-7 py-2 font-bold cursor-pointer transition-colors
                                   {{ request('year') ? 'bg-primary text-black' : 'text-white/60 hover:text-white' }}"
                            style="{{ request('year') ? 'border:1px solid transparent' : 'background:rgba(255,255,255,0.07);border:1px solid rgba(255,255,255,0.10)' }}">
                            <option value="">Año</option>
                            @foreach ($years as $year)
                                <option value="{{ $year }}" {{ request('year') == $year ? 'selected' : '' }}>{{ $year }}</option>
                            @endforeach
                        </select>
                        <svg class="pointer-events-none absolute right-2.5 top-1/2 -translate-y-1/2 w-3 h-3"
                             fill="none" viewBox="0 0 24 24" stroke="currentColor"
                             style="{{ request('year') ? 'color:#000' : 'color:rgba(255,255,255,0.50)' }}">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </div>
                @endif
                @if (request()->hasAny(['modality', 'year']))
                    <a href="{{ route('races.index', request('status') ? ['status' => request('status')] : []) }}"
                        class="text-xs rounded-full px-4 py-2 font-bold flex-shrink-0 flex items-center gap-1.5 transition-colors text-white/50 hover:text-white"
                        style="background:rgba(255,255,255,0.07);border:1px solid rgba(255,255,255,0.10)">
                        <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                        Limpiar
                    </a>
                @endif
            </form>
        </div>

        @forelse($races as $race)
            <div class="mb-2">
                @include('races.partials.race-card', ['race' => $race])
            </div>
        @empty
            <div class="flex flex-col items-center justify-center py-20 text-center">
                <div
                    class="w-20 h-20 bg-primary/10 rounded-3xl border border-primary/20 flex items-center justify-center mb-5">
                    <svg class="w-10 h-10 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>
                </div>
                <p class="text-white font-black text-xl">{{ __('races.no_races') }}</p>
                <p class="text-sm mt-2 mb-6" style="color:rgba(255,255,255,0.35)">
                    @if (request()->hasAny(['status', 'modality', 'year']))
                        No hay carreras con los filtros seleccionados.
                    @else
                        Aún no has registrado ninguna carrera.
                    @endif
                </p>
                @if (request()->hasAny(['status', 'modality', 'year']))
                    <a href="{{ route('races.index') }}" class="btn btn-secondary">Limpiar filtros</a>
                @else
                    <a href="{{ route('races.create') }}" class="btn btn-primary px-8">
                        {{ __('races.add_first') }}
                    </a>
                @endif
            </div>
        @endforelse

        <div class="mt-4">{{ $races->links() }}</div>
    </main>
</x-app-layout>
