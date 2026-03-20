<x-app-layout>
    @section('page_title', __('races.dashboard'))

    <main class="px-5 py-6 max-w-2xl mx-auto w-full space-y-8">

        {{-- ── NEXT RACE HERO ──────────────────────────────────── --}}
        <section>
            @if($upcomingRaces->isNotEmpty())
                @php $nextRace = $upcomingRaces->first(); $daysLeft = now()->startOfDay()->diffInDays($nextRace->date->startOfDay()); @endphp
                <a href="{{ route('races.show', $nextRace) }}"
                   class="relative group overflow-hidden rounded-3xl block" style="aspect-ratio:16/9">
                    <div class="absolute inset-0" style="background:linear-gradient(135deg,#0a0a0a 0%,#1a1a0a 40%,#2d3a00 100%)"></div>
                    <div class="absolute inset-0 opacity-[0.07]" style="background-image:radial-gradient(circle at 20% 80%, rgb(var(--color-primary)) 1px, transparent 1px),radial-gradient(circle at 80% 20%, rgb(var(--color-primary)) 1px, transparent 1px);background-size:40px 40px"></div>
                    <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/20 to-transparent"></div>
                    <div class="absolute bottom-0 left-0 p-6 w-full">
                        <p class="text-primary text-[11px] font-black uppercase tracking-[0.2em] mb-2">
                            {{ $daysLeft === 0 ? '¡Hoy es el día!' : ($daysLeft === 1 ? 'Mañana' : "En {$daysLeft} días") }}
                        </p>
                        <h3 class="text-white text-2xl font-black mb-4 leading-tight">{{ $nextRace->name }}</h3>
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-4" style="color:rgba(255,255,255,0.50)">
                                <span class="text-sm font-semibold">{{ $nextRace->formatted_distance }} {{ $nextRace->distance_unit }}</span>
                                <span class="text-sm font-semibold">{{ __('races.modalities.' . $nextRace->modality) }}</span>
                            </div>
                            <span class="bg-primary text-black rounded-full px-5 py-2 font-black text-sm" style="box-shadow:0 4px 12px rgb(var(--color-primary) / 0.40)">
                                Ver →
                            </span>
                        </div>
                    </div>
                </a>
            @else
                <div class="relative overflow-hidden rounded-3xl flex flex-col items-center justify-center text-center p-10" style="aspect-ratio:16/9;background:linear-gradient(135deg,#0f0f0f 0%,#161a0a 100%);border:1px solid rgba(255,255,255,0.06)">
                    <div class="w-16 h-16 rounded-full bg-primary/10 border border-primary/20 flex items-center justify-center mb-4">
                        <svg class="w-8 h-8 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4v16m8-8H4"/>
                        </svg>
                    </div>
                    <p class="text-white font-black text-lg mb-1">Sin carreras próximas</p>
                    <p class="text-sm mb-5" style="color:rgba(255,255,255,0.35)">Añade tu primera carrera</p>
                    <a href="{{ route('races.create') }}" class="btn btn-primary px-6">
                        {{ __('races.add_race') }}
                    </a>
                </div>
            @endif
        </section>

        {{-- ── YEAR STATS ───────────────────────────────────────── --}}
        <section>
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-black text-white">{{ now()->year }}</h2>
                <a href="{{ route('stats.index') }}" class="text-primary text-xs font-black uppercase tracking-widest">Stats →</a>
            </div>
            <div class="grid grid-cols-2 gap-3">
                @php
                    $statCards = [
                        ['value' => $stats['year_races'],                              'label' => 'Carreras',       'accent' => 'rgb(var(--color-primary))'],
                        ['value' => number_format((float) $stats['total_km'], 0).' km','label' => 'Kilómetros',     'accent' => '#60a5fa'],
                        ['value' => $stats['completed_races'] ?? $stats['year_races'], 'label' => 'Finalizadas',    'accent' => '#4ade80'],
                        ['value' => number_format((float) $stats['total_spent'], 0).'€','label' => 'Invertido',     'accent' => '#fb923c'],
                    ];
                @endphp
                @foreach($statCards as $s)
                    <div class="card p-5">
                        <p class="text-3xl font-black tabnum text-white leading-none mb-2">{{ $s['value'] }}</p>
                        <p class="text-xs font-bold uppercase tracking-widest" style="color:rgba(255,255,255,0.35)">{{ $s['label'] }}</p>
                        <div class="mt-3 h-0.5 rounded-full w-8" style="background:{{ $s['accent'] }}"></div>
                    </div>
                @endforeach
            </div>
        </section>

        {{-- ── RECENT RACES ─────────────────────────────────────── --}}
        @if($recentRaces->isNotEmpty())
            <section>
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-black text-white">{{ __('races.recent_races') }}</h2>
                    <a href="{{ route('races.index') }}" class="text-primary text-xs font-black uppercase tracking-widest">Ver todas →</a>
                </div>
                <div class="space-y-2">
                    @foreach($recentRaces as $race)
                        @include('races.partials.race-card', ['race' => $race])
                    @endforeach
                </div>
            </section>
        @elseif($upcomingRaces->isEmpty())
            <section>
                <div class="flex flex-col items-center justify-center py-12 text-center">
                    <p class="text-white font-black text-lg">{{ __('races.no_races') }}</p>
                    <p class="text-sm mt-2 mb-6" style="color:rgba(255,255,255,0.35)">Registra tu primera carrera para empezar.</p>
                    <a href="{{ route('races.create') }}" class="btn btn-primary px-8">
                        {{ __('races.add_first') }}
                    </a>
                </div>
            </section>
        @endif

    </main>
</x-app-layout>
