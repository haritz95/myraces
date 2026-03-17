<x-app-layout>
    @section('page_title', 'Editar material')
    @section('back_url', route('gear.index'))

    <main class="flex-1 overflow-y-auto px-4 py-6 max-w-lg mx-auto w-full pb-[76px]">

        <form method="POST" action="{{ route('gear.update', $gear) }}" class="space-y-5">
            @csrf
            @method('PATCH')

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1.5">Marca</label>
                <input type="text" name="brand" value="{{ old('brand', $gear->brand) }}" placeholder="Nike, Adidas, ASICS…"
                       class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl text-slate-900 focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary @error('brand') border-red-400 @enderror">
                @error('brand') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1.5">Modelo</label>
                <input type="text" name="model" value="{{ old('model', $gear->model) }}" placeholder="Vaporfly, Gel-Nimbus…"
                       class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl text-slate-900 focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary @error('model') border-red-400 @enderror">
                @error('model') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1.5">Tipo</label>
                <select name="type" class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl text-slate-900 focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary">
                    @foreach(['shoes' => 'Zapatillas', 'watch' => 'Reloj GPS', 'clothing' => 'Ropa', 'accessories' => 'Accesorios', 'nutrition' => 'Nutrición', 'other' => 'Otro'] as $val => $label)
                        <option value="{{ $val }}" {{ old('type', $gear->type) === $val ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Km actuales</label>
                    <input type="number" name="current_km" value="{{ old('current_km', $gear->current_km) }}" min="0" step="0.1"
                           class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl text-slate-900 focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Km máximos</label>
                    <input type="number" name="max_km" value="{{ old('max_km', $gear->max_km) }}" min="0" step="0.1" placeholder="700"
                           class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl text-slate-900 focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Fecha de compra</label>
                    <input type="date" name="purchase_date" value="{{ old('purchase_date', $gear->purchase_date?->format('Y-m-d')) }}"
                           class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl text-slate-900 focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Precio (€)</label>
                    <input type="number" name="purchase_price" value="{{ old('purchase_price', $gear->purchase_price) }}" min="0" step="0.01" placeholder="0.00"
                           class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl text-slate-900 focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary">
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1.5">Notas (opcional)</label>
                <textarea name="notes" rows="2" class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl text-slate-900 focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary resize-none">{{ old('notes', $gear->notes) }}</textarea>
            </div>

            <div class="flex items-center gap-3">
                <input type="checkbox" name="is_active" id="is_active" value="1" class="w-4 h-4 rounded text-primary" {{ old('is_active', $gear->is_active) ? 'checked' : '' }}>
                <label for="is_active" class="text-sm font-medium text-slate-700">Material activo</label>
            </div>

            <button type="submit" class="w-full bg-primary hover:bg-orange-600 text-white font-semibold py-3.5 rounded-xl transition-colors" style="box-shadow: 0 4px 12px rgba(236,91,19,0.35)">
                Actualizar material
            </button>
        </form>

    </main>
</x-app-layout>
