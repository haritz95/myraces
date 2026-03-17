<x-app-layout>
    @section('page_title', 'Usuarios')
    @section('back_url', route('admin.dashboard'))

    <div class="max-w-4xl mx-auto px-4 py-6 space-y-4">

        {{-- Search --}}
        <form method="GET" action="{{ route('admin.users') }}">
            <div class="relative">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="Buscar por nombre o email..."
                       class="input-field pl-10">
            </div>
        </form>

        {{-- Users list --}}
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
            <div class="px-5 py-3 border-b border-slate-100 bg-slate-50">
                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wide">{{ $users->total() }} usuarios</p>
            </div>
            <div class="divide-y divide-slate-100">
                @forelse($users as $user)
                    <div class="flex items-center gap-3 px-5 py-3.5">
                        <div class="w-9 h-9 rounded-full bg-orange-500 flex items-center justify-center text-white font-bold text-sm flex-shrink-0">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                        <div class="min-w-0 flex-1">
                            <p class="text-sm font-semibold text-slate-800 flex items-center gap-2">
                                {{ $user->name }}
                                @if($user->is_admin)
                                    <span class="text-xs bg-orange-100 text-orange-600 px-1.5 py-0.5 rounded-md font-medium">Admin</span>
                                @endif
                            </p>
                            <p class="text-xs text-slate-400">{{ $user->email }} · {{ $user->races_count }} carreras · Registrado {{ $user->created_at->diffForHumans() }}</p>
                        </div>
                        @if($user->id !== auth()->id())
                            <form method="POST" action="{{ route('admin.users.toggle-admin', $user) }}">
                                @csrf
                                @method('PATCH')
                                <button type="submit"
                                        class="text-xs px-3 py-1.5 rounded-lg font-medium border transition flex-shrink-0
                                               {{ $user->is_admin ? 'border-orange-200 text-orange-600 hover:bg-orange-50' : 'border-slate-200 text-slate-500 hover:bg-slate-50' }}">
                                    {{ $user->is_admin ? 'Quitar admin' : 'Hacer admin' }}
                                </button>
                            </form>
                        @else
                            <span class="text-xs text-slate-300 flex-shrink-0">Tú</span>
                        @endif
                    </div>
                @empty
                    <div class="px-5 py-10 text-center text-slate-400 text-sm">No se encontraron usuarios.</div>
                @endforelse
            </div>
        </div>

        {{ $users->withQueryString()->links() }}
    </div>
</x-app-layout>
