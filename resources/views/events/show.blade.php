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

        {{-- Attend CTA + modal --}}
        @if($raceEvent->status !== 'cancelled')
        <div x-data="{
                attending: {{ $isAttending ? 'true' : 'false' }},
                count: {{ $raceEvent->attendees_count }},
                loading: false,
                modal: false,
                unattendModal: false,
                remember: false,
                raceAdded: false,
                eventName: '',
                eventDate: '',
                eventStatus: '',
                csrf: document.querySelector('meta[name=csrf-token]').content,
                toggle() {
                    if (this.loading) return;
                    this.loading = true;
                    fetch('{{ route('events.attend', $raceEvent) }}', {
                        method: 'POST',
                        headers: { 'X-CSRF-TOKEN': this.csrf, 'Accept': 'application/json' }
                    }).then(r => r.json()).then(d => {
                        this.attending = d.attending;
                        this.count = d.count;
                        this.loading = false;
                        if (navigator.vibrate) navigator.vibrate(30);
                        if (d.attending) {
                            if (d.race_added) { this.raceAdded = true; }
                            else if (d.show_modal) {
                                this.eventName = d.event.name;
                                this.eventDate = d.event.date || '';
                                this.eventStatus = d.event.status;
                                this.modal = true;
                            }
                        } else {
                            if (d.show_unattend_modal) { this.unattendModal = true; }
                        }
                    }).catch(() => { this.loading = false; });
                },
                addToRaces() {
                    fetch('{{ route('events.add-to-races', $raceEvent) }}', {
                        method: 'POST',
                        headers: { 'X-CSRF-TOKEN': this.csrf, 'Accept': 'application/json', 'Content-Type': 'application/json' },
                        body: JSON.stringify({ remember: this.remember })
                    }).then(r => r.json()).then(() => {
                        this.modal = false;
                        this.raceAdded = true;
                    });
                },
                skip() {
                    fetch('{{ route('events.skip-add-to-races', $raceEvent) }}', {
                        method: 'POST',
                        headers: { 'X-CSRF-TOKEN': this.csrf, 'Accept': 'application/json', 'Content-Type': 'application/json' },
                        body: JSON.stringify({ remember: this.remember })
                    }).then(() => { this.modal = false; });
                },
                removeFromRaces() {
                    fetch('{{ route('events.remove-from-races', $raceEvent) }}', {
                        method: 'DELETE',
                        headers: { 'X-CSRF-TOKEN': this.csrf, 'Accept': 'application/json' }
                    }).then(() => {
                        this.unattendModal = false;
                        this.raceAdded = false;
                    });
                }
             }">

            {{-- Attend button row --}}
            <div class="rounded-2xl px-5 py-4 flex items-center justify-between gap-4"
                 style="background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.08)">
                <div>
                    <p class="text-sm font-black text-white" x-text="attending ? '¡Vas a esta carrera!' : '¿Te apuntas?'"></p>
                    <p class="text-xs mt-0.5" style="color:rgba(255,255,255,0.40)">
                        <span x-text="count"></span> personas apuntadas
                    </p>
                </div>
                <button @click="toggle()" :disabled="loading"
                        class="flex-shrink-0 px-5 py-2.5 rounded-xl font-black text-sm transition-all"
                        :style="attending ? 'background:rgba(248,113,113,0.12);color:#f87171' : 'background:#C8FA5F;color:#000'">
                    <span x-show="!loading" x-text="attending ? 'Me voy' : 'Me apunto'"></span>
                    <span x-show="loading" class="opacity-50">...</span>
                </button>
            </div>

            {{-- Race added confirmation --}}
            <div x-show="raceAdded" x-transition
                 class="mt-2 rounded-xl px-4 py-2.5 flex items-center gap-2 text-xs font-semibold"
                 style="background:rgba(74,222,128,0.10);color:#4ade80">
                <svg class="w-4 h-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Carrera añadida a tu historial
            </div>

            {{-- Modal backdrop --}}
            <div x-show="modal" x-transition.opacity
                 class="fixed inset-0 z-50 flex items-end sm:items-center justify-center p-4"
                 style="background:rgba(0,0,0,0.75)" x-cloak>

                {{-- Modal panel --}}
                <div x-show="modal" x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 translate-y-4"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     class="w-full max-w-sm rounded-2xl p-6"
                     style="background:#1a1a1a;border:1px solid rgba(255,255,255,0.10)">

                    {{-- Icon --}}
                    <div class="w-12 h-12 rounded-xl flex items-center justify-center mb-4"
                         style="background:rgba(200,250,95,0.10)">
                        <svg class="w-6 h-6" style="color:#C8FA5F" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                    </div>

                    <h3 class="text-white font-black text-lg leading-tight mb-1">¿Añadir a tus carreras?</h3>
                    <p class="text-sm mb-1" style="color:rgba(255,255,255,0.50)">
                        <span x-text="eventName" class="font-semibold text-white/80"></span>
                    </p>
                    <p class="text-xs mb-5" style="color:rgba(255,255,255,0.35)">
                        <span x-text="eventDate"></span>
                        &middot;
                        <span x-text="eventStatus === 'completed' ? 'Se añadirá como carrera terminada' : 'Se añadirá como próxima carrera'"></span>
                    </p>

                    {{-- Remember toggle --}}
                    <label class="flex items-center gap-3 cursor-pointer mb-6 select-none">
                        <input type="checkbox" x-model="remember"
                               class="w-4 h-4 rounded text-primary border-white/20 bg-transparent focus:ring-primary/30">
                        <span class="text-xs font-semibold" style="color:rgba(255,255,255,0.45)">Recordar mi elección</span>
                    </label>

                    {{-- Actions --}}
                    <div class="flex gap-3">
                        <button @click="skip()"
                                class="flex-1 py-3 rounded-xl font-bold text-sm transition-colors"
                                style="background:rgba(255,255,255,0.06);color:rgba(255,255,255,0.60)"
                                onmouseenter="this.style.background='rgba(255,255,255,0.10)'"
                                onmouseleave="this.style.background='rgba(255,255,255,0.06)'">
                            No, gracias
                        </button>
                        <button @click="addToRaces()"
                                class="flex-1 py-3 rounded-xl font-black text-sm text-black transition-all active:scale-[0.97]"
                                style="background:#C8FA5F">
                            Sí, añadir
                        </button>
                    </div>
                </div>
            </div>

            {{-- Unattend modal: remove from races? --}}
            <div x-show="unattendModal" x-transition.opacity
                 class="fixed inset-0 z-50 flex items-end sm:items-center justify-center p-4"
                 style="background:rgba(0,0,0,0.75)" x-cloak>

                <div x-show="unattendModal" x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 translate-y-4"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     class="w-full max-w-sm rounded-2xl p-6"
                     style="background:#1a1a1a;border:1px solid rgba(255,255,255,0.10)">

                    <div class="w-12 h-12 rounded-xl flex items-center justify-center mb-4"
                         style="background:rgba(248,113,113,0.10)">
                        <svg class="w-6 h-6" style="color:#f87171" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                    </div>

                    <h3 class="text-white font-black text-lg leading-tight mb-2">¿Eliminar de tus carreras?</h3>
                    <p class="text-sm mb-6" style="color:rgba(255,255,255,0.45)">
                        Esta carrera está en tu historial. ¿Quieres eliminarla también?
                    </p>

                    <div class="flex gap-3">
                        <button @click="unattendModal = false"
                                class="flex-1 py-3 rounded-xl font-bold text-sm transition-colors"
                                style="background:rgba(255,255,255,0.06);color:rgba(255,255,255,0.60)"
                                onmouseenter="this.style.background='rgba(255,255,255,0.10)'"
                                onmouseleave="this.style.background='rgba(255,255,255,0.06)'">
                            No, mantener
                        </button>
                        <button @click="removeFromRaces()"
                                class="flex-1 py-3 rounded-xl font-black text-sm transition-all active:scale-[0.97]"
                                style="background:rgba(248,113,113,0.15);color:#f87171">
                            Sí, eliminar
                        </button>
                    </div>
                </div>
            </div>
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
