<x-app-layout>
    @section('page_title', __('races.dashboard'))

    <main class="flex-1 overflow-y-auto px-6 py-8 max-w-2xl mx-auto w-full">

        {{-- ── DAILY RECOMMENDATION (next race hero) ─────────── --}}
        <section class="mb-10">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-bold">{{ __('races.upcoming_races') }}</h2>
                <a href="{{ route('races.index', ['status' => 'upcoming']) }}" class="text-xs font-bold text-primary uppercase tracking-wider">Ver todas</a>
            </div>

            @if($upcomingRaces->isNotEmpty())
                @php $nextRace = $upcomingRaces->first(); $daysLeft = now()->startOfDay()->diffInDays($nextRace->date->startOfDay()); @endphp
                <a href="{{ route('races.show', $nextRace) }}"
                   class="relative group overflow-hidden rounded-xl shadow-xl block" style="aspect-ratio:16/9">
                    {{-- Background --}}
                    <div class="absolute inset-0" style="background: linear-gradient(135deg, #221610 0%, #7c2d12 40%, #ec5b13 100%)"></div>
                    {{-- Pattern overlay --}}
                    <div class="absolute inset-0 opacity-10" style="background-image: radial-gradient(circle at 20% 80%, white 1px, transparent 1px), radial-gradient(circle at 80% 20%, white 1px, transparent 1px); background-size: 40px 40px"></div>
                    {{-- Gradient overlay from bottom --}}
                    <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent"></div>

                    <div class="absolute bottom-0 left-0 p-5 w-full">
                        <p class="text-primary text-xs font-bold uppercase mb-1 tracking-wider">
                            {{ $daysLeft === 0 ? '¡Hoy es el día!' : ($daysLeft === 1 ? 'Mañana' : "En {$daysLeft} días") }}
                        </p>
                        <h3 class="text-white text-2xl font-bold mb-3 leading-snug">{{ $nextRace->name }}</h3>
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3 text-white/70 text-sm">
                                <span class="flex items-center gap-1.5">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                    {{ $nextRace->formatted_distance }} {{ $nextRace->distance_unit }}
                                </span>
                                <span class="flex items-center gap-1.5">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                                    {{ __('races.modalities.' . $nextRace->modality) }}
                                </span>
                            </div>
                            <span class="bg-primary hover:bg-orange-600 text-white rounded-full px-5 py-2 font-bold text-sm transition-all active:scale-95" style="box-shadow: 0 4px 12px rgba(236,91,19,0.4)">
                                Ver carrera
                            </span>
                        </div>
                    </div>
                </a>
            @else
                <div class="relative overflow-hidden rounded-xl shadow-xl" style="aspect-ratio:16/9; background: linear-gradient(135deg, #221610 0%, #3d1f0f 100%)">
                    <div class="absolute inset-0 flex flex-col items-center justify-center text-center p-6">
                        <div class="w-16 h-16 rounded-full bg-primary/20 flex items-center justify-center mb-4">
                            <svg class="w-8 h-8 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4v16m8-8H4"/>
                            </svg>
                        </div>
                        <p class="text-white font-bold text-lg mb-1">Sin carreras próximas</p>
                        <p class="text-white/40 text-sm mb-4">Añade tu primera carrera</p>
                        <a href="{{ route('races.create') }}" class="bg-primary text-white rounded-full px-6 py-2.5 font-bold text-sm" style="box-shadow: 0 4px 12px rgba(236,91,19,0.4)">
                            {{ __('races.add_race') }}
                        </a>
                    </div>
                </div>
            @endif
        </section>

        {{-- ── YEAR STATS (2-col grid like "Explore Runs") ────── --}}
        <section class="mb-10">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-bold">{{ now()->year }}</h2>
                <a href="{{ route('stats.index') }}" class="text-primary text-sm font-semibold">Estadísticas</a>
            </div>
            <div class="grid grid-cols-2 gap-3">
                <div class="bg-white p-4 rounded-xl border border-slate-100 shadow-card">
                    <div class="w-10 h-10 rounded-lg flex items-center justify-center mb-3 bg-primary/10">
                        <svg class="w-5 h-5 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                    </div>
                    <p class="text-2xl font-bold tabnum text-slate-900 leading-none">{{ $stats['year_races'] }}</p>
                    <p class="text-xs text-slate-500 mt-1">Carreras completadas</p>
                </div>
                <div class="bg-white p-4 rounded-xl border border-slate-100 shadow-card">
                    <div class="w-10 h-10 rounded-lg flex items-center justify-center mb-3 bg-blue-500/10">
                        <svg class="w-5 h-5 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                        </svg>
                    </div>
                    <p class="text-2xl font-bold tabnum text-slate-900 leading-none">{{ number_format((float) $stats['total_km'], 0) }}</p>
                    <p class="text-xs text-slate-500 mt-1">Kilómetros totales</p>
                </div>
                <div class="bg-white p-4 rounded-xl border border-slate-100 shadow-card">
                    <div class="w-10 h-10 rounded-lg flex items-center justify-center mb-3 bg-green-500/10">
                        <svg class="w-5 h-5 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <p class="text-2xl font-bold tabnum text-slate-900 leading-none">{{ $stats['completed_races'] ?? $stats['year_races'] }}</p>
                    <p class="text-xs text-slate-500 mt-1">Finalizadas</p>
                </div>
                <div class="bg-white p-4 rounded-xl border border-slate-100 shadow-card">
                    <div class="w-10 h-10 rounded-lg flex items-center justify-center mb-3 bg-amber-500/10">
                        <svg class="w-5 h-5 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <p class="text-2xl font-bold tabnum text-slate-900 leading-none">{{ number_format((float) $stats['total_spent'], 0) }}<span class="text-base text-slate-400">€</span></p>
                    <p class="text-xs text-slate-500 mt-1">Invertido en inscripciones</p>
                </div>
            </div>
        </section>

        {{-- ── RECENT RACES (list like "Training Collections") ── --}}
        @if($recentRaces->isNotEmpty())
            <section class="mb-20">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-bold">{{ __('races.recent_races') }}</h2>
                    <a href="{{ route('races.index') }}" class="text-primary text-sm font-semibold">Ver todas</a>
                </div>
                <div class="space-y-3">
                    @foreach($recentRaces as $race)
                        @include('races.partials.race-card', ['race' => $race])
                    @endforeach
                </div>
            </section>
        @elseif($upcomingRaces->isEmpty())
            <section class="mb-20">
                <div class="flex flex-col items-center justify-center py-10 text-center">
                    <p class="text-slate-800 font-bold text-lg">{{ __('races.no_races') }}</p>
                    <p class="text-slate-400 text-sm mt-2 mb-5">Registra tu primera carrera para empezar.</p>
                    <a href="{{ route('races.create') }}" class="btn btn-primary px-6">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                        </svg>
                        {{ __('races.add_first') }}
                    </a>
                </div>
            </section>
        @endif

    </main>
</x-app-layout>
