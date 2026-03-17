<x-app-layout>
    @section('page_title', 'Gastos')
    @section('header_action')
        <a href="{{ route('expenses.create') }}"
           class="bg-primary hover:bg-orange-600 text-white font-semibold px-4 py-2 rounded-xl text-sm transition-colors flex items-center gap-1.5">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
            </svg>
            Añadir
        </a>
    @endsection

    <main class="px-4 py-6 max-w-2xl mx-auto w-full pb-[76px]">

        {{-- ── YEAR TOTAL HERO ───────────────────────────────── --}}
        <div class="relative overflow-hidden rounded-xl px-6 py-7 mb-6 flex items-center justify-between"
             style="background: linear-gradient(135deg, #f59e0b 0%, #ea580c 100%); box-shadow: 0 8px 24px rgba(234,88,12,0.35)">
            <div class="absolute inset-0 opacity-[0.08]" style="background-image: radial-gradient(circle at 80% 20%, white 1px, transparent 1px); background-size: 30px 30px"></div>
            <div class="relative">
                <p class="text-[10px] font-bold uppercase tracking-widest mb-2" style="color:rgba(255,255,255,0.50)">
                    Total {{ now()->year }}
                </p>
                <p class="text-5xl font-bold text-white tabnum leading-none">
                    {{ number_format((float) $yearlyTotal, 2) }}<span class="text-2xl ml-1" style="color:rgba(255,255,255,0.40)">€</span>
                </p>
                <p class="text-xs font-medium mt-2" style="color:rgba(255,255,255,0.40)">en gastos registrados</p>
            </div>
            <div class="w-14 h-14 rounded-xl flex items-center justify-center flex-shrink-0 relative" style="background:rgba(255,255,255,0.15)">
                <svg class="w-7 h-7 text-white/60" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
        </div>

        {{-- ── CATEGORY BREAKDOWN ────────────────────────────── --}}
        @if($totalByCategory->isNotEmpty())
            <div class="bg-white rounded-xl border border-slate-100 shadow-card p-4 mb-6">
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.18em] mb-3">Por categoría</p>
                <div class="space-y-2.5">
                    @php
                        $categoryColors = [
                            'registration'  => 'bg-blue-500',
                            'travel'        => 'bg-purple-500',
                            'accommodation' => 'bg-green-500',
                            'gear'          => 'bg-orange-500',
                            'nutrition'     => 'bg-emerald-500',
                            'other'         => 'bg-slate-400',
                        ];
                        $categoryLabels = [
                            'registration'  => 'Inscripción',
                            'travel'        => 'Viaje',
                            'accommodation' => 'Alojamiento',
                            'gear'          => 'Equipamiento',
                            'nutrition'     => 'Nutrición',
                            'other'         => 'Otros',
                        ];
                        $grandTotal = $totalByCategory->sum();
                    @endphp
                    @foreach($totalByCategory as $category => $amount)
                        @php $pct = $grandTotal > 0 ? ($amount / $grandTotal) * 100 : 0; @endphp
                        <div class="flex items-center gap-3">
                            <div class="w-2 h-2 rounded-full flex-shrink-0 {{ $categoryColors[$category] ?? 'bg-slate-400' }}"></div>
                            <span class="text-xs font-medium text-slate-600 w-28 flex-shrink-0">{{ $categoryLabels[$category] ?? $category }}</span>
                            <div class="flex-1 bg-slate-100 rounded-full h-1.5 overflow-hidden">
                                <div class="h-full rounded-full {{ $categoryColors[$category] ?? 'bg-slate-400' }}" style="width: {{ $pct }}%"></div>
                            </div>
                            <span class="text-xs font-bold text-slate-700 tabnum w-16 text-right flex-shrink-0">{{ number_format($amount, 2) }}€</span>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- ── EXPENSE LIST ──────────────────────────────────── --}}
        <div class="flex items-center justify-between mb-3">
            <h2 class="text-base font-bold text-slate-900">Todos los gastos</h2>
            <span class="text-xs text-slate-400">{{ $expenses->total() }} registros</span>
        </div>

        @if($expenses->isEmpty())
            <div class="bg-white rounded-xl border border-slate-100 shadow-card px-5 py-12 text-center">
                <div class="w-14 h-14 rounded-xl bg-amber-50 flex items-center justify-center mx-auto mb-3">
                    <svg class="w-7 h-7 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <p class="text-slate-700 font-bold mb-1">Sin gastos registrados</p>
                <p class="text-slate-400 text-sm mb-4">Empieza a registrar tus gastos de running</p>
                <a href="{{ route('expenses.create') }}" class="bg-primary hover:bg-orange-600 text-white font-semibold px-5 py-2.5 rounded-xl text-sm transition-colors inline-block">
                    Añadir gasto
                </a>
            </div>
        @else
            <div class="space-y-2">
                @foreach($expenses as $expense)
                    @php
                        $badgeColors = [
                            'registration'  => 'bg-blue-100 text-blue-700',
                            'travel'        => 'bg-purple-100 text-purple-700',
                            'accommodation' => 'bg-green-100 text-green-700',
                            'gear'          => 'bg-orange-100 text-orange-700',
                            'nutrition'     => 'bg-emerald-100 text-emerald-700',
                            'other'         => 'bg-slate-100 text-slate-600',
                        ];
                        $badgeColor = $badgeColors[$expense->category] ?? 'bg-slate-100 text-slate-600';
                    @endphp
                    <div class="bg-white rounded-xl border border-slate-100 shadow-card p-4 flex items-center gap-3">
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2 mb-1">
                                <span class="text-[10px] font-bold uppercase tracking-wider px-2 py-0.5 rounded-full {{ $badgeColor }}">
                                    {{ $categoryLabels[$expense->category] ?? $expense->category }}
                                </span>
                                @if($expense->race)
                                    <span class="text-[10px] text-slate-400 truncate">{{ $expense->race->name }}</span>
                                @endif
                            </div>
                            @if($expense->description)
                                <p class="text-sm font-semibold text-slate-800 truncate">{{ $expense->description }}</p>
                            @endif
                            <p class="text-xs text-slate-400 mt-0.5">{{ $expense->date->format('d M Y') }}</p>
                        </div>
                        <div class="flex items-center gap-3 flex-shrink-0">
                            <p class="text-base font-bold text-slate-900 tabnum">{{ number_format($expense->amount, 2) }}<span class="text-xs text-slate-400 ml-0.5">{{ $expense->currency }}</span></p>
                            <div class="flex items-center gap-1">
                                <a href="{{ route('expenses.edit', $expense) }}" class="w-8 h-8 flex items-center justify-center rounded-lg hover:bg-slate-50 text-slate-400 hover:text-slate-600 transition-colors">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </a>
                                <form method="POST" action="{{ route('expenses.destroy', $expense) }}" onsubmit="return confirm('¿Eliminar este gasto?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="w-8 h-8 flex items-center justify-center rounded-lg hover:bg-red-50 text-slate-400 hover:text-red-500 transition-colors">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Pagination --}}
            @if($expenses->hasPages())
                <div class="mt-6">
                    {{ $expenses->links() }}
                </div>
            @endif
        @endif

    </main>
</x-app-layout>
