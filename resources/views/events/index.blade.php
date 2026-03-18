<x-app-layout>
    @section('page_title', 'Carreras')

    <div class="max-w-2xl mx-auto px-4 py-6 space-y-5">

        {{-- Featured --}}
        @if($featured->isNotEmpty() && !request()->hasAny(['q','type','category','when']))
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
                            <div class="w-full h-32" style="background:linear-gradient(135deg,rgba(200,250,95,0.15),rgba(200,250,95,0.05))"></div>
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
            <form method="GET" class="flex gap-2">
                <input type="text" name="q" value="{{ request('q') }}"
                       placeholder="Buscar carrera, ciudad..."
                       class="input-field text-sm py-2.5 flex-1">
                @foreach(['type','category','when'] as $k)
                    @if(request($k))<input type="hidden" name="{{ $k }}" value="{{ request($k) }}">@endif
                @endforeach
                <button type="submit" class="btn btn-primary text-sm py-2 px-4">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </button>
            </form>

            {{-- Filter chips --}}
            <div class="flex gap-2 overflow-x-auto scrollbar-hide pb-0.5">
                @php
                    $typeFilters = ['' => 'Todos', 'road' => 'Asfalto', 'trail' => 'Trail', 'mountain' => 'Montaña', 'ultra' => 'Ultra', 'triathlon' => 'Triatlón'];
                    $currentType = request('type', '');
                @endphp
                @foreach($typeFilters as $val => $label)
                    <a href="{{ request()->fullUrlWithQuery(['type' => $val ?: null, 'page' => null]) }}"
                       class="flex-shrink-0 text-xs font-bold px-3.5 py-2 rounded-full transition-colors"
                       style="{{ $currentType === $val ? 'background:#C8FA5F;color:#000' : 'background:rgba(255,255,255,0.07);color:rgba(255,255,255,0.55)' }}">
                        {{ $label }}
                    </a>
                @endforeach
            </div>

            <div class="flex gap-2 overflow-x-auto scrollbar-hide pb-0.5">
                @php
                    $whenFilters = ['' => 'Cualquier fecha', 'month' => 'Este mes', '3months' => 'Próximos 3 meses'];
                    $currentWhen = request('when', '');
                @endphp
                @foreach($whenFilters as $val => $label)
                    <a href="{{ request()->fullUrlWithQuery(['when' => $val ?: null, 'page' => null]) }}"
                       class="flex-shrink-0 text-xs font-bold px-3.5 py-2 rounded-full transition-colors"
                       style="{{ $currentWhen === $val ? 'background:rgba(96,165,250,0.20);color:#60a5fa' : 'background:rgba(255,255,255,0.07);color:rgba(255,255,255,0.55)' }}">
                        {{ $label }}
                    </a>
                @endforeach
            </div>
        </div>

        {{-- Results --}}
        <section>
            <div class="flex items-center justify-between mb-3">
                <p class="section-label mb-0">{{ $events->total() }} carreras</p>
                @if(request()->hasAny(['q','type','category','when']))
                    <a href="{{ route('events.index') }}" class="text-xs font-bold" style="color:rgba(255,255,255,0.35)">Limpiar filtros</a>
                @endif
            </div>

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
                <div class="space-y-3">
                    @foreach($events as $event)
                        @php $attending = in_array($event->id, $attendingIds); @endphp
                        <a href="{{ route('events.show', $event) }}"
                           class="block relative overflow-hidden rounded-2xl transition-all active:scale-[0.99]"
                           style="background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.08)">

                            <div class="flex gap-0">
                                {{-- Image --}}
                                @if($event->image)
                                    <div class="w-24 flex-shrink-0">
                                        <img src="{{ asset('storage/' . $event->image) }}" alt=""
                                             class="w-full h-full object-cover rounded-l-2xl" style="min-height:96px">
                                    </div>
                                @else
                                    <div class="w-20 flex-shrink-0 rounded-l-2xl flex flex-col items-center justify-center gap-1"
                                         style="background:rgba(200,250,95,0.06);min-height:96px">
                                        <p class="text-xl font-black text-primary leading-none">{{ $event->event_date->format('d') }}</p>
                                        <p class="text-[10px] font-bold uppercase" style="color:rgba(255,255,255,0.35)">{{ $event->event_date->translatedFormat('M') }}</p>
                                        <p class="text-[9px] font-bold uppercase" style="color:rgba(255,255,255,0.25)">{{ $event->event_date->format('Y') }}</p>
                                    </div>
                                @endif

                                {{-- Info --}}
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
                                            <span class="text-[9px] font-black px-2 py-0.5 rounded-full flex-shrink-0 bg-primary/20 text-primary">✓ Apuntado</span>
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
                                                {{ $event->price > 0 ? '€' . number_format($event->price, 0) : 'Gratis' }}
                                            </span>
                                        @endif
                                        <span class="text-[10px]" style="color:rgba(255,255,255,0.25)">{{ $event->attendees_count }} apuntados</span>
                                    </div>
                                </div>
                            </div>

                            @if($event->image)
                                <div class="px-4 pb-3 pt-0">
                                    <p class="text-xs font-bold" style="color:rgba(255,255,255,0.40)">
                                        {{ $event->event_date->translatedFormat('d M Y') }}
                                    </p>
                                </div>
                            @endif
                        </a>
                    @endforeach
                </div>

                @if($events->hasPages())
                    <div class="mt-4">{{ $events->withQueryString()->links() }}</div>
                @endif
            @endif
        </section>

    </div>
</x-app-layout>
