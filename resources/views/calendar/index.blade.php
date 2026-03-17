<x-app-layout>
    @section('page_title', 'Calendario')

    @php
        use Carbon\Carbon;
        $prevMonth = $month === 1 ? ['year' => $year - 1, 'month' => 12] : ['year' => $year, 'month' => $month - 1];
        $nextMonth = $month === 12 ? ['year' => $year + 1, 'month' => 1] : ['year' => $year, 'month' => $month + 1];

        $monthNames = ['', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio',
                       'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];

        $daysInMonth = $startOfMonth->daysInMonth;
        $firstDayOfWeek = ($startOfMonth->dayOfWeek + 6) % 7; // Monday = 0

        $racesByDay = $races->groupBy(fn($r) => $r->date->day);

        $statusColors = [
            'upcoming' => 'bg-primary',
            'completed' => 'bg-green-500',
            'dnf' => 'bg-amber-500',
            'dns' => 'bg-slate-400',
        ];
    @endphp

    <main class="flex-1 overflow-y-auto px-4 py-6 max-w-2xl mx-auto w-full pb-[76px]">

        {{-- Month header --}}
        <div class="flex items-center justify-between mb-6">
            <a href="{{ route('calendar.index', $prevMonth) }}"
               class="w-10 h-10 rounded-xl bg-white border border-slate-200 flex items-center justify-center text-slate-500 hover:text-slate-900 transition-colors">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            </a>
            <div class="text-center">
                <p class="text-xl font-bold text-slate-900">{{ $monthNames[$month] }}</p>
                <p class="text-sm text-slate-400">{{ $year }}</p>
            </div>
            <a href="{{ route('calendar.index', $nextMonth) }}"
               class="w-10 h-10 rounded-xl bg-white border border-slate-200 flex items-center justify-center text-slate-500 hover:text-slate-900 transition-colors">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </a>
        </div>

        {{-- Calendar grid --}}
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden mb-6">
            {{-- Days of week header --}}
            <div class="grid grid-cols-7 border-b border-slate-100">
                @foreach(['L', 'M', 'X', 'J', 'V', 'S', 'D'] as $day)
                    <div class="py-2 text-center text-[11px] font-semibold text-slate-400">{{ $day }}</div>
                @endforeach
            </div>

            {{-- Calendar days --}}
            <div class="grid grid-cols-7">
                {{-- Empty cells before first day --}}
                @for($i = 0; $i < $firstDayOfWeek; $i++)
                    <div class="h-14 border-r border-b border-slate-50"></div>
                @endfor

                @for($day = 1; $day <= $daysInMonth; $day++)
                    @php
                        $isToday = now()->year === $year && now()->month === $month && now()->day === $day;
                        $dayRaces = $racesByDay->get($day, collect());
                        $col = ($firstDayOfWeek + $day - 1) % 7;
                    @endphp
                    <div class="h-14 p-1 border-r border-b border-slate-50 {{ $col === 6 ? 'border-r-0' : '' }} relative">
                        <span class="text-xs {{ $isToday ? 'bg-primary text-white rounded-full w-6 h-6 flex items-center justify-center font-bold' : 'text-slate-600 font-medium' }} block mb-0.5">
                            {{ $day }}
                        </span>
                        @foreach($dayRaces->take(2) as $race)
                            <div class="text-[9px] font-medium truncate px-1 py-0.5 rounded {{ $statusColors[$race->status] ?? 'bg-slate-400' }} text-white">
                                {{ $race->name }}
                            </div>
                        @endforeach
                        @if($dayRaces->count() > 2)
                            <span class="text-[9px] text-slate-400">+{{ $dayRaces->count() - 2 }}</span>
                        @endif
                    </div>
                @endfor
            </div>
        </div>

        {{-- Races this month --}}
        @if($races->isNotEmpty())
            <section class="mb-6">
                <h2 class="text-base font-bold text-slate-900 mb-3">Este mes</h2>
                <div class="space-y-2">
                    @foreach($races->sortBy('date') as $race)
                        <a href="{{ route('races.show', $race) }}"
                           class="flex items-center gap-3 bg-white rounded-xl border border-slate-100 p-3 hover:shadow-sm transition-shadow">
                            <div class="w-10 h-10 rounded-lg bg-primary/10 flex items-center justify-center flex-shrink-0">
                                <span class="text-primary font-bold text-sm">{{ $race->date->format('d') }}</span>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="font-semibold text-slate-900 text-sm truncate">{{ $race->name }}</p>
                                <p class="text-xs text-slate-400">{{ $race->formatted_distance }} km · {{ $race->date->format('d M') }}</p>
                            </div>
                            <span class="text-[11px] font-semibold px-2 py-0.5 rounded-full
                                {{ $race->status === 'upcoming' ? 'bg-primary/10 text-primary' : ($race->status === 'completed' ? 'bg-green-100 text-green-700' : 'bg-slate-100 text-slate-500') }}">
                                {{ ucfirst($race->status) }}
                            </span>
                        </a>
                    @endforeach
                </div>
            </section>
        @endif

        {{-- Upcoming races --}}
        @if($upcomingRaces->isNotEmpty())
            <section>
                <h2 class="text-base font-bold text-slate-900 mb-3">Próximas</h2>
                <div class="space-y-2">
                    @foreach($upcomingRaces as $race)
                        @php $daysLeft = now()->startOfDay()->diffInDays($race->date->startOfDay()); @endphp
                        <a href="{{ route('races.show', $race) }}"
                           class="flex items-center gap-3 bg-white rounded-xl border border-slate-100 p-3 hover:shadow-sm transition-shadow">
                            <div class="w-10 text-center flex-shrink-0">
                                <p class="text-lg font-bold text-primary leading-none">{{ $daysLeft }}</p>
                                <p class="text-[10px] text-slate-400 leading-none">días</p>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="font-semibold text-slate-900 text-sm truncate">{{ $race->name }}</p>
                                <p class="text-xs text-slate-400">{{ $race->formatted_distance }} km · {{ $race->date->format('d M Y') }}</p>
                            </div>
                        </a>
                    @endforeach
                </div>
            </section>
        @endif

    </main>
</x-app-layout>
