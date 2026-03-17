<x-app-layout>
    @section('page_title', 'Panel de Administración')

    <div class="max-w-5xl mx-auto px-4 py-6 space-y-6">

        {{-- Stats overview --}}
        <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm px-4 py-4 text-center">
                <p class="text-3xl font-extrabold text-slate-800">{{ $stats['total_users'] }}</p>
                <p class="text-xs text-slate-400 mt-1 font-medium uppercase tracking-wide">Usuarios</p>
            </div>
            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm px-4 py-4 text-center">
                <p class="text-3xl font-extrabold text-orange-500">{{ $stats['total_races'] }}</p>
                <p class="text-xs text-slate-400 mt-1 font-medium uppercase tracking-wide">Carreras</p>
            </div>
            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm px-4 py-4 text-center">
                <p class="text-3xl font-extrabold text-slate-800">{{ number_format((float) $stats['total_km'], 0) }}</p>
                <p class="text-xs text-slate-400 mt-1 font-medium uppercase tracking-wide">Km totales</p>
            </div>
            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm px-4 py-4 text-center">
                <p class="text-3xl font-extrabold text-slate-800">{{ number_format((float) $stats['total_spent'], 0) }}€</p>
                <p class="text-xs text-slate-400 mt-1 font-medium uppercase tracking-wide">Invertido</p>
            </div>
        </div>

        <div class="grid md:grid-cols-2 gap-4">
            {{-- Recent users --}}
            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
                <div class="flex items-center justify-between px-5 py-4 border-b border-slate-100">
                    <h2 class="font-bold text-slate-800">Últimos usuarios</h2>
                    <a href="{{ route('admin.users') }}" class="text-xs text-orange-500 font-semibold hover:text-orange-400">Ver todos →</a>
                </div>
                <div class="divide-y divide-slate-100">
                    @foreach($stats['recent_users'] as $user)
                        <div class="flex items-center gap-3 px-5 py-3">
                            <div class="w-8 h-8 rounded-full bg-orange-500 flex items-center justify-center text-white font-bold text-xs flex-shrink-0">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                            <div class="min-w-0 flex-1">
                                <p class="text-sm font-semibold text-slate-800 truncate flex items-center gap-1.5">
                                    {{ $user->name }}
                                    @if($user->is_admin)
                                        <span class="text-xs bg-orange-100 text-orange-600 px-1.5 py-0.5 rounded-md font-medium">Admin</span>
                                    @endif
                                </p>
                                <p class="text-xs text-slate-400 truncate">{{ $user->email }}</p>
                            </div>
                            <p class="text-xs text-slate-400 flex-shrink-0">{{ $user->created_at->diffForHumans() }}</p>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Recent races --}}
            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
                <div class="flex items-center justify-between px-5 py-4 border-b border-slate-100">
                    <h2 class="font-bold text-slate-800">Últimas carreras</h2>
                    <a href="{{ route('admin.races') }}" class="text-xs text-orange-500 font-semibold hover:text-orange-400">Ver todas →</a>
                </div>
                <div class="divide-y divide-slate-100">
                    @foreach($stats['recent_races'] as $race)
                        <div class="flex items-center gap-3 px-5 py-3">
                            <div class="w-10 text-center flex-shrink-0">
                                <p class="text-base font-bold text-slate-700 leading-none">{{ $race->date->format('d') }}</p>
                                <p class="text-xs text-slate-400 uppercase">{{ $race->date->translatedFormat('M') }}</p>
                            </div>
                            <div class="min-w-0 flex-1">
                                <p class="text-sm font-semibold text-slate-800 truncate">{{ $race->name }}</p>
                                <p class="text-xs text-slate-400 truncate">{{ $race->user->name }} · {{ $race->distance }} km</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

    </div>
</x-app-layout>
