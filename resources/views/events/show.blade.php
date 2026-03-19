<x-app-layout>
    @section('page_title', $raceEvent->name)
    @section('back_url', route('events.index'))

    <div class="max-w-2xl mx-auto px-4 py-6 space-y-5">

        {{-- Hero --}}
        <div class="relative overflow-hidden rounded-2xl" style="border:1px solid rgba(255,255,255,0.09)">
            @if($raceEvent->imageSource())
                <img src="{{ $raceEvent->imageSource() }}" alt="{{ $raceEvent->name }}"
                     class="w-full object-cover" style="max-height:260px">
                <div class="absolute inset-0" style="background:linear-gradient(to top,rgba(0,0,0,0.85) 0%,rgba(0,0,0,0.05) 55%)"></div>
                <div class="absolute bottom-0 left-0 right-0 px-5 pb-5">
                    <div class="flex items-center gap-2 mb-2">
                        <span class="text-[10px] font-black px-2.5 py-1 rounded-full"
                              style="background:{{ $raceEvent->statusColor() }}22;color:{{ $raceEvent->statusColor() }}">
                            {{ $raceEvent->statusLabel() }}
                        </span>
                        <span class="text-[10px] font-bold" style="color:rgba(255,255,255,0.55)">{{ $raceEvent->raceTypeLabel() }}</span>
                        @if($raceEvent->category)
                            <span class="text-[10px] font-bold" style="color:rgba(255,255,255,0.40)">· {{ $raceEvent->category }}</span>
                        @endif
                    </div>
                    <h1 class="text-white font-black text-xl leading-tight">{{ $raceEvent->name }}</h1>
                </div>
            @else
                <div class="px-5 py-6" style="background:rgba(255,255,255,0.04)">
                    <div class="flex items-center gap-2 mb-2">
                        <span class="text-[10px] font-black px-2.5 py-1 rounded-full"
                              style="background:{{ $raceEvent->statusColor() }}22;color:{{ $raceEvent->statusColor() }}">
                            {{ $raceEvent->statusLabel() }}
                        </span>
                        <span class="text-[10px] font-bold" style="color:rgba(255,255,255,0.40)">
                            {{ $raceEvent->raceTypeLabel() }}{{ $raceEvent->category ? ' · ' . $raceEvent->category : '' }}
                        </span>
                    </div>
                    <h1 class="text-white font-black text-xl leading-tight">{{ $raceEvent->name }}</h1>
                </div>
            @endif
        </div>

        {{-- Attend CTA --}}
        @if(!$raceEvent->isPast() && $raceEvent->status !== 'cancelled')
        <div x-data="{ attending: {{ $isAttending ? 'true' : 'false' }}, count: {{ $raceEvent->attendees_count }}, loading: false }"
             class="rounded-2xl px-5 py-4 flex items-center justify-between gap-4"
             style="background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.08)">
            <div>
                <p class="text-sm font-black text-white" x-text="attending ? '¡Vas a esta carrera!' : '¿Te apuntas?'"></p>
                <p class="text-xs mt-0.5" style="color:rgba(255,255,255,0.40)">
                    <span x-text="count"></span> personas apuntadas
                </p>
            </div>
            <button @click="
                if (loading) return;
                loading = true;
                fetch('{{ route('events.attend', $raceEvent) }}', {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content, 'Accept': 'application/json' }
                }).then(r => r.json()).then(d => {
                    attending = d.attending;
                    count = d.count;
                    loading = false;
                    if (navigator.vibrate) navigator.vibrate(30);
                }).catch(() => loading = false)
            "
            :disabled="loading"
            class="flex-shrink-0 px-5 py-2.5 rounded-xl font-black text-sm transition-all"
            :style="attending
                ? 'background:rgba(248,113,113,0.12);color:#f87171'
                : 'background:#C8FA5F;color:#000'">
                <span x-show="!loading" x-text="attending ? 'Me voy' : 'Me apunto'"></span>
                <span x-show="loading" class="opacity-50">...</span>
            </button>
        </div>
        @endif

        {{-- Modalities --}}
        @if($raceEvent->modalities->isNotEmpty())
        @php $totalModalities = $raceEvent->modalities->count(); @endphp
        <div x-data="{ expanded: false }">
            <div class="flex items-center justify-between mb-3">
                <p class="section-label mb-0">Modalidades <span class="text-white/30 font-normal">({{ $totalModalities }})</span></p>
                @if($totalModalities > 2)
                    <button type="button" @click="expanded = !expanded"
                            class="text-xs font-bold transition-colors text-primary/70 hover:text-primary">
                        <span x-text="expanded ? 'Ver menos' : 'Ver todas'"></span>
                    </button>
                @endif
            </div>
            <div class="space-y-2">
                @foreach($raceEvent->modalities as $i => $modality)
                <div {{ $i >= 2 ? 'x-show="expanded"' : '' }}
                     class="rounded-2xl overflow-hidden" style="background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.08)">
                    <div class="px-5 py-3.5 flex items-center justify-between gap-3">
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-black text-white">{{ $modality->name }}</p>
                            <div class="flex items-center gap-3 mt-1 flex-wrap">
                                @if($modality->distance_km)
                                    <span class="text-xs font-bold text-primary">{{ $modality->distance_km }} km</span>
                                @endif
                                @if($modality->category)
                                    <span class="text-xs font-bold" style="color:rgba(255,255,255,0.40)">{{ $modality->category }}</span>
                                @endif
                                @if($modality->price !== null)
                                    <span class="text-xs font-bold" style="color:rgba(255,255,255,0.40)">
                                        {{ $modality->price > 0 ? number_format($modality->price, 2) . ' €' : 'Gratis' }}
                                    </span>
                                @endif
                                @if($modality->max_participants)
                                    <span class="text-xs" style="color:rgba(255,255,255,0.25)">Máx. {{ number_format($modality->max_participants) }}</span>
                                @endif
                            </div>
                        </div>
                        @if($modality->registration_url)
                            <a href="{{ $modality->registration_url }}" target="_blank" rel="noopener noreferrer"
                               class="flex-shrink-0 px-4 py-2 rounded-xl text-xs font-black transition-all active:scale-95"
                               style="background:rgba(200,250,95,0.15);color:#C8FA5F">
                                Inscribirse
                            </a>
                        @endif
                    </div>
                </div>
                @endforeach

                @if($totalModalities > 2)
                    <button type="button" x-show="!expanded" @click="expanded = true"
                            class="w-full py-2.5 rounded-2xl text-xs font-bold transition-colors"
                            style="background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.08);color:rgba(255,255,255,0.40)">
                        + {{ $totalModalities - 2 }} modalidades más
                    </button>
                @endif
            </div>
        </div>
        @else
            {{-- Single registration link (no modalities) --}}
            @if($raceEvent->registration_url)
            <a href="{{ $raceEvent->registration_url }}" target="_blank" rel="noopener noreferrer"
               class="flex items-center justify-between px-5 py-4 rounded-2xl transition-all active:scale-[0.99]"
               style="background:linear-gradient(135deg,rgba(200,250,95,0.12),rgba(200,250,95,0.05));border:1px solid rgba(200,250,95,0.25)">
                <div>
                    <p class="text-sm font-black text-primary">Inscribirse en la carrera</p>
                    <p class="text-xs mt-0.5" style="color:rgba(255,255,255,0.40)">
                        {{ $raceEvent->registration_deadline ? 'Plazo: ' . $raceEvent->registration_deadline->format('d/m/Y') : 'Abre en web oficial' }}
                    </p>
                </div>
                <svg class="w-5 h-5 text-primary flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                </svg>
            </a>
            @endif
        @endif

        {{-- Key info --}}
        <div class="card overflow-hidden">
            @php
                $infoRows = array_filter([
                    ['Fecha',         $raceEvent->event_date->translatedFormat('l, d \d\e F \d\e Y')],
                    ['Hora',          $raceEvent->event_date->format('H:i') !== '00:00' ? $raceEvent->event_date->format('H:i') . 'h' : null],
                    ['Lugar',         $raceEvent->location . ($raceEvent->province ? ', ' . $raceEvent->province : '')],
                    ['País',          $raceEvent->country !== 'España' ? $raceEvent->country : null],
                    ['Tipo',          $raceEvent->raceTypeLabel()],
                    ['Organiza',      $raceEvent->organizer],
                    ['Límite inscr.', $raceEvent->registration_deadline?->format('d/m/Y')],
                ], fn ($r) => !empty($r[1]));

                // Only show distance/category/price at event level if no modalities
                if ($raceEvent->modalities->isEmpty()) {
                    $infoRows = array_merge($infoRows, array_filter([
                        ['Distancia',    $raceEvent->distance_km ? $raceEvent->distance_km . ' km' : null],
                        ['Categoría',    $raceEvent->category],
                        ['Precio',       $raceEvent->price !== null ? ($raceEvent->price > 0 ? number_format($raceEvent->price, 2) . ' €' : 'Gratuita') : null],
                        ['Participantes',$raceEvent->max_participants ? 'Máx. ' . number_format($raceEvent->max_participants) : null],
                    ], fn ($r) => !empty($r[1])));
                }
            @endphp
            @foreach($infoRows as [$label, $value])
                <div class="flex items-center justify-between px-5 py-3.5 {{ !$loop->last ? 'border-b' : '' }}"
                     style="{{ !$loop->last ? 'border-color:rgba(255,255,255,0.05)' : '' }}">
                    <span class="text-xs font-bold" style="color:rgba(255,255,255,0.35)">{{ $label }}</span>
                    <span class="text-sm font-semibold text-white text-right max-w-[60%]">{{ $value }}</span>
                </div>
            @endforeach
        </div>

        {{-- Description --}}
        @if($raceEvent->description)
        <div class="card px-5 py-4">
            <p class="text-xs font-black uppercase tracking-widest mb-3" style="color:rgba(255,255,255,0.30)">Sobre la carrera</p>
            <div class="text-sm leading-relaxed whitespace-pre-line" style="color:rgba(255,255,255,0.65)">{{ $raceEvent->description }}</div>
        </div>
        @endif

        {{-- Website --}}
        @if($raceEvent->website_url)
        <a href="{{ $raceEvent->website_url }}" target="_blank" rel="noopener noreferrer"
           class="flex items-center gap-3 px-5 py-4 rounded-2xl"
           style="background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.07)">
            <svg class="w-4 h-4 flex-shrink-0" style="color:rgba(255,255,255,0.35)" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9"/>
            </svg>
            <span class="text-sm font-semibold flex-1 truncate" style="color:rgba(255,255,255,0.55)">Web oficial</span>
            <svg class="w-4 h-4 flex-shrink-0" style="color:rgba(255,255,255,0.25)" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
            </svg>
        </a>
        @endif

    </div>
</x-app-layout>
