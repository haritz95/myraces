<x-app-layout>
    @section('page_title', 'Récords personales')

    <main class="px-4 py-6 max-w-2xl mx-auto w-full pb-[76px]">

        {{-- ── QUICK ADD FORM ────────────────────────────────── --}}
        <div class="bg-white rounded-xl border border-slate-100 shadow-card p-4 mb-6"
             x-data="{
                timeStr: '',
                computeSeconds() {
                    const parts = this.timeStr.split(':');
                    if (parts.length === 3) {
                        const h = parseInt(parts[0]) || 0;
                        const m = parseInt(parts[1]) || 0;
                        const s = parseInt(parts[2]) || 0;
                        return (h * 3600) + (m * 60) + s;
                    }
                    if (parts.length === 2) {
                        const m = parseInt(parts[0]) || 0;
                        const s = parseInt(parts[1]) || 0;
                        return (m * 60) + s;
                    }
                    return 0;
                }
             }">
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.18em] mb-3">Añadir récord</p>
            <form method="POST" action="{{ route('personal-records.store') }}"
                  @submit="$el.querySelector('[name=time_seconds]').value = computeSeconds()"
                  class="space-y-3">
                @csrf
                <input type="hidden" name="time_seconds" value="">

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1.5">Distancia <span class="text-red-400">*</span></label>
                        <select name="distance_label"
                                class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl text-slate-900 focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary @error('distance_label') border-red-400 @enderror">
                            <option value="">Seleccionar...</option>
                            @foreach(['5K', '10K', 'Half Marathon', 'Marathon', '50K', '100K', 'Other'] as $dist)
                                <option value="{{ $dist }}" {{ old('distance_label') === $dist ? 'selected' : '' }}>{{ $dist }}</option>
                            @endforeach
                        </select>
                        @error('distance_label') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1.5">Tiempo <span class="text-red-400">*</span></label>
                        <input type="text" name="time_display" placeholder="H:MM:SS"
                               x-model="timeStr"
                               value="{{ old('time_display') }}"
                               class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl text-slate-900 focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary tabnum @error('time_seconds') border-red-400 @enderror">
                        @error('time_seconds') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1.5">Fecha <span class="text-red-400">*</span></label>
                        <input type="date" name="date" value="{{ old('date', now()->format('Y-m-d')) }}"
                               class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl text-slate-900 focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary @error('date') border-red-400 @enderror">
                        @error('date') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1.5">Lugar</label>
                        <input type="text" name="location" value="{{ old('location') }}"
                               placeholder="Ej. Madrid"
                               class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl text-slate-900 focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary">
                        @error('location') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <button type="submit" class="bg-primary hover:bg-orange-600 text-white font-semibold px-6 py-3 rounded-xl transition-colors w-full text-sm">
                    Guardar récord
                </button>
            </form>
        </div>

        {{-- ── BEST RECORDS GRID ─────────────────────────────── --}}
        @php
            $featuredDistances = ['5K', '10K', 'Half Marathon', 'Marathon'];
            $distanceColors = [
                '5K'            => ['bg' => 'bg-blue-500/10', 'text' => 'text-blue-600', 'dot' => 'bg-blue-500'],
                '10K'           => ['bg' => 'bg-purple-500/10', 'text' => 'text-purple-600', 'dot' => 'bg-purple-500'],
                'Half Marathon' => ['bg' => 'bg-emerald-500/10', 'text' => 'text-emerald-600', 'dot' => 'bg-emerald-500'],
                'Marathon'      => ['bg' => 'bg-primary/10', 'text' => 'text-primary', 'dot' => 'bg-primary'],
            ];
        @endphp

        @if($bestByDistance->isNotEmpty())
            <div class="mb-6">
                <h2 class="text-base font-bold text-slate-900 mb-3">Mejores marcas</h2>
                <div class="grid grid-cols-2 gap-3">
                    @foreach($featuredDistances as $dist)
                        @php $colors = $distanceColors[$dist] ?? ['bg' => 'bg-slate-100', 'text' => 'text-slate-600', 'dot' => 'bg-slate-400']; @endphp
                        <div class="bg-white rounded-xl border border-slate-100 shadow-card p-4">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">{{ $dist }}</span>
                                <div class="w-2 h-2 rounded-full {{ $colors['dot'] }}"></div>
                            </div>
                            @if($bestByDistance->has($dist))
                                @php $pr = $bestByDistance->get($dist); @endphp
                                @php
                                    $secs = $pr->time_seconds;
                                    $h = intdiv($secs, 3600);
                                    $m = intdiv($secs % 3600, 60);
                                    $s = $secs % 60;
                                    $formatted = $h > 0
                                        ? sprintf('%d:%02d:%02d', $h, $m, $s)
                                        : sprintf('%d:%02d', $m, $s);
                                @endphp
                                <p class="text-2xl font-bold tabnum {{ $colors['text'] }} leading-none">{{ $formatted }}</p>
                                <p class="text-xs text-slate-400 mt-1">{{ $pr->date->format('d M Y') }}</p>
                            @else
                                <p class="text-xl font-bold text-slate-200 leading-none">—</p>
                                <p class="text-xs text-slate-300 mt-1">Sin marca</p>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- ── ALL RECORDS LIST ─────────────────────────────── --}}
        <div class="flex items-center justify-between mb-3">
            <h2 class="text-base font-bold text-slate-900">Todos los récords</h2>
            <span class="text-xs text-slate-400">{{ $records->count() }} registros</span>
        </div>

        @if($records->isEmpty())
            <div class="bg-white rounded-xl border border-slate-100 shadow-card px-5 py-12 text-center">
                <div class="w-14 h-14 rounded-xl bg-primary/10 flex items-center justify-center mx-auto mb-3">
                    <svg class="w-7 h-7 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                </div>
                <p class="text-slate-700 font-bold mb-1">Sin récords registrados</p>
                <p class="text-slate-400 text-sm">Añade tu primer récord personal usando el formulario de arriba.</p>
            </div>
        @else
            <div class="space-y-2">
                @foreach($records->sortByDesc('date') as $record)
                    @php
                        $secs = $record->time_seconds;
                        $h = intdiv($secs, 3600);
                        $m = intdiv($secs % 3600, 60);
                        $s = $secs % 60;
                        $formattedTime = $h > 0
                            ? sprintf('%d:%02d:%02d', $h, $m, $s)
                            : sprintf('%d:%02d', $m, $s);
                        $isBest = $bestByDistance->has($record->distance_label)
                            && $bestByDistance->get($record->distance_label)->id === $record->id;
                    @endphp
                    <div class="bg-white rounded-xl border border-slate-100 shadow-card p-4 flex items-center gap-3">
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2 mb-1">
                                <span class="text-xs font-bold text-slate-700">{{ $record->distance_label }}</span>
                                @if($isBest)
                                    <span class="text-[10px] font-bold uppercase tracking-wider px-2 py-0.5 rounded-full bg-amber-100 text-amber-700">
                                        PR
                                    </span>
                                @endif
                            </div>
                            <div class="flex items-center gap-3">
                                <p class="text-xl font-bold tabnum text-slate-900 leading-none">{{ $formattedTime }}</p>
                            </div>
                            <div class="flex items-center gap-2 mt-1">
                                <p class="text-xs text-slate-400">{{ $record->date->format('d M Y') }}</p>
                                @if($record->location)
                                    <span class="text-slate-200">·</span>
                                    <p class="text-xs text-slate-400 truncate">{{ $record->location }}</p>
                                @endif
                            </div>
                        </div>
                        <form method="POST" action="{{ route('personal-records.destroy', $record) }}"
                              onsubmit="return confirm('¿Eliminar este récord?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="w-8 h-8 flex items-center justify-center rounded-lg hover:bg-red-50 text-slate-300 hover:text-red-500 transition-colors flex-shrink-0">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                            </button>
                        </form>
                    </div>
                @endforeach
            </div>
        @endif

    </main>
</x-app-layout>
