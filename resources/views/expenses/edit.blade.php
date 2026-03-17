<x-app-layout>
    @section('page_title', 'Editar gasto')
    @section('back_url', route('expenses.index'))

    <main class="px-4 py-6 max-w-2xl mx-auto w-full pb-[76px]">

        <form method="POST" action="{{ route('expenses.update', $expense) }}" class="space-y-5">
            @csrf
            @method('PATCH')

            {{-- Amount + Currency --}}
            <div class="bg-white rounded-xl border border-slate-100 shadow-card p-4 space-y-4">
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.18em]">Importe</p>

                <div class="flex gap-3">
                    <div class="flex-1">
                        <label class="block text-sm font-medium text-slate-700 mb-1.5">Cantidad <span class="text-red-400">*</span></label>
                        <input type="number" name="amount" value="{{ old('amount', $expense->amount) }}"
                               step="0.01" min="0" placeholder="0.00"
                               class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl text-slate-900 focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary tabnum @error('amount') border-red-400 @enderror">
                        @error('amount') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div class="w-28">
                        <label class="block text-sm font-medium text-slate-700 mb-1.5">Divisa</label>
                        <select name="currency" class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl text-slate-900 focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary">
                            <option value="EUR" {{ old('currency', $expense->currency) === 'EUR' ? 'selected' : '' }}>EUR €</option>
                            <option value="USD" {{ old('currency', $expense->currency) === 'USD' ? 'selected' : '' }}>USD $</option>
                            <option value="GBP" {{ old('currency', $expense->currency) === 'GBP' ? 'selected' : '' }}>GBP £</option>
                        </select>
                    </div>
                </div>
            </div>

            {{-- Category --}}
            <div class="bg-white rounded-xl border border-slate-100 shadow-card p-4 space-y-4">
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.18em]">Categoría</p>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Categoría <span class="text-red-400">*</span></label>
                    <div class="grid grid-cols-2 gap-2">
                        @foreach([
                            'registration'  => ['label' => 'Inscripción',   'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2', 'color' => 'peer-checked:border-blue-500 peer-checked:bg-blue-50 peer-checked:text-blue-700'],
                            'travel'        => ['label' => 'Viaje',          'icon' => 'M12 19l9 2-9-18-9 18 9-2zm0 0v-8',                                                                                                     'color' => 'peer-checked:border-purple-500 peer-checked:bg-purple-50 peer-checked:text-purple-700'],
                            'accommodation' => ['label' => 'Alojamiento',   'icon' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6', 'color' => 'peer-checked:border-green-500 peer-checked:bg-green-50 peer-checked:text-green-700'],
                            'gear'          => ['label' => 'Equipamiento',  'icon' => 'M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z',                                                                                             'color' => 'peer-checked:border-orange-500 peer-checked:bg-orange-50 peer-checked:text-orange-700'],
                            'nutrition'     => ['label' => 'Nutrición',     'icon' => 'M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z',        'color' => 'peer-checked:border-emerald-500 peer-checked:bg-emerald-50 peer-checked:text-emerald-700'],
                            'other'         => ['label' => 'Otros',         'icon' => 'M5 12h.01M12 12h.01M19 12h.01M6 12a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0z',            'color' => 'peer-checked:border-slate-400 peer-checked:bg-slate-50 peer-checked:text-slate-700'],
                        ] as $value => $cfg)
                            <label class="cursor-pointer">
                                <input type="radio" name="category" value="{{ $value }}" class="sr-only peer"
                                       {{ old('category', $expense->category) === $value ? 'checked' : '' }}>
                                <div class="flex items-center gap-2.5 px-3 py-3 rounded-xl border-2 border-transparent bg-slate-50 font-semibold text-slate-500 text-sm
                                            {{ $cfg['color'] }} hover:bg-slate-100 transition-colors cursor-pointer">
                                    <svg class="w-4 h-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $cfg['icon'] }}"/>
                                    </svg>
                                    {{ $cfg['label'] }}
                                </div>
                            </label>
                        @endforeach
                    </div>
                    @error('category') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            {{-- Details --}}
            <div class="bg-white rounded-xl border border-slate-100 shadow-card p-4 space-y-4">
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.18em]">Detalles</p>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Descripción</label>
                    <input type="text" name="description" value="{{ old('description', $expense->description) }}"
                           placeholder="Ej. Hotel cerca de la salida..."
                           class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl text-slate-900 focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary">
                    @error('description') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Fecha <span class="text-red-400">*</span></label>
                    <input type="date" name="date" value="{{ old('date', $expense->date->format('Y-m-d')) }}"
                           class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl text-slate-900 focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary @error('date') border-red-400 @enderror">
                    @error('date') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Carrera asociada</label>
                    <select name="race_id" class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl text-slate-900 focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary">
                        <option value="">Sin carrera asociada</option>
                        @foreach($races as $race)
                            <option value="{{ $race->id }}" {{ old('race_id', $expense->race_id) == $race->id ? 'selected' : '' }}>
                                {{ $race->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('race_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <button type="submit" class="bg-primary hover:bg-orange-600 text-white font-semibold px-6 py-3 rounded-xl transition-colors w-full text-base">
                Guardar cambios
            </button>
        </form>

    </main>
</x-app-layout>
