<x-app-layout>
    @section('page_title', 'Anuncio #' . $ad->id)
    @section('back_url', route('admin.ads'))

    @php
        $statusConfig = [
            'pending'  => ['bg' => 'rgba(251,191,36,0.15)',  'text' => '#fbbf24', 'label' => 'Pendiente'],
            'approved' => ['bg' => 'rgb(var(--color-primary) / 0.12)',  'text' => 'rgb(var(--color-primary))', 'label' => 'Activo'],
            'paused'   => ['bg' => 'rgba(255,255,255,0.08)', 'text' => 'rgba(255,255,255,0.50)', 'label' => 'Pausado'],
            'rejected' => ['bg' => 'rgba(248,113,113,0.12)', 'text' => '#f87171', 'label' => 'Rechazado'],
        ];
        $sc = $statusConfig[$ad->status] ?? $statusConfig['pending'];
        $maxBar = $chartData->max('count') ?: 1;
    @endphp

    <div class="max-w-4xl mx-auto px-4 py-6">

        @if(session('success'))
            <div class="mb-4 card px-4 py-3 text-sm font-semibold text-green-400 border-green-500/20 bg-green-500/10">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="mb-4 card px-4 py-3 text-sm font-semibold text-red-400 border-red-500/20 bg-red-500/10">{{ session('error') }}</div>
        @endif

        <div class="grid md:grid-cols-2 gap-5">

            {{-- ── LEFT: Preview + stats ─────────────────────────── --}}
            <div class="space-y-4">

                {{-- Ad preview --}}
                <div>
                    <p class="section-label mb-2">Preview del anuncio</p>
                    <div class="rounded-2xl overflow-hidden" style="background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.08)">
                        @if($ad->imageUrl())
                            <div class="relative">
                                <img src="{{ $ad->imageUrl() }}" alt="{{ $ad->title }}"
                                     class="w-full object-cover" style="max-height:180px">
                                <div class="absolute inset-0" style="background:linear-gradient(to top,rgba(0,0,0,0.55) 0%,transparent 60%)"></div>
                            </div>
                        @endif
                        <div class="px-4 py-3.5 flex items-center gap-3">
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-2 mb-1">
                                    <span class="text-[9px] font-black uppercase tracking-wider px-1.5 py-0.5 rounded"
                                          style="background:rgba(255,255,255,0.10);color:rgba(255,255,255,0.40)">Anuncio</span>
                                    <span class="text-[10px]" style="color:rgba(255,255,255,0.35)">{{ $ad->typeLabel() }}</span>
                                </div>
                                <p class="text-sm font-bold text-white leading-snug">{{ $ad->title }}</p>
                                @if($ad->subtitle)
                                    <p class="text-xs mt-0.5 truncate" style="color:rgba(255,255,255,0.45)">{{ $ad->subtitle }}</p>
                                @endif
                            </div>
                            <span class="text-xs font-black flex-shrink-0 px-3.5 py-2 rounded-full bg-primary text-black">
                                {{ $ad->cta_label }}
                            </span>
                        </div>
                    </div>
                </div>

                {{-- Stats --}}
                <div class="grid grid-cols-3 gap-2.5">
                    @foreach([
                        [number_format($ad->impressions_count), 'Impresiones'],
                        [number_format($ad->clicks_count), 'Clicks'],
                        [$ad->ctr() . '%', 'CTR'],
                    ] as [$val, $label])
                        <div class="card px-3 py-3.5 text-center">
                            <p class="text-xl font-black text-primary tabnum leading-none">{{ $val }}</p>
                            <p class="text-[10px] font-bold uppercase tracking-wider mt-1.5" style="color:rgba(255,255,255,0.30)">{{ $label }}</p>
                        </div>
                    @endforeach
                </div>

                {{-- Click chart --}}
                <div class="card px-4 pt-4 pb-3">
                    <p class="text-xs font-black text-white mb-3">Clicks (últimos 14 días)</p>
                    <div class="flex items-end gap-1" style="height:64px">
                        @foreach($chartData as $day)
                            @php $pct = $maxBar > 0 ? round($day['count'] / $maxBar * 100) : 0; @endphp
                            <div class="flex-1 flex flex-col items-center gap-1 group">
                                <div class="w-full rounded-t-sm transition-all"
                                     style="height:{{ max(3, $pct * 0.60) }}px;background:{{ $day['count'] > 0 ? 'rgb(var(--color-primary) / 0.70)' : 'rgba(255,255,255,0.08)' }}"></div>
                            </div>
                        @endforeach
                    </div>
                    <div class="flex justify-between mt-1.5">
                        <span class="text-[9px]" style="color:rgba(255,255,255,0.25)">{{ $chartData->first()['date'] }}</span>
                        <span class="text-[9px]" style="color:rgba(255,255,255,0.25)">{{ $chartData->last()['date'] }}</span>
                    </div>
                </div>

            </div>

            {{-- ── RIGHT: Details + advertiser + actions ────────── --}}
            <div class="space-y-4">

                {{-- Status + advertiser --}}
                <div class="card px-5 py-4 space-y-3">
                    <div class="flex items-center justify-between">
                        <p class="text-xs font-black uppercase tracking-widest text-white">Anunciante</p>
                        <span class="text-[11px] font-black px-2.5 py-1 rounded-full"
                              style="background:{{ $sc['bg'] }};color:{{ $sc['text'] }}">
                            {{ $sc['label'] }}
                        </span>
                    </div>
                    <div class="flex items-center gap-3" style="border-top:1px solid rgba(255,255,255,0.05);padding-top:0.75rem">
                        <div class="w-9 h-9 rounded-full bg-primary flex items-center justify-center text-black font-black text-sm flex-shrink-0">
                            {{ strtoupper(substr($ad->user?->name ?? 'A', 0, 1)) }}
                        </div>
                        <div class="min-w-0">
                            <p class="text-sm font-bold text-white truncate">{{ $ad->user?->name ?? 'Anónimo' }}</p>
                            <p class="text-xs truncate" style="color:rgba(255,255,255,0.40)">{{ $ad->user?->email ?? '—' }}</p>
                        </div>
                    </div>
                    <div class="text-xs" style="color:rgba(255,255,255,0.35);border-top:1px solid rgba(255,255,255,0.05);padding-top:0.75rem">
                        Enviado {{ $ad->created_at->diffForHumans() }} · {{ $ad->created_at->format('d/m/Y H:i') }}
                    </div>
                </div>

                {{-- Ad details --}}
                <div class="card px-5 py-4 space-y-2.5">
                    <p class="text-xs font-black uppercase tracking-widest text-white mb-1">Detalles</p>
                    @foreach([
                        ['Tipo', $ad->typeLabel()],
                        ['Ubicación', $ad->location === 'feed' ? 'Entre carreras (feed)' : 'Dashboard principal'],
                        ['Inicio', $ad->starts_at ? $ad->starts_at->format('d/m/Y') : 'Inmediato'],
                        ['Fin', $ad->ends_at ? $ad->ends_at->format('d/m/Y') : 'Sin fecha'],
                        ['Máx. impresiones', $ad->max_impressions > 0 ? number_format($ad->max_impressions) : 'Ilimitadas'],
                    ] as [$key, $val])
                        <div class="flex items-center justify-between py-1.5" style="border-bottom:1px solid rgba(255,255,255,0.04)">
                            <span class="text-xs" style="color:rgba(255,255,255,0.40)">{{ $key }}</span>
                            <span class="text-xs font-semibold text-white">{{ $val }}</span>
                        </div>
                    @endforeach
                    <div class="pt-1">
                        <p class="text-xs mb-1" style="color:rgba(255,255,255,0.40)">URL de destino</p>
                        <a href="{{ $ad->target_url }}" target="_blank" rel="noopener"
                           class="text-xs font-semibold text-primary hover:underline break-all flex items-start gap-1">
                            <svg class="w-3 h-3 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                            </svg>
                            {{ $ad->target_url }}
                        </a>
                    </div>
                    @if($ad->rejection_reason)
                        <div class="rounded-xl px-3 py-2.5 mt-1" style="background:rgba(248,113,113,0.10);border:1px solid rgba(248,113,113,0.20)">
                            <p class="text-xs font-bold text-red-400 mb-0.5">Motivo de rechazo</p>
                            <p class="text-xs text-red-300">{{ $ad->rejection_reason }}</p>
                        </div>
                    @endif
                </div>

                {{-- Actions --}}
                <div x-data="{ rejectOpen: false }" class="card px-5 py-4 space-y-3">
                    <p class="text-xs font-black uppercase tracking-widest text-white">Acciones</p>

                    <div class="flex flex-wrap gap-2">
                        @if(in_array($ad->status, ['pending', 'rejected']))
                            <form method="POST" action="{{ route('admin.ads.approve', $ad) }}">
                                @csrf @method('PATCH')
                                <button type="submit" class="btn btn-primary text-xs py-2 px-4">
                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                    Aprobar y publicar
                                </button>
                            </form>
                        @endif
                        @if($ad->status === 'pending')
                            <button @click="rejectOpen = !rejectOpen"
                                    class="text-xs font-bold px-4 py-2 rounded-xl transition-colors"
                                    style="background:rgba(248,113,113,0.12);color:#f87171">
                                <svg class="w-3.5 h-3.5 inline-block mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                Rechazar
                            </button>
                        @endif
                        @if($ad->status === 'approved')
                            <form method="POST" action="{{ route('admin.ads.pause', $ad) }}">
                                @csrf @method('PATCH')
                                <button type="submit" class="text-xs font-bold px-4 py-2 rounded-xl transition-colors"
                                        style="background:rgba(255,255,255,0.08);color:rgba(255,255,255,0.60)">
                                    <svg class="w-3.5 h-3.5 inline-block mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 9v6m4-6v6m7-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    Pausar
                                </button>
                            </form>
                        @endif
                        @if($ad->status === 'paused')
                            <form method="POST" action="{{ route('admin.ads.pause', $ad) }}">
                                @csrf @method('PATCH')
                                <button type="submit" class="btn btn-primary text-xs py-2 px-4">
                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    Reactivar
                                </button>
                            </form>
                        @endif
                        <form method="POST" action="{{ route('admin.ads.destroy', $ad) }}"
                              onsubmit="return confirm('¿Eliminar este anuncio definitivamente?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-xs font-bold px-4 py-2 rounded-xl transition-colors"
                                    style="background:rgba(248,113,113,0.10);color:#f87171">
                                <svg class="w-3.5 h-3.5 inline-block mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                Eliminar
                            </button>
                        </form>
                    </div>

                    {{-- Reject form --}}
                    <div x-show="rejectOpen" x-transition x-cloak
                         class="pt-3" style="border-top:1px solid rgba(255,255,255,0.06)">
                        <form method="POST" action="{{ route('admin.ads.reject', $ad) }}" class="space-y-2">
                            @csrf @method('PATCH')
                            <label class="block text-xs font-bold mb-1" style="color:rgba(255,255,255,0.45)">
                                Motivo del rechazo <span style="color:rgba(255,255,255,0.25)">(opcional)</span>
                            </label>
                            <textarea name="reason" rows="2" maxlength="500"
                                      placeholder="ej: El contenido no cumple las directrices de la plataforma."
                                      class="input-field text-sm resize-none"></textarea>
                            <div class="flex gap-2 pt-1">
                                <button type="submit" class="text-xs font-bold px-4 py-2 rounded-xl"
                                        style="background:rgba(248,113,113,0.15);color:#f87171">
                                    Confirmar rechazo
                                </button>
                                <button type="button" @click="rejectOpen = false"
                                        class="text-xs font-bold px-4 py-2 rounded-xl"
                                        style="background:rgba(255,255,255,0.06);color:rgba(255,255,255,0.50)">
                                    Cancelar
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
