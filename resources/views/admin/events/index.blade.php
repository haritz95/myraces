<x-app-layout>
    @section('page_title', 'Catálogo de Carreras')
    @section('back_url', route('admin.dashboard'))

    @section('header_action')
        <a href="{{ route('admin.events.create') }}" class="btn btn-primary py-2 text-xs">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
            </svg>
            Nueva carrera
        </a>
    @endsection

    <div class="max-w-5xl mx-auto px-4 py-6 space-y-5">

        @if(session('success'))
            <div class="card px-4 py-3 text-sm font-semibold text-green-400 bg-green-500/10 border-green-500/20">{{ session('success') }}</div>
        @endif

        {{-- Stats --}}
        <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
            @foreach([
                [$stats['total'],     'Total',     'rgba(255,255,255,0.70)'],
                [$stats['open'],      'Abiertas',  'rgb(var(--color-primary))'],
                [$stats['upcoming'],  'Próximas',  '#60a5fa'],
                [$stats['cancelled'], 'Canceladas','#f87171'],
            ] as [$val, $label, $color])
                <div class="card px-4 py-4 text-center">
                    <p class="text-2xl font-black tabnum" style="color:{{ $color }}">{{ $val }}</p>
                    <p class="text-[10px] font-bold uppercase tracking-wider mt-1.5" style="color:rgba(255,255,255,0.30)">{{ $label }}</p>
                </div>
            @endforeach
        </div>

        {{-- Filters --}}
        <form method="GET" class="flex gap-2 flex-wrap">
            <input type="text" name="q" value="{{ request('q') }}"
                   placeholder="Buscar nombre, ciudad..."
                   class="input-field text-sm py-2 flex-1 min-w-[200px]">
            <select name="status" class="input-field text-sm py-2 w-44">
                <option value="">Todos los estados</option>
                @foreach(['upcoming' => 'Próximas', 'open' => 'Abiertas', 'cancelled' => 'Canceladas', 'past' => 'Finalizadas'] as $val => $label)
                    <option value="{{ $val }}" {{ request('status') === $val ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>
            <button type="submit" class="btn btn-primary text-sm py-2 px-4">Filtrar</button>
            @if(request('q') || request('status'))
                <a href="{{ route('admin.events.index') }}" class="btn btn-secondary text-sm py-2 px-4">Limpiar</a>
            @endif
        </form>

        {{-- Table --}}
        <div class="card overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr style="border-bottom:1px solid rgba(255,255,255,0.06)">
                            <th class="text-left px-5 py-3 text-[11px] font-black uppercase tracking-widest" style="color:rgba(255,255,255,0.30)">Carrera</th>
                            <th class="text-left px-4 py-3 text-[11px] font-black uppercase tracking-widest hidden md:table-cell" style="color:rgba(255,255,255,0.30)">Fecha</th>
                            <th class="text-center px-4 py-3 text-[11px] font-black uppercase tracking-widest hidden md:table-cell" style="color:rgba(255,255,255,0.30)">Tipo</th>
                            <th class="text-center px-4 py-3 text-[11px] font-black uppercase tracking-widest" style="color:rgba(255,255,255,0.30)">Estado</th>
                            <th class="text-center px-4 py-3 text-[11px] font-black uppercase tracking-widest" style="color:rgba(255,255,255,0.30)">Apuntados</th>
                            <th class="text-right px-5 py-3 text-[11px] font-black uppercase tracking-widest" style="color:rgba(255,255,255,0.30)">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y" style="divide-color:rgba(255,255,255,0.04)">
                        @forelse($events as $event)
                            <tr class="hover:bg-white/[0.02] transition-colors">
                                <td class="px-5 py-3.5">
                                    <div class="flex items-center gap-3">
                                        @if($event->image)
                                            <img src="{{ asset('storage/' . $event->image) }}" alt=""
                                                 class="w-10 h-10 rounded-xl object-cover flex-shrink-0">
                                        @else
                                            <div class="w-10 h-10 rounded-xl bg-primary/10 flex items-center justify-center flex-shrink-0">
                                                <svg class="w-5 h-5 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 21v-4m0 0V5a2 2 0 012-2h6.5l1 1H21l-3 6 3 6h-8.5l-1-1H5a2 2 0 00-2 2zm9-13.5V9"/>
                                                </svg>
                                            </div>
                                        @endif
                                        <div class="min-w-0">
                                            <p class="font-bold text-white truncate">
                                                {{ $event->name }}
                                                @if($event->is_featured)
                                                    <span class="text-[9px] bg-primary/20 text-primary px-1.5 py-0.5 rounded font-black ml-1">★</span>
                                                @endif
                                            </p>
                                            <p class="text-xs truncate" style="color:rgba(255,255,255,0.35)">{{ $event->location }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-3.5 hidden md:table-cell" style="color:rgba(255,255,255,0.50)">
                                    {{ $event->event_date->format('d/m/Y') }}
                                </td>
                                <td class="px-4 py-3.5 text-center hidden md:table-cell">
                                    <span class="text-xs font-semibold" style="color:rgba(255,255,255,0.45)">{{ $event->category }}</span>
                                </td>
                                <td class="px-4 py-3.5 text-center">
                                    <span class="text-[10px] font-black px-2 py-1 rounded-full"
                                          style="background:{{ $event->statusColor() }}20;color:{{ $event->statusColor() }}">
                                        {{ $event->statusLabel() }}
                                    </span>
                                </td>
                                <td class="px-4 py-3.5 text-center font-bold text-white">
                                    {{ $event->attendees_count }}
                                </td>
                                <td class="px-5 py-3.5">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('events.show', $event) }}" target="_blank"
                                           class="text-xs font-bold px-3 py-1.5 rounded-lg"
                                           style="background:rgba(255,255,255,0.07);color:rgba(255,255,255,0.60)">Ver</a>
                                        <a href="{{ route('admin.events.edit', $event) }}"
                                           class="text-xs font-bold px-3 py-1.5 rounded-lg"
                                           style="background:rgb(var(--color-primary) / 0.10);color:rgb(var(--color-primary))">Editar</a>
                                        <form method="POST" action="{{ route('admin.events.destroy', $event) }}"
                                              onsubmit="return confirm('¿Eliminar «{{ $event->name }}»?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="text-xs font-bold px-3 py-1.5 rounded-lg"
                                                    style="background:rgba(248,113,113,0.10);color:#f87171">Eliminar</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-5 py-12 text-center text-sm" style="color:rgba(255,255,255,0.30)">
                                    No hay carreras. <a href="{{ route('admin.events.create') }}" class="text-primary font-bold">Añade la primera →</a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($events->hasPages())
                <div class="px-5 py-3" style="border-top:1px solid rgba(255,255,255,0.06)">
                    {{ $events->withQueryString()->links() }}
                </div>
            @endif
        </div>

    </div>
</x-app-layout>
