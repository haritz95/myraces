<x-app-layout>
    @section('page_title', 'Añadir material')
    @section('back_url', route('gear.index'))

    <main class="px-5 py-6 max-w-lg mx-auto w-full">

        <form method="POST" action="{{ route('gear.store') }}" class="space-y-4">
            @csrf

            <div>
                <label class="block text-sm font-bold text-white mb-2">Marca</label>
                <input type="text" name="brand" value="{{ old('brand') }}" placeholder="Nike, Adidas, ASICS..."
                       class="input-field @error('brand') error @enderror">
                @error('brand') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-bold text-white mb-2">Modelo</label>
                <input type="text" name="model" value="{{ old('model') }}" placeholder="Vaporfly, Gel-Nimbus..."
                       class="input-field @error('model') error @enderror">
                @error('model') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-bold text-white mb-2">Tipo</label>
                <select name="type" class="input-field">
                    @foreach(['shoes' => 'Zapatillas', 'watch' => 'Reloj GPS', 'clothing' => 'Ropa', 'accessories' => 'Accesorios', 'nutrition' => 'Nutrición', 'other' => 'Otro'] as $val => $label)
                        <option value="{{ $val }}" {{ old('type') === $val ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-bold text-white mb-2">Km actuales</label>
                    <input type="number" name="current_km" value="{{ old('current_km', 0) }}" min="0" step="0.1" class="input-field tabnum">
                </div>
                <div>
                    <label class="block text-sm font-bold text-white mb-2">Km máximos</label>
                    <input type="number" name="max_km" value="{{ old('max_km') }}" min="0" step="0.1" placeholder="700" class="input-field tabnum">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-bold text-white mb-2">Fecha compra</label>
                    <input type="date" name="purchase_date" value="{{ old('purchase_date') }}" class="input-field">
                </div>
                <div>
                    <label class="block text-sm font-bold text-white mb-2">Precio (€)</label>
                    <input type="number" name="purchase_price" value="{{ old('purchase_price') }}" min="0" step="0.01" placeholder="0.00" class="input-field tabnum">
                </div>
            </div>

            <div>
                <label class="block text-sm font-bold text-white mb-2">Notas</label>
                <textarea name="notes" rows="2" placeholder="Observaciones..."
                          class="input-field resize-none">{{ old('notes') }}</textarea>
            </div>

            <div class="flex items-center gap-3 py-2">
                <input type="checkbox" name="is_active" id="is_active" value="1"
                       class="w-5 h-5 rounded-lg cursor-pointer accent-primary" checked>
                <label for="is_active" class="text-sm font-bold text-white cursor-pointer">Material activo</label>
            </div>

            <button type="submit" class="btn btn-primary w-full py-4 text-base">
                Guardar material
            </button>
        </form>

    </main>
</x-app-layout>
