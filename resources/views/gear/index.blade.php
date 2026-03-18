<x-app-layout>
    @section('page_title', 'Material')
    @section('header_action')
        <a href="{{ route('gear.create') }}" class="btn btn-primary text-sm py-2 px-4">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
            Añadir
        </a>
    @endsection

    <main class="px-5 py-6 max-w-2xl mx-auto w-full space-y-8">

        @if($activeGear->isEmpty() && $retiredGear->isEmpty())
            <div class="flex flex-col items-center justify-center py-20 text-center">
                <div class="w-20 h-20 rounded-3xl bg-primary/10 border border-primary/20 flex items-center justify-center mb-5">
                    <svg class="w-10 h-10 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/>
                    </svg>
                </div>
                <p class="text-white font-black text-lg">Sin material registrado</p>
                <p class="text-sm mt-2 mb-6" style="color:rgba(255,255,255,0.35)">Registra tus zapatillas y equipamiento</p>
                <a href="{{ route('gear.create') }}" class="btn btn-primary px-8">Añadir material</a>
            </div>
        @else
            @if($activeGear->isNotEmpty())
                <section>
                    <h2 class="text-lg font-black text-white mb-4">Activo</h2>
                    <div class="space-y-3">
                        @foreach($activeGear as $item)
                            @php
                                $pct = $item->usage_percentage;
                                $barColor = $pct === null ? 'rgba(255,255,255,0.15)' : ($pct >= 85 ? '#f87171' : ($pct >= 60 ? '#fb923c' : '#4ade80'));
                                $types = [
                                    'shoes'       => ['label' => 'Zapatillas', 'color' => 'rgba(96,165,250,0.15)', 'text' => '#60a5fa'],
                                    'watch'       => ['label' => 'Reloj GPS',  'color' => 'rgba(167,139,250,0.15)','text' => '#a78bfa'],
                                    'clothing'    => ['label' => 'Ropa',       'color' => 'rgba(244,114,182,0.15)','text' => '#f472b6'],
                                    'accessories' => ['label' => 'Accesorios', 'color' => 'rgba(251,191,36,0.15)', 'text' => '#fbbf24'],
                                    'nutrition'   => ['label' => 'Nutrición',  'color' => 'rgba(74,222,128,0.15)', 'text' => '#4ade80'],
                                    'other'       => ['label' => 'Otro',       'color' => 'rgba(255,255,255,0.08)','text' => 'rgba(255,255,255,0.50)'],
                                ];
                                $typeInfo = $types[$item->type] ?? $types['other'];
                            @endphp
                            <div class="card p-4">
                                <div class="flex items-start justify-between mb-4">
                                    <div class="flex-1 min-w-0">
                                        <span class="text-[11px] font-bold px-2.5 py-1 rounded-full inline-block mb-2"
                                              style="background:{{ $typeInfo['color'] }};color:{{ $typeInfo['text'] }}">{{ $typeInfo['label'] }}</span>
                                        <p class="font-black text-white text-base leading-tight">{{ $item->brand }} {{ $item->model }}</p>
                                        @if($item->purchase_date)
                                            <p class="text-xs mt-0.5" style="color:rgba(255,255,255,0.35)">Comprado {{ $item->purchase_date->format('M Y') }}</p>
                                        @endif
                                    </div>
                                    <div class="flex items-center gap-1 ml-3">
                                        <a href="{{ route('gear.edit', $item) }}" class="w-9 h-9 flex items-center justify-center rounded-xl transition-colors" style="background:rgba(255,255,255,0.06)" onmouseover="this.style.background='rgba(255,255,255,0.12)'" onmouseout="this.style.background='rgba(255,255,255,0.06)'">
                                            <svg class="w-4 h-4" style="color:rgba(255,255,255,0.50)" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                        </a>
                                        <form method="POST" action="{{ route('gear.destroy', $item) }}" onsubmit="return confirm('Eliminar este material?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="w-9 h-9 flex items-center justify-center rounded-xl transition-colors" style="background:rgba(255,255,255,0.06)" onmouseover="this.style.background='rgba(248,113,113,0.15)'" onmouseout="this.style.background='rgba(255,255,255,0.06)'">
                                                <svg class="w-4 h-4" style="color:rgba(255,255,255,0.35)" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                                @if($item->max_km)
                                    <div>
                                        <div class="flex justify-between text-xs mb-2" style="color:rgba(255,255,255,0.45)">
                                            <span class="font-bold tabnum">{{ number_format((float)$item->current_km, 0) }} km</span>
                                            <span class="tabnum">{{ $pct }}% · {{ number_format((float)$item->remaining_km, 0) }} km rest.</span>
                                        </div>
                                        <div class="h-1.5 rounded-full overflow-hidden" style="background:rgba(255,255,255,0.08)">
                                            <div class="h-full rounded-full transition-all" style="width:{{ min(100, $pct ?? 0) }}%;background:{{ $barColor }}"></div>
                                        </div>
                                        <p class="text-[10px] mt-1.5" style="color:rgba(255,255,255,0.25)">Máx: {{ number_format((float)$item->max_km, 0) }} km</p>
                                    </div>
                                @else
                                    <p class="text-sm font-bold tabnum" style="color:rgba(255,255,255,0.50)">{{ number_format((float)$item->current_km, 0) }} km acumulados</p>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </section>
            @endif

            @if($retiredGear->isNotEmpty())
                <section>
                    <h2 class="text-base font-black mb-3" style="color:rgba(255,255,255,0.35)">Retirado</h2>
                    <div class="space-y-2">
                        @foreach($retiredGear as $item)
                            <div class="card p-4 flex items-center justify-between opacity-50">
                                <div>
                                    <p class="font-bold text-white text-sm">{{ $item->brand }} {{ $item->model }}</p>
                                    <p class="text-xs mt-0.5 tabnum" style="color:rgba(255,255,255,0.40)">{{ number_format((float)$item->current_km, 0) }} km totales</p>
                                </div>
                                <form method="POST" action="{{ route('gear.destroy', $item) }}" onsubmit="return confirm('Eliminar?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="w-8 h-8 flex items-center justify-center rounded-xl" style="color:rgba(255,255,255,0.25)">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </form>
                            </div>
                        @endforeach
                    </div>
                </section>
            @endif
        @endif

    </main>
</x-app-layout>
