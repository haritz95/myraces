<x-app-layout>
    @section('page_title', 'Carreras')
    @section('back_url', route('admin.dashboard'))

    <div class="max-w-4xl mx-auto px-4 py-6 space-y-4">

        {{-- Filters --}}
        <form method="GET" action="{{ route('admin.races') }}" class="flex gap-2">
            <div class="relative flex-1">
                <svg class="w-4 h-4 absolute left-3.5 top-1/2 -translate-y-1/2" style="color:rgba(255,255,255,0.25)" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="Buscar carreras..."
                       class="input-field pl-10">
            </div>
            <select name="status" onchange="this.form.submit()" class="input-field w-auto pr-8">
                <option value="">Todos</option>
                @foreach(['upcoming' => 'Próxima', 'completed' => 'Completada', 'dnf' => 'DNF', 'dns' => 'DNS'] as $val => $label)
                    <option value="{{ $val }}" {{ request('status') === $val ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>
        </form>

        {{-- Races list --}}
        <div class="card overflow-hidden">
            <div class="px-5 py-3" style="border-bottom:1px solid rgba(255,255,255,0.06)">
                <p class="text-[10px] font-bold uppercase tracking-wider" style="color:rgba(255,255,255,0.30)">{{ $races->total() }} carreras</p>
            </div>
            <div>
                @forelse($races as $race)
                    @php
                        $badgeClass = match($race->status) {
                            'completed' => 'badge-completed',
                            'dnf'       => 'badge-dnf',
                            'dns'       => 'badge-dns',
                            default     => 'badge-upcoming',
                        };
                    @endphp
                    <div class="flex items-center gap-3 px-5 py-3.5 hover:bg-white/[0.03] transition-colors" style="border-bottom:1px solid rgba(255,255,255,0.04)">

                        <div class="w-10 text-center flex-shrink-0">
                            <p class="text-sm font-black text-white leading-none">{{ $race->date->format('d') }}</p>
                            <p class="text-[10px] uppercase" style="color:rgba(255,255,255,0.30)">{{ $race->date->translatedFormat('M y') }}</p>
                        </div>

                        <div class="min-w-0 flex-1">
                            <p class="text-sm font-semibold text-white truncate">{{ $race->name }}</p>
                            <p class="text-xs mt-0.5 truncate" style="color:rgba(255,255,255,0.35)">
                                {{ $race->user->name }} · {{ $race->distance }} km · {{ __('races.modalities.' . $race->modality) }}
                            </p>
                        </div>

                        <span class="badge {{ $badgeClass }} flex-shrink-0">
                            {{ __('races.statuses.' . $race->status) }}
                        </span>

                        <form method="POST" action="{{ route('admin.races.destroy', $race) }}"
                              onsubmit="return confirm('¿Eliminar «{{ addslashes($race->name) }}»? Esta acción es irreversible.')">
                            @csrf @method('DELETE')
                            <button type="submit"
                                    class="w-7 h-7 flex items-center justify-center rounded-lg border border-red-500/20 text-red-400 hover:bg-red-500/15 transition flex-shrink-0">
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                            </button>
                        </form>
                    </div>
                @empty
                    <div class="px-5 py-12 text-center">
                        <p class="text-sm" style="color:rgba(255,255,255,0.35)">No se encontraron carreras.</p>
                    </div>
                @endforelse
            </div>
        </div>

        {{ $races->withQueryString()->links() }}
    </div>
</x-app-layout>
