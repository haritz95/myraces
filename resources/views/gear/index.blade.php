<x-app-layout>
    @section('page_title', 'Material')
    @section('header_action')
        <a href="{{ route('gear.create') }}" class="btn btn-primary text-sm py-2 px-4">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
            Añadir
        </a>
    @endsection

    <main class="flex-1 overflow-y-auto px-4 py-6 max-w-2xl mx-auto w-full pb-[76px]">

        @if($activeGear->isEmpty() && $retiredGear->isEmpty())
            <div class="flex flex-col items-center justify-center py-16 text-center">
                <div class="w-20 h-20 rounded-full bg-primary/10 flex items-center justify-center mb-4">
                    <svg class="w-10 h-10 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/>
                    </svg>
                </div>
                <p class="text-slate-800 font-bold text-lg">Sin material registrado</p>
                <p class="text-slate-400 text-sm mt-2 mb-6">Registra tus zapatillas y equipamiento</p>
                <a href="{{ route('gear.create') }}" class="bg-primary text-white rounded-xl px-6 py-3 font-bold text-sm" style="box-shadow: 0 4px 12px rgba(236,91,19,0.4)">
                    Añadir material
                </a>
            </div>
        @else
            @if($activeGear->isNotEmpty())
                <section class="mb-8">
                    <h2 class="text-lg font-bold text-slate-900 mb-4">Activo</h2>
                    <div class="space-y-3">
                        @foreach($activeGear as $item)
                            @php
                                $pct = $item->usage_percentage;
                                $barColor = $pct === null ? 'bg-slate-300' : ($pct >= 85 ? 'bg-red-500' : ($pct >= 60 ? 'bg-amber-500' : 'bg-green-500'));
                                $types = [
                                    'shoes' => ['label' => 'Zapatillas', 'color' => 'bg-blue-100 text-blue-700'],
                                    'watch' => ['label' => 'Reloj', 'color' => 'bg-purple-100 text-purple-700'],
                                    'clothing' => ['label' => 'Ropa', 'color' => 'bg-pink-100 text-pink-700'],
                                    'accessories' => ['label' => 'Accesorios', 'color' => 'bg-amber-100 text-amber-700'],
                                    'nutrition' => ['label' => 'Nutricion', 'color' => 'bg-green-100 text-green-700'],
                                    'other' => ['label' => 'Otro', 'color' => 'bg-slate-100 text-slate-600'],
                                ];
                                $typeInfo = $types[$item->type] ?? $types['other'];
                            @endphp
                            <div class="bg-white rounded-xl border border-slate-100 shadow-sm p-4">
                                <div class="flex items-start justify-between mb-3">
                                    <div>
                                        <span class="text-[11px] font-semibold px-2 py-0.5 rounded-full {{ $typeInfo['color'] }}">{{ $typeInfo['label'] }}</span>
                                        <p class="font-bold text-slate-900 mt-1">{{ $item->brand }} {{ $item->model }}</p>
                                        @if($item->purchase_date)
                                            <p class="text-xs text-slate-400 mt-0.5">Comprado {{ $item->purchase_date->format('M Y') }}</p>
                                        @endif
                                    </div>
                                    <div class="flex items-center gap-1">
                                        <a href="{{ route('gear.edit', $item) }}" class="p-2 rounded-lg hover:bg-slate-100 text-slate-400 transition-colors">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                        </a>
                                        <form method="POST" action="{{ route('gear.destroy', $item) }}" onsubmit="return confirm('Eliminar este material?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="p-2 rounded-lg hover:bg-red-50 text-slate-300 hover:text-red-400 transition-colors">
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                                @if($item->max_km)
                                    <div class="mt-2">
                                        <div class="flex justify-between text-xs text-slate-500 mb-1">
                                            <span>{{ number_format((float)$item->current_km, 0) }} km</span>
                                            <span>{{ $pct }}% · {{ number_format((float)$item->remaining_km, 0) }} km rest.</span>
                                        </div>
                                        <div class="h-2 bg-slate-100 rounded-full overflow-hidden">
                                            <div class="h-full rounded-full {{ $barColor }}" style="width: {{ min(100, $pct ?? 0) }}%"></div>
                                        </div>
                                        <p class="text-[10px] text-slate-400 mt-1">Max: {{ number_format((float)$item->max_km, 0) }} km</p>
                                    </div>
                                @else
                                    <p class="text-sm text-slate-500 mt-1">{{ number_format((float)$item->current_km, 0) }} km acumulados</p>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </section>
            @endif

            @if($retiredGear->isNotEmpty())
                <section class="mb-6">
                    <h2 class="text-base font-semibold text-slate-500 mb-3">Retirado</h2>
                    <div class="space-y-2">
                        @foreach($retiredGear as $item)
                            <div class="bg-white/60 rounded-xl border border-slate-100 p-3 flex items-center justify-between opacity-60">
                                <div>
                                    <p class="font-medium text-slate-700 text-sm">{{ $item->brand }} {{ $item->model }}</p>
                                    <p class="text-xs text-slate-400">{{ number_format((float)$item->current_km, 0) }} km totales</p>
                                </div>
                                <form method="POST" action="{{ route('gear.destroy', $item) }}" onsubmit="return confirm('Eliminar?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="p-1.5 rounded-lg hover:bg-red-50 text-slate-300 hover:text-red-400 transition-colors">
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
