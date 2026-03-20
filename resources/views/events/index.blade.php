<x-app-layout>
    @section('page_title', 'Carreras')
    @section('meta_description', 'Descubre y apúntate a carreras de running, trail y triatlón. Explora el calendario de eventos deportivos con filtros por tipo, categoría y fecha.')
    @section('robots', 'index, follow')
    @section('canonical', url('/events'))
    @section('og_title', 'Carreras — MyRaces')
    @section('og_description', 'Descubre y apúntate a carreras de running, trail y triatlón. Explora el calendario de eventos deportivos con filtros por tipo, categoría y fecha.')

    <div class="max-w-2xl mx-auto px-4 py-5 space-y-5"
         x-data="{
             view: localStorage.getItem('events-view') || 'list',
             showDates: {{ (request()->filled('date_from') || request()->filled('date_to')) ? 'true' : 'false' }}
         }"
         x-init="window.addEventListener('events-view-changed', e => view = e.detail)">

        {{-- Featured --}}
        @if($featured->isNotEmpty() && !request()->hasAny(['q','type','category','when','date_from','date_to']))
        <section>
            <p class="section-label mb-3">Destacadas</p>
            <div class="space-y-3">
                @foreach($featured as $event)
                    <a href="{{ route('events.show', $event) }}"
                       class="block relative overflow-hidden rounded-2xl transition-all active:scale-[0.99]"
                       style="border:1px solid rgba(255,255,255,0.09)">
                        @if($event->image)
                            <img src="{{ asset('storage/' . $event->image) }}" alt="{{ $event->name }}"
                                 class="w-full h-40 object-cover">
                            <div class="absolute inset-0" style="background:linear-gradient(to top,rgba(0,0,0,0.80) 0%,rgba(0,0,0,0.10) 60%)"></div>
                        @else
                            <div class="w-full h-32" style="background:linear-gradient(135deg,rgb(var(--color-primary) / 0.15),rgb(var(--color-primary) / 0.05))"></div>
                        @endif
                        <div class="absolute bottom-0 left-0 right-0 px-4 pb-3 pt-6">
                            <div class="flex items-end justify-between gap-2">
                                <div>
                                    <div class="flex items-center gap-2 mb-1">
                                        <span class="text-[10px] font-black px-2 py-0.5 rounded-full"
                                              style="background:{{ $event->statusColor() }}22;color:{{ $event->statusColor() }}">
                                            {{ $event->statusLabel() }}
                                        </span>
                                        <span class="text-[10px] font-bold" style="color:rgba(255,255,255,0.50)">{{ $event->category }}</span>
                                    </div>
                                    <p class="text-white font-black text-[15px] leading-tight">{{ $event->name }}</p>
                                    <p class="text-xs mt-0.5" style="color:rgba(255,255,255,0.55)">
                                        {{ $event->event_date->translatedFormat('d M Y') }} · {{ $event->location }}
                                    </p>
                                </div>
                                <div class="text-right flex-shrink-0">
                                    <p class="text-xs font-bold text-primary">{{ $event->attendees_count }} apuntados</p>
                                </div>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        </section>
        @endif

        {{-- Filters --}}
        <div class="space-y-3">
            {{-- Search + filter toggle --}}
            <form method="GET" id="filters-form" class="flex gap-2">
                <input type="text" name="q" value="{{ request('q') }}"
                       placeholder="Buscar carrera, ciudad..."
                       class="input-field text-sm py-2.5 flex-1">
                @if(request('type'))<input type="hidden" name="type" value="{{ request('type') }}">@endif
                @if(request('when') && !request()->filled('date_from') && !request()->filled('date_to'))<input type="hidden" name="when" value="{{ request('when') }}">@endif
                @if(request('date_from'))<input type="hidden" name="date_from" value="{{ request('date_from') }}">@endif
                @if(request('date_to'))<input type="hidden" name="date_to" value="{{ request('date_to') }}">@endif
                <button type="submit" class="btn btn-primary text-sm py-2 px-4 flex-shrink-0">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </button>
            </form>

            {{-- Type chips --}}
            <div class="flex gap-2 overflow-x-auto scrollbar-hide pb-0.5">
                @php
                    $typeFilters = ['' => 'Todos', 'road' => 'Asfalto', 'trail' => 'Trail', 'mountain' => 'Montaña', 'ultra' => 'Ultra', 'triathlon' => 'Triatlón'];
                    $currentType = request('type', '');
                @endphp
                @foreach($typeFilters as $val => $label)
                    <a href="{{ request()->fullUrlWithQuery(['type' => $val ?: null, 'page' => null]) }}"
                       class="flex-shrink-0 text-xs font-bold px-3.5 py-2 rounded-full transition-colors"
                       style="{{ $currentType === $val ? 'background:rgb(var(--color-primary));color:#000' : 'background:rgba(255,255,255,0.07);color:rgba(255,255,255,0.55)' }}">
                        {{ $label }}
                    </a>
                @endforeach
            </div>

            {{-- Date quick chips + range toggle --}}
            <div class="flex gap-2 overflow-x-auto scrollbar-hide pb-0.5 items-center">
                @php
                    $hasDateRange = request()->filled('date_from') || request()->filled('date_to');
                    $whenFilters = ['' => 'Cualquier fecha', 'month' => 'Este mes', '3months' => 'Próx. 3 meses'];
                    $currentWhen = $hasDateRange ? '' : request('when', '');
                @endphp
                @foreach($whenFilters as $val => $label)
                    <a href="{{ request()->fullUrlWithQuery(['when' => $val ?: null, 'date_from' => null, 'date_to' => null, 'page' => null]) }}"
                       class="flex-shrink-0 text-xs font-bold px-3.5 py-2 rounded-full transition-colors"
                       style="{{ (!$hasDateRange && $currentWhen === $val) ? 'background:rgba(96,165,250,0.20);color:#60a5fa' : 'background:rgba(255,255,255,0.07);color:rgba(255,255,255,0.55)' }}">
                        {{ $label }}
                    </a>
                @endforeach
                {{-- Range toggle button --}}
                <button type="button" @click="showDates = !showDates"
                        class="flex-shrink-0 flex items-center gap-1.5 text-xs font-bold px-3.5 py-2 rounded-full transition-colors"
                        :style="showDates || {{ $hasDateRange ? 'true' : 'false' }} ? 'background:rgba(96,165,250,0.20);color:#60a5fa' : 'background:rgba(255,255,255,0.07);color:rgba(255,255,255,0.55)'">
                    <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    Entre fechas
                </button>
            </div>

            {{-- Date range inputs --}}
            <form method="GET" x-show="showDates" x-cloak class="flex items-center gap-2">
                @if(request('q'))<input type="hidden" name="q" value="{{ request('q') }}">@endif
                @if(request('type'))<input type="hidden" name="type" value="{{ request('type') }}">@endif
                <input type="date" name="date_from" value="{{ request('date_from') }}"
                       class="input-field text-sm py-2 flex-1 min-w-0"
                       placeholder="Desde">
                <span class="text-white/30 text-xs flex-shrink-0">—</span>
                <input type="date" name="date_to" value="{{ request('date_to') }}"
                       class="input-field text-sm py-2 flex-1 min-w-0"
                       placeholder="Hasta">
                <button type="submit" class="btn btn-primary text-xs py-2 px-3 flex-shrink-0">
                    Aplicar
                </button>
                @if($hasDateRange)
                    <a href="{{ request()->fullUrlWithQuery(['date_from' => null, 'date_to' => null, 'page' => null]) }}"
                       class="flex-shrink-0 text-white/40 hover:text-white/70 transition-colors">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </a>
                @endif
            </form>
        </div>

        {{-- Results header --}}
        <div class="flex items-center justify-between">
            <p class="section-label mb-0">{{ $events->total() }} carreras</p>
            <div class="flex items-center gap-3">
                @if(request()->hasAny(['q','type','category','when','date_from','date_to']))
                    <a href="{{ route('events.index') }}" class="text-xs font-bold" style="color:rgba(255,255,255,0.35)">Limpiar</a>
                @endif
                <div class="flex rounded-xl overflow-hidden" style="border:1px solid rgba(255,255,255,0.08)">
                    <button @click="view='list'; localStorage.setItem('events-view','list')"
                            class="px-2.5 py-1.5 transition-colors"
                            :style="view==='list' ? 'background:rgb(var(--color-primary) / 0.15);color:rgb(var(--color-primary))' : 'background:transparent;color:rgba(255,255,255,0.35)'">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
                        </svg>
                    </button>
                    <button @click="view='card'; localStorage.setItem('events-view','card')"
                            class="px-2.5 py-1.5 transition-colors"
                            :style="view==='card' ? 'background:rgb(var(--color-primary) / 0.15);color:rgb(var(--color-primary))' : 'background:transparent;color:rgba(255,255,255,0.35)'">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 5a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1H5a1 1 0 01-1-1V5zm10 0a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1h-4a1 1 0 01-1-1V5zM4 15a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1H5a1 1 0 01-1-1v-4zm10 0a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1h-4a1 1 0 01-1-1v-4z"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        {{-- Results --}}
        <section>
            @if($events->isEmpty())
                <div class="card px-6 py-14 text-center space-y-3">
                    <div class="w-14 h-14 rounded-2xl bg-primary/10 border border-primary/20 flex items-center justify-center mx-auto">
                        <svg class="w-7 h-7 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 21v-4m0 0V5a2 2 0 012-2h6.5l1 1H21l-3 6 3 6h-8.5l-1-1H5a2 2 0 00-2 2zm9-13.5V9"/>
                        </svg>
                    </div>
                    <p class="text-white font-black text-lg">Sin resultados</p>
                    <p class="text-sm" style="color:rgba(255,255,255,0.40)">Prueba con otros filtros.</p>
                </div>
            @else

                {{-- LIST VIEW --}}
                <div x-show="view==='list'" class="space-y-3">
                    @if($feedAd)
                        <x-ad-card :ad="$feedAd" />
                    @endif
                    @foreach($events as $i => $event)
                        @php $attending = in_array($event->id, $attendingIds); @endphp

                        <a href="{{ route('events.show', $event) }}"
                           class="block relative overflow-hidden rounded-2xl transition-all active:scale-[0.99]"
                           style="background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.08)">
                            <div class="flex">
                                @if($event->image)
                                    <div class="w-24 flex-shrink-0">
                                        <img src="{{ asset('storage/' . $event->image) }}" alt=""
                                             class="w-full h-full object-cover rounded-l-2xl" style="min-height:96px">
                                    </div>
                                @else
                                    <div class="w-20 flex-shrink-0 rounded-l-2xl flex flex-col items-center justify-center gap-1"
                                         style="background:rgb(var(--color-primary) / 0.06);min-height:96px">
                                        <p class="text-xl font-black text-primary leading-none">{{ $event->event_date->format('d') }}</p>
                                        <p class="text-[10px] font-bold uppercase" style="color:rgba(255,255,255,0.35)">{{ $event->event_date->translatedFormat('M') }}</p>
                                        <p class="text-[9px] font-bold uppercase" style="color:rgba(255,255,255,0.25)">{{ $event->event_date->format('Y') }}</p>
                                    </div>
                                @endif
                                <div class="flex-1 min-w-0 px-4 py-3.5">
                                    <div class="flex items-start justify-between gap-2 mb-1.5">
                                        <div class="flex items-center gap-1.5 flex-wrap">
                                            <span class="text-[9px] font-black px-1.5 py-0.5 rounded-full"
                                                  style="background:{{ $event->statusColor() }}20;color:{{ $event->statusColor() }}">
                                                {{ $event->statusLabel() }}
                                            </span>
                                            <span class="text-[10px] font-bold" style="color:rgba(255,255,255,0.35)">{{ $event->category }}</span>
                                        </div>
                                        @if($attending)
                                            <span class="text-[9px] font-black px-2 py-0.5 rounded-full flex-shrink-0 bg-primary/20 text-primary">Apuntado</span>
                                        @endif
                                    </div>
                                    <p class="text-sm font-black text-white leading-tight">{{ $event->name }}</p>
                                    <p class="text-xs mt-1 truncate" style="color:rgba(255,255,255,0.40)">
                                        <svg class="inline w-3 h-3 mr-0.5 -mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        </svg>
                                        {{ $event->location }}{{ $event->province ? ', ' . $event->province : '' }}
                                    </p>
                                    <div class="flex items-center gap-3 mt-2">
                                        @if($event->distance_km)
                                            <span class="text-[10px] font-bold" style="color:rgba(255,255,255,0.35)">{{ $event->distance_km }} km</span>
                                        @endif
                                        @if($event->price !== null)
                                            <span class="text-[10px] font-bold" style="color:rgba(255,255,255,0.35)">
                                                {{ $event->price > 0 ? number_format($event->price, 0) . ' €' : 'Gratis' }}
                                            </span>
                                        @endif
                                        <span class="text-[10px]" style="color:rgba(255,255,255,0.25)">{{ $event->attendees_count }} apuntados</span>
                                    </div>
                                </div>
                            </div>
                        </a>

                    @endforeach
                </div>

                {{-- CARD VIEW --}}
                <div x-show="view==='card'" x-cloak>
                    <div class="grid grid-cols-2 gap-3">
                        @if($feedAd)
                            <div class="col-span-2">
                                <x-ad-card :ad="$feedAd" />
                            </div>
                        @endif
                        @foreach($events as $i => $event)
                            @php $attending = in_array($event->id, $attendingIds); @endphp

                            <a href="{{ route('events.show', $event) }}"
                               class="block relative overflow-hidden rounded-2xl transition-all active:scale-[0.99]"
                               style="background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.08)">
                                @if($event->image)
                                    <div class="relative">
                                        <img src="{{ asset('storage/' . $event->image) }}" alt="{{ $event->name }}"
                                             class="w-full object-cover" style="height:120px">
                                        <div class="absolute inset-0" style="background:linear-gradient(to top,rgba(0,0,0,0.60) 0%,transparent 60%)"></div>
                                        @if($attending)
                                            <span class="absolute top-2 right-2 text-[8px] font-black px-1.5 py-0.5 rounded-full bg-primary/90 text-black">Apuntado</span>
                                        @endif
                                    </div>
                                @else
                                    <div class="relative flex flex-col items-center justify-center gap-1" style="height:120px;background:rgb(var(--color-primary) / 0.06)">
                                        <p class="text-2xl font-black text-primary leading-none">{{ $event->event_date->format('d') }}</p>
                                        <p class="text-xs font-bold uppercase" style="color:rgba(255,255,255,0.40)">{{ $event->event_date->translatedFormat('M Y') }}</p>
                                        @if($attending)
                                            <span class="absolute top-2 right-2 text-[8px] font-black px-1.5 py-0.5 rounded-full bg-primary/90 text-black">Apuntado</span>
                                        @endif
                                    </div>
                                @endif
                                <div class="px-3 py-3">
                                    <div class="flex items-center gap-1.5 mb-1.5">
                                        <span class="text-[8px] font-black px-1.5 py-0.5 rounded-full"
                                              style="background:{{ $event->statusColor() }}20;color:{{ $event->statusColor() }}">
                                            {{ $event->statusLabel() }}
                                        </span>
                                        <span class="text-[9px] font-bold" style="color:rgba(255,255,255,0.35)">{{ $event->category }}</span>
                                    </div>
                                    <p class="text-xs font-black text-white leading-tight line-clamp-2">{{ $event->name }}</p>
                                    <p class="text-[10px] mt-1.5 truncate" style="color:rgba(255,255,255,0.40)">{{ $event->location }}</p>
                                    <div class="flex items-center justify-between mt-2">
                                        @if($event->distance_km)
                                            <span class="text-[10px] font-bold text-primary">{{ $event->distance_km }} km</span>
                                        @elseif($event->price !== null)
                                            <span class="text-[10px] font-bold" style="color:rgba(255,255,255,0.35)">
                                                {{ $event->price > 0 ? number_format($event->price, 0) . ' €' : 'Gratis' }}
                                            </span>
                                        @else
                                            <span></span>
                                        @endif
                                        <span class="text-[9px]" style="color:rgba(255,255,255,0.25)">{{ $event->attendees_count }}</span>
                                    </div>
                                </div>
                            </a>

                        @endforeach
                    </div>
                </div>

                @if($events->hasPages())
                    <div class="mt-4">{{ $events->withQueryString()->links() }}</div>
                @endif
            @endif
        </section>

    </div>
</x-app-layout>
