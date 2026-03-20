{{-- Amount + Currency --}}
<div class="card p-5 space-y-4">
    <p class="section-label">Importe</p>
    <div class="flex gap-3">
        <div class="flex-1">
            <label class="block text-sm font-bold text-white mb-2">Cantidad <span class="text-primary">*</span></label>
            <input type="number" name="amount" value="{{ old('amount', $expense->amount ?? '') }}"
                   step="0.01" min="0" placeholder="0.00"
                   class="input-field tabnum @error('amount') error @enderror">
            @error('amount') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
        </div>
        <div class="w-28">
            <label class="block text-sm font-bold text-white mb-2">Divisa</label>
            <select name="currency" class="input-field">
                <option value="EUR" {{ old('currency', $expense->currency ?? 'EUR') === 'EUR' ? 'selected' : '' }}>EUR €</option>
                <option value="USD" {{ old('currency', $expense->currency ?? '') === 'USD' ? 'selected' : '' }}>USD $</option>
                <option value="GBP" {{ old('currency', $expense->currency ?? '') === 'GBP' ? 'selected' : '' }}>GBP £</option>
            </select>
        </div>
    </div>
</div>

{{-- Category --}}
<div class="card p-5 space-y-4">
    <p class="section-label">Categoría</p>
    <div class="grid grid-cols-2 gap-2">
        @foreach([
            'registration'  => ['label' => 'Inscripción',  'color' => '#60a5fa', 'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2'],
            'travel'        => ['label' => 'Viaje',         'color' => '#a78bfa', 'icon' => 'M12 19l9 2-9-18-9 18 9-2zm0 0v-8'],
            'accommodation' => ['label' => 'Alojamiento',  'color' => '#4ade80', 'icon' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6'],
            'gear'          => ['label' => 'Equipamiento', 'color' => '#fb923c', 'icon' => 'M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z'],
            'nutrition'     => ['label' => 'Nutrición',    'color' => '#34d399', 'icon' => 'M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z'],
            'other'         => ['label' => 'Otros',        'color' => '#6b7280', 'icon' => 'M5 12h.01M12 12h.01M19 12h.01M6 12a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0z'],
        ] as $value => $cfg)
            <label class="cursor-pointer">
                <input type="radio" name="category" value="{{ $value }}" class="sr-only"
                       id="cat_{{ $value }}"
                       {{ old('category', $expense->category ?? 'registration') === $value ? 'checked' : '' }}>
                <div class="flex items-center gap-2.5 px-3 py-3 rounded-2xl font-bold text-sm transition-all cursor-pointer"
                     id="cat_label_{{ $value }}"
                     style="background:rgba(255,255,255,0.06);color:rgba(255,255,255,0.45);border:2px solid transparent">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $cfg['icon'] }}"/>
                    </svg>
                    {{ $cfg['label'] }}
                </div>
            </label>
        @endforeach
    </div>
    @error('category') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
</div>

{{-- Details --}}
<div class="card p-5 space-y-4">
    <p class="section-label">Detalles</p>

    <div>
        <label class="block text-sm font-bold text-white mb-2">Descripción</label>
        <input type="text" name="description" value="{{ old('description', $expense->description ?? '') }}"
               placeholder="Ej. Hotel cerca de la salida..."
               class="input-field">
        @error('description') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
    </div>

    <div>
        <label class="block text-sm font-bold text-white mb-2">Fecha <span class="text-primary">*</span></label>
        <input type="date" name="date" value="{{ old('date', $expense ? $expense->date->format('Y-m-d') : now()->format('Y-m-d')) }}"
               class="input-field @error('date') error @enderror">
        @error('date') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
    </div>

    <div>
        <label class="block text-sm font-bold text-white mb-2">Carrera asociada</label>
        <select name="race_id" class="input-field">
            <option value="">Sin carrera asociada</option>
            @foreach($races as $race)
                <option value="{{ $race->id }}" {{ old('race_id', $expense->race_id ?? '') == $race->id ? 'selected' : '' }}>
                    {{ $race->name }}
                </option>
            @endforeach
        </select>
        @error('race_id') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
    </div>
</div>

<button type="submit" class="btn btn-primary w-full py-4 text-base">
    {{ $expense ? 'Guardar cambios' : 'Guardar gasto' }}
</button>

<script>
    (function () {
        const colors = {
            registration: '#60a5fa', travel: '#a78bfa', accommodation: '#4ade80',
            gear: '#fb923c', nutrition: '#34d399', other: '#6b7280'
        };
        document.querySelectorAll('input[name="category"]').forEach(radio => {
            const update = () => {
                document.querySelectorAll('input[name="category"]').forEach(r => {
                    const lbl = document.getElementById('cat_label_' + r.value);
                    if (!lbl) { return; }
                    if (r.checked) {
                        const c = colors[r.value] || 'rgb(var(--color-primary))';
                        lbl.style.background = c + '18';
                        lbl.style.color = c;
                        lbl.style.borderColor = c + '40';
                    } else {
                        lbl.style.background = 'rgba(255,255,255,0.06)';
                        lbl.style.color = 'rgba(255,255,255,0.45)';
                        lbl.style.borderColor = 'transparent';
                    }
                });
            };
            update();
            radio.addEventListener('change', update);
        });
    })();
</script>
