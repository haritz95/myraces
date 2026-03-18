<x-app-layout>
    @section('page_title', $pod->name)
    @section('back_url', route('admin.pods.index'))

    @section('header_action')
        <a href="{{ route('admin.pods.edit', $pod) }}" class="btn btn-primary py-2 text-xs">Editar</a>
    @endsection

    @php
        $statusColor = match($pod->status) {
            'active'    => '#C8FA5F',
            'completed' => '#f59e0b',
            default     => 'rgba(255,255,255,0.40)',
        };
    @endphp

    <div class="max-w-4xl mx-auto px-4 py-6 space-y-5">

        @if(session('success'))
            <div class="card px-4 py-3 text-sm font-semibold text-green-400 bg-green-500/10 border-green-500/20">{{ session('success') }}</div>
        @endif

        <div class="grid md:grid-cols-2 gap-5">

            {{-- Pod info --}}
            <div class="card px-5 py-5 space-y-4">
                <div class="flex items-start justify-between gap-3">
                    <div>
                        <span class="text-[10px] font-black uppercase tracking-widest" style="color:{{ $statusColor }}">{{ $pod->statusLabel() }}</span>
                        <p class="text-lg font-black text-white mt-0.5">{{ $pod->name }}</p>
                        <p class="text-sm mt-0.5" style="color:rgba(255,255,255,0.45)">{{ $pod->goal }}</p>
                    </div>
                    <form method="POST" action="{{ route('admin.pods.destroy', $pod) }}"
                          onsubmit="return confirm('¿Eliminar «{{ $pod->name }}»? No se puede deshacer.')">
                        @csrf @method('DELETE')
                        <button type="submit" class="text-xs font-bold px-3 py-1.5 rounded-lg"
                                style="background:rgba(248,113,113,0.10);color:#f87171">Eliminar</button>
                    </form>
                </div>

                @if($pod->description)
                    <p class="text-sm" style="color:rgba(255,255,255,0.45)">{{ $pod->description }}</p>
                @endif

                <div class="grid grid-cols-2 gap-3 pt-1">
                    @foreach([
                        ['Creador',    $pod->creator?->name ?? '—'],
                        ['Miembros',   $members->count() . ' / ' . $pod->max_members],
                        ['Distancia',  $pod->target_distance ? $pod->target_distance . ' ' . $pod->target_unit : '—'],
                        ['Inicio',     $pod->starts_at?->format('d/m/Y') ?? '—'],
                        ['Fin',        $pod->ends_at?->format('d/m/Y') ?? '—'],
                        ['Creado',     $pod->created_at->diffForHumans()],
                    ] as [$label, $value])
                        <div>
                            <p class="text-[10px] font-bold uppercase tracking-wider mb-0.5" style="color:rgba(255,255,255,0.25)">{{ $label }}</p>
                            <p class="text-sm font-semibold text-white">{{ $value }}</p>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Leaderboard --}}
            <div class="card overflow-hidden">
                <p class="px-5 py-3.5 text-xs font-black uppercase tracking-widest" style="color:rgba(255,255,255,0.30);border-bottom:1px solid rgba(255,255,255,0.06)">
                    Miembros ({{ $members->count() }})
                </p>
                <div class="divide-y" style="divide-color:rgba(255,255,255,0.05)">
                    @forelse($members as $i => $member)
                        <div class="flex items-center gap-3 px-5 py-3">
                            <span class="text-sm w-5 text-center flex-shrink-0" style="color:rgba(255,255,255,0.30)">{{ $i + 1 }}</span>
                            <div class="w-7 h-7 rounded-full bg-white/10 flex items-center justify-center text-xs font-black text-white flex-shrink-0">
                                {{ strtoupper(substr($member->name, 0, 1)) }}
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-bold text-white truncate">
                                    {{ $member->name }}
                                    @if($member->pivot->role === 'leader')
                                        <span class="text-[9px] font-black px-1.5 py-0.5 rounded-full bg-primary/20 text-primary ml-1">LÍDER</span>
                                    @endif
                                </p>
                                <p class="text-xs" style="color:rgba(255,255,255,0.30)">{{ number_format($member->pivot->points) }} pts</p>
                            </div>
                            <form method="POST" action="{{ route('admin.pods.members.remove', [$pod, $member->id]) }}"
                                  onsubmit="return confirm('¿Expulsar a {{ $member->name }}?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-[11px] font-bold px-2 py-1 rounded-lg"
                                        style="background:rgba(248,113,113,0.10);color:#f87171">Expulsar</button>
                            </form>
                        </div>
                    @empty
                        <p class="px-5 py-6 text-sm text-center" style="color:rgba(255,255,255,0.30)">Sin miembros.</p>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Feed --}}
        <div class="card overflow-hidden">
            <p class="px-5 py-3.5 text-xs font-black uppercase tracking-widest" style="color:rgba(255,255,255,0.30);border-bottom:1px solid rgba(255,255,255,0.06)">
                Últimos mensajes ({{ $messages->count() }})
            </p>
            <div class="divide-y" style="divide-color:rgba(255,255,255,0.04)">
                @forelse($messages as $msg)
                    <div class="px-5 py-3 flex items-start gap-3">
                        @if($msg->type === 'system' || $msg->type === 'celebration')
                            <div class="flex-1">
                                <span class="text-xs px-2.5 py-1 rounded-full"
                                      style="background:rgba(255,255,255,0.06);color:rgba(255,255,255,0.40)">
                                    {{ $msg->message }}
                                </span>
                            </div>
                        @else
                            <div class="w-7 h-7 rounded-full bg-white/10 flex items-center justify-center text-xs font-black text-white flex-shrink-0 mt-0.5">
                                {{ strtoupper(substr($msg->user?->name ?? '?', 0, 1)) }}
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-baseline gap-2 mb-0.5">
                                    <span class="text-xs font-bold text-white">{{ $msg->user?->name ?? 'Sistema' }}</span>
                                    <span class="text-[10px]" style="color:rgba(255,255,255,0.25)">{{ $msg->created_at->diffForHumans() }}</span>
                                </div>
                                <p class="text-sm" style="color:rgba(255,255,255,0.70)">{{ $msg->message }}</p>
                            </div>
                        @endif
                    </div>
                @empty
                    <p class="px-5 py-6 text-sm text-center" style="color:rgba(255,255,255,0.30)">Sin mensajes.</p>
                @endforelse
            </div>
        </div>

    </div>
</x-app-layout>
