<x-app-layout>
    @section('page_title', 'Calendario')

    @php
        $prevMonth = $month === 1 ? ['year' => $year - 1, 'month' => 12] : ['year' => $year, 'month' => $month - 1];
        $nextMonth = $month === 12 ? ['year' => $year + 1, 'month' => 1] : ['year' => $year, 'month' => $month + 1];

        $monthNames = ['', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio',
                       'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];

        $daysInMonth = $startOfMonth->daysInMonth;
        $firstDayOfWeek = ($startOfMonth->dayOfWeek + 6) % 7;

        $racesByDay = $races->groupBy(fn($r) => $r->date->day);

        $statusColors = [
            'upcoming'  => 'rgb(var(--color-primary))',
            'completed' => '#4ade80',
            'dnf'       => '#f87171',
            'dns'       => '#6b7280',
        ];
    @endphp

    <main class="px-5 py-6 max-w-2xl mx-auto w-full space-y-6">

        {{-- Month header --}}
        <div class="flex items-center justify-between">
            <a href="{{ route('calendar.index', $prevMonth) }}"
               class="w-10 h-10 rounded-2xl flex items-center justify-center transition-colors"
               style="background:rgba(255,255,255,0.07);color:rgba(255,255,255,0.50)"
               onmouseover="this.style.background='rgba(255,255,255,0.12)';this.style.color='white'"
               onmouseout="this.style.background='rgba(255,255,255,0.07)';this.style.color='rgba(255,255,255,0.50)'">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/></svg>
            </a>
            <div class="text-center">
                <p class="text-xl font-black text-white">{{ $monthNames[$month] }}</p>
                <p class="text-sm font-bold" style="color:rgba(255,255,255,0.35)">{{ $year }}</p>
            </div>
            <a href="{{ route('calendar.index', $nextMonth) }}"
               class="w-10 h-10 rounded-2xl flex items-center justify-center transition-colors"
               style="background:rgba(255,255,255,0.07);color:rgba(255,255,255,0.50)"
               onmouseover="this.style.background='rgba(255,255,255,0.12)';this.style.color='white'"
               onmouseout="this.style.background='rgba(255,255,255,0.07)';this.style.color='rgba(255,255,255,0.50)'">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
            </a>
        </div>

        {{-- Calendar grid --}}
        <div class="card overflow-hidden">
            {{-- Days of week header --}}
            <div class="grid grid-cols-7" style="border-bottom:1px solid rgba(255,255,255,0.06)">
                @foreach(['L', 'M', 'X', 'J', 'V', 'S', 'D'] as $day)
                    <div class="py-2.5 text-center text-[11px] font-black uppercase tracking-wider" style="color:rgba(255,255,255,0.25)">{{ $day }}</div>
                @endforeach
            </div>

            {{-- Calendar days --}}
            <div class="grid grid-cols-7">
                @for($i = 0; $i < $firstDayOfWeek; $i++)
                    <div class="h-14" style="border-right:1px solid rgba(255,255,255,0.04);border-bottom:1px solid rgba(255,255,255,0.04)"></div>
                @endfor

                @for($day = 1; $day <= $daysInMonth; $day++)
                    @php
                        $isToday = now()->year === $year && now()->month === $month && now()->day === $day;
                        $dayRaces = $racesByDay->get($day, collect());
                        $col = ($firstDayOfWeek + $day - 1) % 7;
                    @endphp
                    <div class="h-14 p-1 relative" style="border-right:{{ $col === 6 ? '0' : '1px solid rgba(255,255,255,0.04)' }};border-bottom:1px solid rgba(255,255,255,0.04)">
                        <span class="text-xs block mb-0.5 {{ $isToday ? 'w-6 h-6 rounded-full flex items-center justify-center font-black text-black mx-auto' : 'text-center font-semibold' }}"
                              style="{{ $isToday ? 'background:rgb(var(--color-primary))' : 'color:rgba(255,255,255,0.50)' }}">
                            {{ $day }}
                        </span>
                        @foreach($dayRaces->take(2) as $race)
                            <div class="text-[9px] font-black truncate px-1 py-0.5 rounded-md mx-0.5 mb-0.5"
                                 style="background:{{ ($statusColors[$race->status] ?? '#6b7280') }}20;color:{{ $statusColors[$race->status] ?? '#6b7280' }}">
                                {{ $race->name }}
                            </div>
                        @endforeach
                        @if($dayRaces->count() > 2)
                            <span class="text-[9px] font-bold px-1" style="color:rgba(255,255,255,0.30)">+{{ $dayRaces->count() - 2 }}</span>
                        @endif
                    </div>
                @endfor
            </div>
        </div>

        {{-- Races this month --}}
        @if($races->isNotEmpty())
            <section>
                <h2 class="text-base font-black text-white mb-3">Este mes</h2>
                <div class="space-y-2">
                    @foreach($races->sortBy('date') as $race)
                        @php $accentColor = $statusColors[$race->status] ?? '#6b7280'; @endphp
                        <a href="{{ route('races.show', $race) }}"
                           class="flex items-center gap-3 card-interactive p-3">
                            <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0"
                                 style="background:{{ $accentColor }}18;border:1px solid {{ $accentColor }}30">
                                <span class="font-black text-sm" style="color:{{ $accentColor }}">{{ $race->date->format('d') }}</span>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="font-black text-white text-sm truncate">{{ $race->name }}</p>
                                <p class="text-xs mt-0.5" style="color:rgba(255,255,255,0.35)">{{ $race->formatted_distance }} km · {{ $race->date->format('d M') }}</p>
                            </div>
                            <span class="text-[11px] font-black px-2.5 py-1 rounded-full flex-shrink-0"
                                  style="background:{{ $accentColor }}18;color:{{ $accentColor }}">
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
                <h2 class="text-base font-black text-white mb-3">Próximas</h2>
                <div class="space-y-2">
                    @foreach($upcomingRaces as $race)
                        @php $daysLeft = now()->startOfDay()->diffInDays($race->date->startOfDay()); @endphp
                        <a href="{{ route('races.show', $race) }}"
                           class="flex items-center gap-3 card-interactive p-3">
                            <div class="w-10 text-center flex-shrink-0">
                                <p class="text-xl font-black tabnum leading-none text-primary">{{ $daysLeft }}</p>
                                <p class="text-[10px] font-bold leading-none mt-0.5" style="color:rgba(255,255,255,0.30)">días</p>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="font-black text-white text-sm truncate">{{ $race->name }}</p>
                                <p class="text-xs mt-0.5" style="color:rgba(255,255,255,0.35)">{{ $race->formatted_distance }} km · {{ $race->date->format('d M Y') }}</p>
                            </div>
                        </a>
                    @endforeach
                </div>
            </section>
        @endif

    </main>
</x-app-layout>
