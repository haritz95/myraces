<x-app-layout>
    @section('page_title', 'Nuevo Pod')
    @section('back_url', route('pods.index'))

    <div class="max-w-lg mx-auto px-4 py-6">

        <form method="POST" action="{{ route('pods.store') }}" class="space-y-6">
            @csrf

            {{-- Pod identity --}}
            <div>
                <p class="section-label">El Pod</p>
                <div class="settings-group divide-y divide-white/[0.05]">

                    <div class="px-5 py-4 space-y-1.5">
                        <label class="block text-xs font-bold" style="color:rgba(255,255,255,0.50)">Nombre del Pod <span class="text-red-400">*</span></label>
                        <input type="text" name="name" value="{{ old('name') }}" required maxlength="60"
                               placeholder="ej: Madrileños Sub-4h"
                               class="input-field @error('name') error @enderror">
                        @error('name')<p class="text-red-400 text-xs">{{ $message }}</p>@enderror
                    </div>

                    <div class="px-5 py-4 space-y-1.5">
                        <label class="block text-xs font-bold" style="color:rgba(255,255,255,0.50)">Objetivo <span class="text-red-400">*</span></label>
                        <input type="text" name="goal" value="{{ old('goal') }}" required maxlength="120"
                               placeholder="ej: Madrid Marathon Sub-4h · Junio 2026"
                               class="input-field @error('goal') error @enderror">
                        <p class="text-[10px]" style="color:rgba(255,255,255,0.25)">El reto que une al grupo.</p>
                        @error('goal')<p class="text-red-400 text-xs">{{ $message }}</p>@enderror
                    </div>

                    <div class="px-5 py-4 space-y-1.5">
                        <label class="block text-xs font-bold" style="color:rgba(255,255,255,0.50)">Descripción</label>
                        <textarea name="description" rows="3" maxlength="500"
                                  placeholder="Contexto, reglas, nivel esperado..."
                                  class="input-field resize-none @error('description') error @enderror">{{ old('description') }}</textarea>
                    </div>
                </div>
            </div>

            {{-- Target & config --}}
            <div>
                <p class="section-label">Configuración</p>
                <div class="settings-group divide-y divide-white/[0.05]">

                    <div class="grid grid-cols-2 gap-4 px-5 py-4">
                        <div class="space-y-1.5">
                            <label class="block text-xs font-bold" style="color:rgba(255,255,255,0.50)">Distancia objetivo</label>
                            <input type="number" name="target_distance" value="{{ old('target_distance') }}"
                                   min="0" step="0.1" placeholder="ej: 500"
                                   class="input-field @error('target_distance') error @enderror">
                        </div>
                        <div class="space-y-1.5">
                            <label class="block text-xs font-bold" style="color:rgba(255,255,255,0.50)">Unidad</label>
                            <select name="target_unit" class="input-field">
                                <option value="km" {{ old('target_unit', 'km') === 'km' ? 'selected' : '' }}>km</option>
                                <option value="mi" {{ old('target_unit') === 'mi' ? 'selected' : '' }}>millas</option>
                            </select>
                        </div>
                    </div>

                    <div class="px-5 py-4 space-y-1.5">
                        <label class="block text-xs font-bold" style="color:rgba(255,255,255,0.50)">Máx. miembros</label>
                        <input type="number" name="max_members" value="{{ old('max_members', 10) }}"
                               min="2" max="10" class="input-field">
                        <p class="text-[10px]" style="color:rgba(255,255,255,0.25)">Los Pods son pequeños (2–10) para mayor cohesión.</p>
                    </div>

                    <div class="grid grid-cols-2 gap-4 px-5 py-4">
                        <div class="space-y-1.5">
                            <label class="block text-xs font-bold" style="color:rgba(255,255,255,0.50)">Inicio</label>
                            <input type="date" name="starts_at" value="{{ old('starts_at') }}" class="input-field">
                        </div>
                        <div class="space-y-1.5">
                            <label class="block text-xs font-bold" style="color:rgba(255,255,255,0.50)">Fin</label>
                            <input type="date" name="ends_at" value="{{ old('ends_at') }}"
                                   class="input-field @error('ends_at') error @enderror">
                        </div>
                        @error('ends_at')<p class="text-red-400 text-xs col-span-2 px-5">{{ $message }}</p>@enderror
                    </div>
                </div>
            </div>

            {{-- Info box --}}
            <div class="rounded-2xl px-4 py-3.5 text-xs leading-relaxed"
                 style="background:rgb(var(--color-primary) / 0.06);border:1px solid rgb(var(--color-primary) / 0.15);color:rgba(255,255,255,0.50)">
                <span class="text-primary font-bold">Sistema de puntos:</span>
                Cada km completado suma puntos con tu multiplicador de racha.
                <span class="font-mono text-white/70">Puntos = km × (1 + racha/10)</span>.
                Una racha de 10 días = ×2 puntos por km.
            </div>

            <button type="submit" class="btn btn-primary w-full">Crear Pod</button>
        </form>
    </div>
</x-app-layout>
