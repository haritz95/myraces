<x-app-layout>
    @section('page_title', 'Editar Pod')
    @section('back_url', route('admin.pods.show', $pod))

    <div class="max-w-lg mx-auto px-4 py-6">

        <form method="POST" action="{{ route('admin.pods.update', $pod) }}" class="space-y-6">
            @csrf @method('PATCH')

            <div>
                <p class="section-label">Identidad</p>
                <div class="settings-group divide-y divide-white/[0.05]">

                    <div class="px-5 py-4 space-y-1.5">
                        <label class="block text-xs font-bold" style="color:rgba(255,255,255,0.50)">Nombre <span class="text-red-400">*</span></label>
                        <input type="text" name="name" value="{{ old('name', $pod->name) }}" required maxlength="60"
                               class="input-field @error('name') error @enderror">
                        @error('name')<p class="text-red-400 text-xs">{{ $message }}</p>@enderror
                    </div>

                    <div class="px-5 py-4 space-y-1.5">
                        <label class="block text-xs font-bold" style="color:rgba(255,255,255,0.50)">Objetivo <span class="text-red-400">*</span></label>
                        <input type="text" name="goal" value="{{ old('goal', $pod->goal) }}" required maxlength="120"
                               class="input-field @error('goal') error @enderror">
                        @error('goal')<p class="text-red-400 text-xs">{{ $message }}</p>@enderror
                    </div>

                    <div class="px-5 py-4 space-y-1.5">
                        <label class="block text-xs font-bold" style="color:rgba(255,255,255,0.50)">Descripción</label>
                        <textarea name="description" rows="3" maxlength="500"
                                  class="input-field resize-none">{{ old('description', $pod->description) }}</textarea>
                    </div>
                </div>
            </div>

            <div>
                <p class="section-label">Configuración</p>
                <div class="settings-group divide-y divide-white/[0.05]">

                    <div class="px-5 py-4 space-y-1.5">
                        <label class="block text-xs font-bold" style="color:rgba(255,255,255,0.50)">Estado</label>
                        <select name="status" class="input-field">
                            <option value="active"    {{ old('status', $pod->status) === 'active'    ? 'selected' : '' }}>Activo</option>
                            <option value="completed" {{ old('status', $pod->status) === 'completed' ? 'selected' : '' }}>Completado</option>
                            <option value="archived"  {{ old('status', $pod->status) === 'archived'  ? 'selected' : '' }}>Archivado</option>
                        </select>
                    </div>

                    <div class="grid grid-cols-2 gap-4 px-5 py-4">
                        <div class="space-y-1.5">
                            <label class="block text-xs font-bold" style="color:rgba(255,255,255,0.50)">Distancia objetivo</label>
                            <input type="number" name="target_distance" value="{{ old('target_distance', $pod->target_distance) }}"
                                   min="0" step="0.1" class="input-field">
                        </div>
                        <div class="space-y-1.5">
                            <label class="block text-xs font-bold" style="color:rgba(255,255,255,0.50)">Unidad</label>
                            <select name="target_unit" class="input-field">
                                <option value="km" {{ old('target_unit', $pod->target_unit) === 'km' ? 'selected' : '' }}>km</option>
                                <option value="mi" {{ old('target_unit', $pod->target_unit) === 'mi' ? 'selected' : '' }}>millas</option>
                            </select>
                        </div>
                    </div>

                    <div class="px-5 py-4 space-y-1.5">
                        <label class="block text-xs font-bold" style="color:rgba(255,255,255,0.50)">Máx. miembros</label>
                        <input type="number" name="max_members" value="{{ old('max_members', $pod->max_members) }}"
                               min="2" max="50" class="input-field">
                        <p class="text-[10px]" style="color:rgba(255,255,255,0.25)">El admin puede ampliar hasta 50 miembros.</p>
                    </div>

                    <div class="grid grid-cols-2 gap-4 px-5 py-4">
                        <div class="space-y-1.5">
                            <label class="block text-xs font-bold" style="color:rgba(255,255,255,0.50)">Inicio</label>
                            <input type="date" name="starts_at" value="{{ old('starts_at', $pod->starts_at?->format('Y-m-d')) }}" class="input-field">
                        </div>
                        <div class="space-y-1.5">
                            <label class="block text-xs font-bold" style="color:rgba(255,255,255,0.50)">Fin</label>
                            <input type="date" name="ends_at" value="{{ old('ends_at', $pod->ends_at?->format('Y-m-d')) }}"
                                   class="input-field @error('ends_at') error @enderror">
                        </div>
                        @error('ends_at')<p class="text-red-400 text-xs col-span-2 px-5">{{ $message }}</p>@enderror
                    </div>
                </div>
            </div>

            <div class="flex gap-3">
                <button type="submit" class="btn btn-primary flex-1">Guardar cambios</button>
                <a href="{{ route('admin.pods.show', $pod) }}" class="btn btn-secondary flex-1">Cancelar</a>
            </div>
        </form>
    </div>
</x-app-layout>
