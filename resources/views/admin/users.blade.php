<x-app-layout>
    @section('page_title', 'Usuarios')
    @section('back_url', route('admin.dashboard'))

    <div class="max-w-4xl mx-auto px-4 py-6 space-y-4">

        {{-- Search & filter --}}
        <form method="GET" action="{{ route('admin.users') }}" class="flex gap-2">
            <div class="relative flex-1">
                <svg class="w-4 h-4 absolute left-3.5 top-1/2 -translate-y-1/2" style="color:rgba(255,255,255,0.25)" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="Buscar por nombre o email..."
                       class="input-field pl-10">
            </div>
            <select name="filter" onchange="this.form.submit()" class="input-field w-auto pr-8">
                <option value="">Todos</option>
                <option value="admin" {{ request('filter') === 'admin' ? 'selected' : '' }}>Admins</option>
                <option value="banned" {{ request('filter') === 'banned' ? 'selected' : '' }}>Baneados</option>
            </select>
        </form>

        {{-- Users list --}}
        <div class="card overflow-hidden">
            <div class="px-5 py-3" style="border-bottom:1px solid rgba(255,255,255,0.06)">
                <p class="text-[10px] font-bold uppercase tracking-wider" style="color:rgba(255,255,255,0.30)">{{ $users->total() }} usuarios</p>
            </div>
            <div>
                @forelse($users as $user)
                    <div class="flex items-center gap-3 px-5 py-3.5 hover:bg-white/[0.03] transition-colors" style="border-bottom:1px solid rgba(255,255,255,0.04)">

                        <div class="w-9 h-9 rounded-full flex items-center justify-center text-black font-black text-sm flex-shrink-0
                                    {{ $user->is_banned ? 'bg-red-400' : 'bg-primary' }}">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>

                        <div class="min-w-0 flex-1">
                            <p class="text-sm font-semibold text-white flex flex-wrap items-center gap-1.5">
                                {{ $user->name }}
                                @if($user->is_admin)
                                    <span class="text-[10px] bg-primary/20 text-primary px-1.5 py-0.5 rounded font-bold">Admin</span>
                                @endif
                                @if($user->is_banned)
                                    <span class="text-[10px] bg-red-500/20 text-red-400 px-1.5 py-0.5 rounded font-bold">Baneado</span>
                                @endif
                            </p>
                            <p class="text-xs mt-0.5" style="color:rgba(255,255,255,0.35)">
                                {{ $user->email }} · {{ $user->races_count }} carreras · {{ $user->created_at->diffForHumans() }}
                            </p>
                        </div>

                        @if($user->id !== auth()->id())
                            <div class="flex items-center gap-2 flex-shrink-0">

                                {{-- Toggle admin --}}
                                <form method="POST" action="{{ route('admin.users.toggle-admin', $user) }}">
                                    @csrf @method('PATCH')
                                    <button type="submit"
                                            class="text-[11px] px-2.5 py-1.5 rounded-lg font-bold border transition
                                                   {{ $user->is_admin
                                                        ? 'border-primary/30 text-primary bg-primary/10 hover:bg-primary/20'
                                                        : 'border-white/10 text-white/40 hover:text-white hover:border-white/20' }}">
                                        {{ $user->is_admin ? 'Quitar admin' : 'Admin' }}
                                    </button>
                                </form>

                                {{-- Toggle ban --}}
                                <form method="POST" action="{{ route('admin.users.toggle-ban', $user) }}">
                                    @csrf @method('PATCH')
                                    <button type="submit"
                                            class="text-[11px] px-2.5 py-1.5 rounded-lg font-bold border transition
                                                   {{ $user->is_banned
                                                        ? 'border-green-500/30 text-green-400 bg-green-500/10 hover:bg-green-500/20'
                                                        : 'border-red-500/20 text-red-400 hover:bg-red-500/10 hover:border-red-500/30' }}">
                                        {{ $user->is_banned ? 'Desbanear' : 'Banear' }}
                                    </button>
                                </form>

                                {{-- Delete --}}
                                <form method="POST" action="{{ route('admin.users.destroy', $user) }}"
                                      onsubmit="return confirm('¿Eliminar a {{ addslashes($user->name) }}? Esta acción es irreversible.')">
                                    @csrf @method('DELETE')
                                    <button type="submit"
                                            class="w-7 h-7 flex items-center justify-center rounded-lg border border-red-500/20 text-red-400 hover:bg-red-500/15 transition">
                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        @else
                            <span class="text-xs flex-shrink-0" style="color:rgba(255,255,255,0.25)">Tú</span>
                        @endif
                    </div>
                @empty
                    <div class="px-5 py-12 text-center">
                        <p class="text-sm" style="color:rgba(255,255,255,0.35)">No se encontraron usuarios.</p>
                    </div>
                @endforelse
            </div>
        </div>

        {{ $users->withQueryString()->links() }}
    </div>
</x-app-layout>
