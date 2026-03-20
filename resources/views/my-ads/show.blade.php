<x-app-layout>
    @section('page_title', $ad->title)
    @section('back_url', route('my-ads.index'))

    @php
        $statusConfig = [
            'pending'  => ['bg' => 'rgba(251,191,36,0.15)',  'text' => '#fbbf24', 'label' => 'Pendiente de revisión'],
            'approved' => ['bg' => 'rgb(var(--color-primary) / 0.12)',  'text' => 'rgb(var(--color-primary))', 'label' => 'Activo'],
            'paused'   => ['bg' => 'rgba(255,255,255,0.08)', 'text' => 'rgba(255,255,255,0.50)', 'label' => 'Pausado por admin'],
            'rejected' => ['bg' => 'rgba(248,113,113,0.12)', 'text' => '#f87171', 'label' => 'Rechazado'],
        ];
        $sc = $statusConfig[$ad->status] ?? $statusConfig['pending'];
        $maxBar = $chartData->max('count') ?: 1;
        $canEdit = in_array($ad->status, ['pending', 'rejected']);
    @endphp

    <div class="max-w-2xl mx-auto px-4 py-6 space-y-5">

        @if(session('success'))
            <div class="card px-4 py-3 text-sm font-semibold text-green-400 border-green-500/20 bg-green-500/10">{{ session('success') }}</div>
        @endif

        {{-- Status banner --}}
        <div class="rounded-2xl px-4 py-3.5 flex items-center gap-3"
             style="background:{{ $sc['bg'] }};border:1px solid {{ $sc['text'] }}22">
            <div class="flex-1">
                <p class="text-sm font-black" style="color:{{ $sc['text'] }}">{{ $sc['label'] }}</p>
                @if($ad->status === 'pending')
                    <p class="text-xs mt-0.5" style="color:{{ $sc['text'] }}99">Normalmente revisamos en menos de 24 h.</p>
                @elseif($ad->status === 'rejected' && $ad->rejection_reason)
                    <p class="text-xs mt-0.5" style="color:{{ $sc['text'] }}99">Motivo: {{ $ad->rejection_reason }}</p>
                @elseif($ad->status === 'approved')
                    <p class="text-xs mt-0.5" style="color:{{ $sc['text'] }}99">
                        {{ $ad->isActive() ? 'Mostrándose ahora.' : 'Fuera de ventana de emisión.' }}
                    </p>
                @endif
            </div>
            @if($canEdit)
                <span class="text-[10px] font-bold px-2 py-1 rounded-lg" style="background:{{ $sc['text'] }}20;color:{{ $sc['text'] }}">
                    Editable
                </span>
            @endif
        </div>

        {{-- Ad preview --}}
        <div>
            <p class="section-label mb-2">Vista previa</p>
            <div class="rounded-2xl overflow-hidden" style="background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.08)">
                @if($ad->imageUrl())
                    <div class="relative">
                        <img src="{{ $ad->imageUrl() }}" alt="{{ $ad->title }}"
                             class="w-full object-cover" style="max-height:200px">
                        <div class="absolute inset-0" style="background:linear-gradient(to top,rgba(0,0,0,0.55) 0%,transparent 60%)"></div>
                    </div>
                @endif
                <div class="px-4 py-3.5 flex items-center gap-3">
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-2 mb-1">
                            <span class="text-[9px] font-black uppercase tracking-wider px-1.5 py-0.5 rounded"
                                  style="background:rgba(255,255,255,0.10);color:rgba(255,255,255,0.40)">Anuncio</span>
                            <span class="text-[10px]" style="color:rgba(255,255,255,0.30)">{{ $ad->typeLabel() }}</span>
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
        <div>
            <p class="section-label mb-2">Rendimiento</p>
            <div class="grid grid-cols-3 gap-2.5">
                @foreach([
                    [number_format($ad->impressions_count), 'Impresiones', 'M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z'],
                    [number_format($ad->clicks_count), 'Clicks', 'M15 15l-2 5L9 9l11 4-5 2zm0 0l5 5M7.188 2.239l.777 2.897M5.136 7.965l-2.898-.777M13.95 4.05l-2.122 2.122m-5.657 5.656l-2.12 2.122'],
                    [$ad->ctr() . '%', 'CTR', 'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z'],
                ] as [$val, $label, $icon])
                    <div class="card px-3 py-4 text-center">
                        <p class="text-xl font-black text-primary tabnum leading-none">{{ $val }}</p>
                        <p class="text-[10px] font-bold uppercase tracking-wider mt-1.5" style="color:rgba(255,255,255,0.30)">{{ $label }}</p>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Click chart --}}
        <div class="card px-4 pt-4 pb-3">
            <div class="flex items-center justify-between mb-3">
                <p class="text-xs font-black text-white">Clicks por día</p>
                <span class="text-[10px]" style="color:rgba(255,255,255,0.30)">Últimos 14 días</span>
            </div>
            <div class="flex items-end gap-1" style="height:72px">
                @foreach($chartData as $day)
                    @php $pct = $maxBar > 0 ? round($day['count'] / $maxBar * 100) : 0; @endphp
                    <div class="flex-1 flex flex-col items-center justify-end group relative">
                        @if($day['count'] > 0)
                            <div class="absolute -top-5 left-1/2 -translate-x-1/2 opacity-0 group-hover:opacity-100 transition-opacity
                                        text-[9px] font-bold text-primary bg-black/80 px-1 py-0.5 rounded whitespace-nowrap">
                                {{ $day['count'] }}
                            </div>
                        @endif
                        <div class="w-full rounded-t transition-all"
                             style="height:{{ max(2, $pct * 0.68) }}px;background:{{ $day['count'] > 0 ? 'rgb(var(--color-primary) / 0.70)' : 'rgba(255,255,255,0.07)' }}"></div>
                    </div>
                @endforeach
            </div>
            <div class="flex justify-between mt-2">
                <span class="text-[9px]" style="color:rgba(255,255,255,0.25)">{{ $chartData->first()['date'] }}</span>
                <span class="text-[9px]" style="color:rgba(255,255,255,0.25)">{{ $chartData->last()['date'] }}</span>
            </div>
        </div>

        {{-- Campaign details --}}
        <div class="card px-5 py-4 space-y-2">
            <p class="text-xs font-black uppercase tracking-widest text-white mb-1">Detalles de campaña</p>
            @foreach([
                ['Tipo', $ad->typeLabel()],
                ['Posición', $ad->location === 'feed' ? 'Entre carreras (feed)' : 'Dashboard principal'],
                ['Inicio', $ad->starts_at ? $ad->starts_at->format('d/m/Y') : 'Inmediato'],
                ['Fin', $ad->ends_at ? $ad->ends_at->format('d/m/Y') : 'Sin fecha límite'],
                ['Máx. impresiones', $ad->max_impressions > 0 ? number_format($ad->max_impressions) : 'Ilimitadas'],
                ['Creado', $ad->created_at->format('d/m/Y')],
            ] as [$key, $val])
                <div class="flex items-center justify-between py-1.5" style="border-bottom:1px solid rgba(255,255,255,0.04)">
                    <span class="text-xs" style="color:rgba(255,255,255,0.40)">{{ $key }}</span>
                    <span class="text-xs font-semibold text-white">{{ $val }}</span>
                </div>
            @endforeach
        </div>

        {{-- Edit section (only for pending or rejected) --}}
        @if($canEdit)
        <div x-data="{ open: {{ $errors->any() ? 'true' : 'false' }} }">
            <button type="button" @click="open = !open"
                    class="w-full card-interactive flex items-center gap-3 px-5 py-4">
                <div class="w-8 h-8 rounded-xl flex items-center justify-center flex-shrink-0"
                     style="background:rgba(255,255,255,0.06)">
                    <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                </div>
                <div class="flex-1 text-left">
                    <p class="text-sm font-bold text-white">Editar anuncio</p>
                    <p class="text-xs mt-0.5" style="color:rgba(255,255,255,0.40)">
                        @if($ad->status === 'rejected')
                            Corrígelo y se enviará de nuevo a revisión.
                        @else
                            Puedes modificarlo mientras está en revisión.
                        @endif
                    </p>
                </div>
                <svg class="w-4 h-4 transition-transform duration-200" :class="open ? 'rotate-90' : ''"
                     style="color:rgba(255,255,255,0.30)" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </button>

            <div x-show="open" x-transition x-cloak class="card mt-1 overflow-hidden">
                <form method="POST" action="{{ route('my-ads.update', $ad) }}" enctype="multipart/form-data">
                    @csrf @method('PATCH')

                    <div class="divide-y divide-white/[0.05]">

                        <div class="px-5 py-4 space-y-1.5">
                            <label class="block text-xs font-bold" style="color:rgba(255,255,255,0.50)">Título <span class="text-red-400">*</span></label>
                            <input type="text" name="title" value="{{ old('title', $ad->title) }}" required maxlength="80"
                                   class="input-field @error('title') error @enderror">
                            @error('title')<p class="text-red-400 text-xs">{{ $message }}</p>@enderror
                        </div>

                        <div class="px-5 py-4 space-y-1.5">
                            <label class="block text-xs font-bold" style="color:rgba(255,255,255,0.50)">Subtítulo</label>
                            <input type="text" name="subtitle" value="{{ old('subtitle', $ad->subtitle) }}" maxlength="160"
                                   class="input-field @error('subtitle') error @enderror">
                        </div>

                        <div class="px-5 py-4 space-y-1.5">
                            <label class="block text-xs font-bold" style="color:rgba(255,255,255,0.50)">Imagen</label>
                            @if($ad->imageUrl())
                                <img src="{{ $ad->imageUrl() }}" alt="" class="h-16 rounded-xl object-cover mb-2">
                                <p class="text-[10px] mb-2" style="color:rgba(255,255,255,0.35)">Sube una nueva para reemplazarla.</p>
                            @endif
                            <input type="file" name="image" accept="image/*"
                                   class="input-field text-sm file:mr-4 file:py-1.5 file:px-3 file:rounded-full file:border-0 file:text-xs file:font-bold file:bg-primary/20 file:text-primary cursor-pointer">
                            @error('image')<p class="text-red-400 text-xs">{{ $message }}</p>@enderror
                        </div>

                        <div class="px-5 py-4 space-y-1.5">
                            <label class="block text-xs font-bold" style="color:rgba(255,255,255,0.50)">Texto del botón <span class="text-red-400">*</span></label>
                            <input type="text" name="cta_label" value="{{ old('cta_label', $ad->cta_label) }}" required maxlength="30"
                                   class="input-field @error('cta_label') error @enderror">
                        </div>

                        <div class="px-5 py-4 space-y-1.5">
                            <label class="block text-xs font-bold" style="color:rgba(255,255,255,0.50)">URL de destino <span class="text-red-400">*</span></label>
                            <input type="url" name="target_url" value="{{ old('target_url', $ad->target_url) }}" required
                                   class="input-field @error('target_url') error @enderror">
                            @error('target_url')<p class="text-red-400 text-xs">{{ $message }}</p>@enderror
                        </div>

                        <div class="grid grid-cols-2 gap-4 px-5 py-4">
                            <div class="space-y-1.5">
                                <label class="block text-xs font-bold" style="color:rgba(255,255,255,0.50)">Tipo</label>
                                <select name="type" class="input-field">
                                    @foreach(['race' => 'Carrera', 'product' => 'Producto', 'service' => 'Servicio', 'event' => 'Evento'] as $val => $label)
                                        <option value="{{ $val }}" {{ old('type', $ad->type) === $val ? 'selected' : '' }}>{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="space-y-1.5">
                                <label class="block text-xs font-bold" style="color:rgba(255,255,255,0.50)">Posición</label>
                                <select name="location" class="input-field">
                                    <option value="feed" {{ old('location', $ad->location) === 'feed' ? 'selected' : '' }}>Feed de carreras</option>
                                    <option value="dashboard" {{ old('location', $ad->location) === 'dashboard' ? 'selected' : '' }}>Dashboard</option>
                                </select>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4 px-5 py-4">
                            <div class="space-y-1.5">
                                <label class="block text-xs font-bold" style="color:rgba(255,255,255,0.50)">Fecha inicio</label>
                                <input type="date" name="starts_at" value="{{ old('starts_at', $ad->starts_at?->format('Y-m-d')) }}"
                                       class="input-field">
                            </div>
                            <div class="space-y-1.5">
                                <label class="block text-xs font-bold" style="color:rgba(255,255,255,0.50)">Fecha fin</label>
                                <input type="date" name="ends_at" value="{{ old('ends_at', $ad->ends_at?->format('Y-m-d')) }}"
                                       class="input-field @error('ends_at') error @enderror">
                            </div>
                            @error('ends_at')<p class="text-red-400 text-xs col-span-2 px-0">{{ $message }}</p>@enderror
                        </div>

                        <div class="px-5 py-4 space-y-1.5">
                            <label class="block text-xs font-bold" style="color:rgba(255,255,255,0.50)">Máx. impresiones (0 = ilimitado)</label>
                            <input type="number" name="max_impressions" value="{{ old('max_impressions', $ad->max_impressions) }}"
                                   min="0" class="input-field">
                        </div>

                        <div class="px-5 py-4 flex gap-2 justify-end">
                            <button type="button" @click="open = false" class="btn btn-secondary text-xs py-2">Cancelar</button>
                            <button type="submit" class="btn btn-primary text-xs py-2">
                                @if($ad->status === 'rejected') Corregir y reenviar @else Guardar cambios @endif
                            </button>
                        </div>

                    </div>
                </form>
            </div>
        </div>
        @endif

        {{-- Delete --}}
        <div class="card px-5 py-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-bold text-white">Eliminar anuncio</p>
                    <p class="text-xs mt-0.5" style="color:rgba(255,255,255,0.35)">Esta acción no se puede deshacer.</p>
                </div>
                <form method="POST" action="{{ route('my-ads.destroy', $ad) }}"
                      onsubmit="return confirm('¿Eliminar este anuncio definitivamente?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="text-xs font-bold px-4 py-2 rounded-xl"
                            style="background:rgba(248,113,113,0.10);color:#f87171">
                        Eliminar
                    </button>
                </form>
            </div>
        </div>

    </div>
</x-app-layout>
