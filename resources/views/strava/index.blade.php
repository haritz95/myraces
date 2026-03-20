<x-app-layout>
    @section('page_title', 'Importar de Strava')
    @section('back_url', route('races.index'))

    <div class="max-w-lg mx-auto px-4 py-6 space-y-4">

        {{-- Header --}}
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0" style="background:#FC4C02">
                <svg class="w-5 h-5 text-white" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M15.387 17.944l-2.089-4.116h-3.065L15.387 24l5.15-10.172h-3.066m-7.008-5.599l2.836 5.599h4.172L10.463 0l-7 13.828h4.169"/>
                </svg>
            </div>
            <div>
                <h1 class="text-base font-black text-white">Importar desde Strava</h1>
                <p class="text-xs" style="color:rgba(255,255,255,0.40)">Selecciona las actividades que quieres añadir</p>
            </div>
        </div>

        @if($activities->isEmpty())
            <div class="card px-6 py-12 text-center space-y-2">
                <p class="text-white font-bold">No hay actividades nuevas</p>
                <p class="text-sm" style="color:rgba(255,255,255,0.40)">Todas tus carreras de Strava ya están importadas o no tienes actividades en esta página.</p>
                @if($page > 1)
                    <a href="{{ route('strava.import', ['page' => $page - 1]) }}" class="btn btn-secondary inline-block mt-2">Página anterior</a>
                @endif
            </div>
        @else
            <form method="POST" action="{{ route('strava.import.store') }}" id="import-form">
                @csrf

                <div class="space-y-2">
                    @foreach($activities as $activity)
                        @php
                            $stravaId = $activity['id'];
                            $alreadyImported = in_array($stravaId, $imported);
                            $distanceKm = round($activity['distance'] / 1000, 2);
                            $minutes = intdiv($activity['moving_time'] ?? 0, 60);
                            $seconds = ($activity['moving_time'] ?? 0) % 60;
                            $date = \Carbon\Carbon::parse($activity['start_date_local']);
                        @endphp

                        <label class="block relative overflow-hidden rounded-2xl cursor-pointer transition-all
                                      {{ $alreadyImported ? 'opacity-40 pointer-events-none' : '' }}"
                               style="background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.08)">

                            <input type="checkbox" name="activities[{{ $loop->index }}][id]"
                                   value="{{ $stravaId }}"
                                   {{ $alreadyImported ? 'disabled' : 'checked' }}
                                   class="peer sr-only"
                                   onchange="syncFields(this, {{ $loop->index }})">

                            {{-- Hidden fields for all activity data --}}
                            <input type="hidden" name="activities[{{ $loop->index }}][name]"           value="{{ $activity['name'] }}">
                            <input type="hidden" name="activities[{{ $loop->index }}][distance]"       value="{{ $activity['distance'] }}">
                            <input type="hidden" name="activities[{{ $loop->index }}][moving_time]"    value="{{ $activity['moving_time'] ?? 0 }}">
                            <input type="hidden" name="activities[{{ $loop->index }}][start_date_local]" value="{{ $activity['start_date_local'] ?? '' }}">
                            <input type="hidden" name="activities[{{ $loop->index }}][sport_type]"     value="{{ $activity['sport_type'] ?? $activity['type'] ?? '' }}">
                            <input type="hidden" name="activities[{{ $loop->index }}][location_city]"  value="{{ $activity['location_city'] ?? '' }}">
                            <input type="hidden" name="activities[{{ $loop->index }}][location_country]" value="{{ $activity['location_country'] ?? '' }}">
                            <input type="hidden" name="activities[{{ $loop->index }}][total_elevation_gain]" value="{{ $activity['total_elevation_gain'] ?? 0 }}">
                            <input type="hidden" name="activities[{{ $loop->index }}][average_heartrate]" value="{{ $activity['average_heartrate'] ?? 0 }}">
                            <input type="hidden" name="activities[{{ $loop->index }}][description]"    value="{{ $activity['description'] ?? '' }}">

                            <div class="flex items-center gap-3 px-4 py-3.5">

                                {{-- Check indicator --}}
                                <div class="w-5 h-5 rounded-md border flex-shrink-0 flex items-center justify-center transition-all
                                            peer-checked:bg-primary peer-checked:border-primary"
                                     style="border-color:rgba(255,255,255,0.20)"
                                     id="check-{{ $loop->index }}">
                                    <svg class="w-3 h-3 text-black hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3" id="checkmark-{{ $loop->index }}">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                    </svg>
                                </div>

                                {{-- Date --}}
                                <div class="w-10 text-center flex-shrink-0">
                                    <p class="text-sm font-black text-white leading-none">{{ $date->format('d') }}</p>
                                    <p class="text-[10px] uppercase" style="color:rgba(255,255,255,0.30)">{{ $date->translatedFormat('M') }}</p>
                                </div>

                                {{-- Info --}}
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-bold text-white truncate">{{ $activity['name'] }}</p>
                                    <p class="text-xs mt-0.5" style="color:rgba(255,255,255,0.40)">
                                        {{ $distanceKm }} km
                                        @if($activity['moving_time'] ?? 0)
                                            · {{ $minutes }}:{{ str_pad($seconds, 2, '0', STR_PAD_LEFT) }}
                                        @endif
                                        @if(!empty($activity['total_elevation_gain']))
                                            · +{{ round($activity['total_elevation_gain']) }}m
                                        @endif
                                    </p>
                                </div>

                                @if($alreadyImported)
                                    <span class="text-[10px] font-bold px-2 py-0.5 rounded-full flex-shrink-0" style="background:rgba(255,255,255,0.08);color:rgba(255,255,255,0.35)">Importada</span>
                                @endif
                            </div>
                        </label>
                    @endforeach
                </div>

                {{-- Actions --}}
                <div class="flex gap-3 mt-4">
                    <button type="submit" class="btn btn-primary flex-1">
                        Importar seleccionadas
                    </button>
                </div>

                {{-- Pagination --}}
                <div class="flex justify-between mt-3">
                    @if($page > 1)
                        <a href="{{ route('strava.import', ['page' => $page - 1]) }}"
                           class="text-xs font-bold" style="color:rgba(255,255,255,0.40)">Anterior</a>
                    @else
                        <span></span>
                    @endif
                    <a href="{{ route('strava.import', ['page' => $page + 1]) }}"
                       class="text-xs font-bold" style="color:rgba(255,255,255,0.40)">Siguiente</a>
                </div>
            </form>
        @endif

    </div>

    <script>
    // Sync the visual checkbox state and control which fields are submitted
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('input[type=checkbox]').forEach(function (cb) {
            updateVisual(cb);
            cb.addEventListener('change', function () { updateVisual(this); });
        });
    });

    function updateVisual(cb) {
        var idx = cb.name.match(/\[(\d+)\]/)[1];
        var box = document.getElementById('check-' + idx);
        var mark = document.getElementById('checkmark-' + idx);
        if (cb.checked) {
            box.style.background = 'rgb(var(--color-primary))';
            box.style.borderColor = 'rgb(var(--color-primary))';
            mark.classList.remove('hidden');
        } else {
            box.style.background = '';
            box.style.borderColor = 'rgba(255,255,255,0.20)';
            mark.classList.add('hidden');
        }
    }

    document.getElementById('import-form')?.addEventListener('submit', function (e) {
        var anyChecked = Array.from(this.querySelectorAll('input[type=checkbox]')).some(cb => cb.checked);
        if (!anyChecked) {
            e.preventDefault();
            alert('Selecciona al menos una actividad para importar.');
        }
    });
    </script>
</x-app-layout>
