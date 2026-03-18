<x-app-layout>
    @section('page_title', 'Pods')
    @section('back_url', route('admin.dashboard'))

    <div class="max-w-5xl mx-auto px-4 py-6 space-y-5">

        @if(session('success'))
            <div class="card px-4 py-3 text-sm font-semibold text-green-400 bg-green-500/10 border-green-500/20">{{ session('success') }}</div>
        @endif

        {{-- Stats --}}
        <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
            @foreach([
                [$stats['total'],     'Total',      'rgba(255,255,255,0.70)'],
                [$stats['active'],    'Activos',    '#C8FA5F'],
                [$stats['completed'], 'Completados','#f59e0b'],
                [$stats['archived'],  'Archivados', 'rgba(255,255,255,0.30)'],
            ] as [$val, $label, $color])
                <div class="card px-4 py-4 text-center">
                    <p class="text-2xl font-black tabnum" style="color:{{ $color }}">{{ $val }}</p>
                    <p class="text-[10px] font-bold uppercase tracking-wider mt-1.5" style="color:rgba(255,255,255,0.30)">{{ $label }}</p>
                </div>
            @endforeach
        </div>

        {{-- Filters --}}
        <form method="GET" class="flex gap-2 flex-wrap">
            <input type="text" name="search" value="{{ request('search') }}"
                   placeholder="Buscar por nombre o objetivo..."
                   class="input-field text-sm py-2 flex-1 min-w-[200px]">
            <select name="status" class="input-field text-sm py-2 w-36">
                <option value="">Todos</option>
                <option value="active"    {{ request('status') === 'active'    ? 'selected' : '' }}>Activos</option>
                <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completados</option>
                <option value="archived"  {{ request('status') === 'archived'  ? 'selected' : '' }}>Archivados</option>
            </select>
            <button type="submit" class="btn btn-primary text-sm py-2 px-4">Filtrar</button>
            @if(request('search') || request('status'))
                <a href="{{ route('admin.pods.index') }}" class="btn btn-secondary text-sm py-2 px-4">Limpiar</a>
            @endif
        </form>

        {{-- Table --}}
        <div class="card overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr style="border-bottom:1px solid rgba(255,255,255,0.06)">
                            <th class="text-left px-5 py-3 text-[11px] font-black uppercase tracking-widest" style="color:rgba(255,255,255,0.30)">Pod</th>
                            <th class="text-left px-4 py-3 text-[11px] font-black uppercase tracking-widest hidden md:table-cell" style="color:rgba(255,255,255,0.30)">Creador</th>
                            <th class="text-center px-4 py-3 text-[11px] font-black uppercase tracking-widest" style="color:rgba(255,255,255,0.30)">Miembros</th>
                            <th class="text-center px-4 py-3 text-[11px] font-black uppercase tracking-widest" style="color:rgba(255,255,255,0.30)">Estado</th>
                            <th class="text-right px-5 py-3 text-[11px] font-black uppercase tracking-widest" style="color:rgba(255,255,255,0.30)">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y" style="divide-color:rgba(255,255,255,0.04)">
                        @forelse($pods as $pod)
                            @php
                                $statusColor = match($pod->status) {
                                    'active'    => '#C8FA5F',
                                    'completed' => '#f59e0b',
                                    default     => 'rgba(255,255,255,0.30)',
                                };
                            @endphp
                            <tr class="hover:bg-white/[0.02] transition-colors">
                                <td class="px-5 py-3.5">
                                    <p class="font-bold text-white">{{ $pod->name }}</p>
                                    <p class="text-xs mt-0.5 truncate max-w-[220px]" style="color:rgba(255,255,255,0.35)">{{ $pod->goal }}</p>
                                </td>
                                <td class="px-4 py-3.5 hidden md:table-cell" style="color:rgba(255,255,255,0.50)">
                                    {{ $pod->creator?->name ?? '—' }}
                                </td>
                                <td class="px-4 py-3.5 text-center">
                                    <span class="font-bold text-white">{{ $pod->members_count }}</span>
                                    <span style="color:rgba(255,255,255,0.30)">/{{ $pod->max_members }}</span>
                                </td>
                                <td class="px-4 py-3.5 text-center">
                                    <span class="text-[10px] font-black px-2 py-1 rounded-full"
                                          style="background:{{ $statusColor }}20;color:{{ $statusColor }}">
                                        {{ $pod->statusLabel() }}
                                    </span>
                                </td>
                                <td class="px-5 py-3.5">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('admin.pods.show', $pod) }}"
                                           class="text-xs font-bold px-3 py-1.5 rounded-lg transition-colors"
                                           style="background:rgba(200,250,95,0.10);color:#C8FA5F">Ver</a>
                                        <a href="{{ route('admin.pods.edit', $pod) }}"
                                           class="text-xs font-bold px-3 py-1.5 rounded-lg transition-colors"
                                           style="background:rgba(255,255,255,0.07);color:rgba(255,255,255,0.60)">Editar</a>
                                        <form method="POST" action="{{ route('admin.pods.destroy', $pod) }}"
                                              onsubmit="return confirm('¿Eliminar el Pod «{{ $pod->name }}»? Esta acción no se puede deshacer.')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="text-xs font-bold px-3 py-1.5 rounded-lg transition-colors"
                                                    style="background:rgba(248,113,113,0.10);color:#f87171">Eliminar</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-5 py-12 text-center text-sm" style="color:rgba(255,255,255,0.30)">
                                    No hay pods que coincidan con tu búsqueda.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($pods->hasPages())
                <div class="px-5 py-3" style="border-top:1px solid rgba(255,255,255,0.06)">
                    {{ $pods->withQueryString()->links() }}
                </div>
            @endif
        </div>

    </div>
</x-app-layout>
