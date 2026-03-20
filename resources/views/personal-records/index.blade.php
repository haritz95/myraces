<x-app-layout>
    @section('page_title', 'Récords personales')

    <main class="px-5 py-6 max-w-2xl mx-auto w-full space-y-8">

        {{-- ── QUICK ADD FORM ────────────────────────────────── --}}
        <section x-data="{
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
            <h2 class="text-lg font-black text-white mb-3">Añadir récord manual</h2>
            <p class="text-sm mb-4" style="color:rgba(255,255,255,0.40)">Las carreras completadas con tiempo generan récords automáticamente.</p>
            <div class="card p-5">
                <form method="POST" action="{{ route('personal-records.store') }}"
                      @submit="$el.querySelector('[name=time_seconds]').value = computeSeconds()"
                      class="space-y-4">
                    @csrf
                    <input type="hidden" name="time_seconds" value="">

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-sm font-bold text-white mb-2">Distancia <span class="text-primary">*</span></label>
                            <select name="distance_label" class="input-field @error('distance_label') error @enderror">
                                <option value="">Seleccionar...</option>
                                @foreach(['5K', '10K', 'Half Marathon', 'Marathon', '50K', '100K', 'Other'] as $dist)
                                    <option value="{{ $dist }}" {{ old('distance_label') === $dist ? 'selected' : '' }}>{{ $dist }}</option>
                                @endforeach
                            </select>
                            @error('distance_label') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-white mb-2">Tiempo <span class="text-primary">*</span></label>
                            <input type="text" name="time_display" placeholder="H:MM:SS"
                                   x-model="timeStr"
                                   value="{{ old('time_display') }}"
                                   class="input-field tabnum @error('time_seconds') error @enderror">
                            @error('time_seconds') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-sm font-bold text-white mb-2">Fecha <span class="text-primary">*</span></label>
                            <input type="date" name="date" value="{{ old('date', now()->format('Y-m-d')) }}"
                                   class="input-field @error('date') error @enderror">
                            @error('date') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-white mb-2">Lugar</label>
                            <input type="text" name="location" value="{{ old('location') }}"
                                   placeholder="Ej. Madrid" class="input-field">
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary w-full py-3">
                        Guardar récord
                    </button>
                </form>
            </div>
        </section>

        {{-- ── BEST RECORDS GRID ─────────────────────────────── --}}
        @php
            $featuredDistances = ['5K', '10K', 'Half Marathon', 'Marathon'];
            $distColors = [
                '5K'            => '#60a5fa',
                '10K'           => '#a78bfa',
                'Half Marathon' => '#4ade80',
                'Marathon'      => 'rgb(var(--color-primary))',
            ];
        @endphp

        @if($bestByDistance->isNotEmpty())
            <section>
                <h2 class="text-lg font-black text-white mb-4">Mejores marcas</h2>
                <div class="grid grid-cols-2 gap-3">
                    @foreach($featuredDistances as $dist)
                        @php $color = $distColors[$dist] ?? 'rgb(var(--color-primary))'; @endphp
                        <div class="card overflow-hidden">
                            <div class="h-1 w-full" style="background:{{ $color }}"></div>
                            <div class="p-4">
                                <p class="section-label mb-2">{{ $dist }}</p>
                                @if($bestByDistance->has($dist))
                                    @php
                                        $pr = $bestByDistance->get($dist);
                                        $secs = $pr->time_seconds;
                                        $h = intdiv($secs, 3600);
                                        $m = intdiv($secs % 3600, 60);
                                        $s = $secs % 60;
                                        $formatted = $h > 0
                                            ? sprintf('%d:%02d:%02d', $h, $m, $s)
                                            : sprintf('%d:%02d', $m, $s);
                                    @endphp
                                    <p class="text-2xl font-black tabnum leading-none" style="color:{{ $color }}">{{ $formatted }}</p>
                                    <p class="text-xs mt-1" style="color:rgba(255,255,255,0.35)">{{ $pr->date->format('d M Y') }}</p>
                                @else
                                    <p class="text-xl font-black leading-none" style="color:rgba(255,255,255,0.12)">—</p>
                                    <p class="text-xs mt-1" style="color:rgba(255,255,255,0.20)">Sin marca</p>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </section>
        @endif

        {{-- ── ALL RECORDS ─────────────────────────────────── --}}
        <section>
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-black text-white">Todos los récords</h2>
                <span class="text-xs font-bold" style="color:rgba(255,255,255,0.35)">{{ $records->count() }} registros</span>
            </div>

            @if($records->isEmpty())
                <div class="card px-5 py-12 text-center">
                    <p class="text-white font-black mb-1">Sin récords registrados</p>
                    <p class="text-sm" style="color:rgba(255,255,255,0.35)">Añade tu primer récord usando el formulario de arriba.</p>
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
                        <div class="card p-4 flex items-center gap-4">
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-2 mb-1">
                                    <span class="text-xs font-black text-white">{{ $record->distance_label }}</span>
                                    @if($isBest)
                                        <span class="text-[10px] font-black uppercase tracking-wider px-2 py-0.5 rounded-full" style="background:rgb(var(--color-primary) / 0.15);color:rgb(var(--color-primary))">PR</span>
                                    @endif
                                    @if($record->race_id)
                                        <span class="text-[10px] font-bold px-2 py-0.5 rounded-full" style="background:rgba(96,165,250,0.12);color:#60a5fa">De carrera</span>
                                    @endif
                                </div>
                                <p class="text-xl font-black tabnum text-white leading-none">{{ $formattedTime }}</p>
                                <div class="flex items-center gap-2 mt-1">
                                    <p class="text-xs" style="color:rgba(255,255,255,0.35)">{{ $record->date->format('d M Y') }}</p>
                                    @if($record->location)
                                        <span style="color:rgba(255,255,255,0.15)">·</span>
                                        <p class="text-xs truncate" style="color:rgba(255,255,255,0.35)">{{ $record->location }}</p>
                                    @endif
                                </div>
                            </div>
                            <form method="POST" action="{{ route('personal-records.destroy', $record) }}"
                                  onsubmit="return confirm('¿Eliminar este récord?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="w-9 h-9 flex items-center justify-center rounded-xl transition-colors flex-shrink-0"
                                        style="color:rgba(255,255,255,0.25)"
                                        onmouseover="this.style.background='rgba(248,113,113,0.15)';this.style.color='#f87171'"
                                        onmouseout="this.style.background='';this.style.color='rgba(255,255,255,0.25)'">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </button>
                            </form>
                        </div>
                    @endforeach
                </div>
            @endif
        </section>

    </main>
</x-app-layout>
