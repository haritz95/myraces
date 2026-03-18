<x-app-layout>
    @section('page_title', 'Gastos')
    @section('header_action')
        <a href="{{ route('expenses.create') }}"
           class="btn btn-primary text-sm py-2 px-4">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
            </svg>
            Añadir
        </a>
    @endsection

    <main class="px-5 py-6 max-w-2xl mx-auto w-full space-y-6">

        {{-- ── YEAR TOTAL HERO ───────────────────────────────── --}}
        <div class="relative overflow-hidden rounded-3xl px-6 py-8"
             style="background:linear-gradient(135deg,#0f1a00 0%,#1a2d00 50%,#253d00 100%);border:1px solid rgba(200,250,95,0.15)">
            <div class="absolute inset-0 opacity-[0.06]" style="background-image:radial-gradient(circle at 80% 20%, #C8FA5F 1px, transparent 1px);background-size:30px 30px"></div>
            <div class="relative flex items-center justify-between">
                <div>
                    <p class="section-label mb-3">Total {{ now()->year }}</p>
                    <p class="text-5xl font-black text-white tabnum leading-none">
                        {{ number_format((float) $yearlyTotal, 2) }}<span class="text-2xl ml-1" style="color:rgba(255,255,255,0.35)">€</span>
                    </p>
                    <p class="text-xs font-medium mt-2" style="color:rgba(255,255,255,0.30)">en gastos registrados</p>
                </div>
                <div class="w-14 h-14 rounded-2xl flex items-center justify-center flex-shrink-0" style="background:rgba(200,250,95,0.10);border:1px solid rgba(200,250,95,0.20)">
                    <svg class="w-7 h-7 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>

        {{-- ── CATEGORY BREAKDOWN ────────────────────────────── --}}
        @if($totalByCategory->isNotEmpty())
            <div class="card p-5">
                <p class="section-label mb-4">Por categoría</p>
                @php
                    $categoryColors = [
                        'registration'  => '#60a5fa',
                        'travel'        => '#a78bfa',
                        'accommodation' => '#4ade80',
                        'gear'          => '#fb923c',
                        'nutrition'     => '#34d399',
                        'other'         => '#6b7280',
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
                <div class="space-y-3">
                    @foreach($totalByCategory as $category => $amount)
                        @php
                            $pct = $grandTotal > 0 ? ($amount / $grandTotal) * 100 : 0;
                            $color = $categoryColors[$category] ?? '#6b7280';
                        @endphp
                        <div class="flex items-center gap-3">
                            <div class="w-2 h-2 rounded-full flex-shrink-0" style="background:{{ $color }}"></div>
                            <span class="text-xs font-bold w-28 flex-shrink-0" style="color:rgba(255,255,255,0.60)">{{ $categoryLabels[$category] ?? $category }}</span>
                            <div class="flex-1 rounded-full h-1.5 overflow-hidden" style="background:rgba(255,255,255,0.08)">
                                <div class="h-full rounded-full" style="width:{{ $pct }}%;background:{{ $color }}"></div>
                            </div>
                            <span class="text-xs font-black text-white tabnum w-16 text-right flex-shrink-0">{{ number_format($amount, 2) }}€</span>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- ── EXPENSE LIST ──────────────────────────────────── --}}
        <div>
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-base font-black text-white">Todos los gastos</h2>
                <span class="text-xs font-bold" style="color:rgba(255,255,255,0.35)">{{ $expenses->total() }} registros</span>
            </div>

            @if($expenses->isEmpty())
                <div class="card px-5 py-12 text-center">
                    <div class="w-14 h-14 rounded-2xl bg-primary/10 border border-primary/20 flex items-center justify-center mx-auto mb-3">
                        <svg class="w-7 h-7 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <p class="text-white font-black mb-1">Sin gastos registrados</p>
                    <p class="text-sm mb-4" style="color:rgba(255,255,255,0.35)">Empieza a registrar tus gastos de running</p>
                    <a href="{{ route('expenses.create') }}" class="btn btn-primary px-6">Añadir gasto</a>
                </div>
            @else
                <div class="space-y-2">
                    @foreach($expenses as $expense)
                        @php
                            $badgeColor = $categoryColors[$expense->category] ?? '#6b7280';
                        @endphp
                        <div class="card p-4 flex items-center gap-4">
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-2 mb-1">
                                    <span class="text-[10px] font-black uppercase tracking-wider px-2 py-0.5 rounded-full"
                                          style="background:{{ $badgeColor }}18;color:{{ $badgeColor }}">
                                        {{ $categoryLabels[$expense->category] ?? $expense->category }}
                                    </span>
                                    @if($expense->race)
                                        <span class="text-[10px] truncate" style="color:rgba(255,255,255,0.30)">{{ $expense->race->name }}</span>
                                    @endif
                                </div>
                                @if($expense->description)
                                    <p class="text-sm font-black text-white truncate">{{ $expense->description }}</p>
                                @endif
                                <p class="text-xs mt-0.5" style="color:rgba(255,255,255,0.35)">{{ $expense->date->format('d M Y') }}</p>
                            </div>
                            <div class="flex items-center gap-3 flex-shrink-0">
                                <p class="text-base font-black text-white tabnum">{{ number_format($expense->amount, 2) }}<span class="text-xs ml-0.5" style="color:rgba(255,255,255,0.40)">{{ $expense->currency }}</span></p>
                                <div class="flex items-center gap-1">
                                    <a href="{{ route('expenses.edit', $expense) }}" class="w-8 h-8 flex items-center justify-center rounded-xl transition-colors" style="color:rgba(255,255,255,0.30)" onmouseover="this.style.background='rgba(255,255,255,0.08)';this.style.color='white'" onmouseout="this.style.background='';this.style.color='rgba(255,255,255,0.30)'">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </a>
                                    <form method="POST" action="{{ route('expenses.destroy', $expense) }}" onsubmit="return confirm('¿Eliminar este gasto?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="w-8 h-8 flex items-center justify-center rounded-xl transition-colors" style="color:rgba(255,255,255,0.30)" onmouseover="this.style.background='rgba(248,113,113,0.15)';this.style.color='#f87171'" onmouseout="this.style.background='';this.style.color='rgba(255,255,255,0.30)'">
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

                @if($expenses->hasPages())
                    <div class="mt-6">{{ $expenses->links() }}</div>
                @endif
            @endif
        </div>

    </main>
</x-app-layout>
