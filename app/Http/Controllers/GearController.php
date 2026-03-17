<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreGearRequest;
use App\Http\Requests\UpdateGearRequest;
use App\Models\Gear;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class GearController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();

        $activeGear = $user->gears()->where('is_active', true)->orderBy('type')->orderByDesc('created_at')->get();
        $retiredGear = $user->gears()->where('is_active', false)->orderByDesc('updated_at')->get();

        return view('gear.index', compact('activeGear', 'retiredGear'));
    }

    public function create(): View
    {
        return view('gear.create');
    }

    public function store(StoreGearRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['user_id'] = $request->user()->id;
        $data['is_active'] = $request->boolean('is_active', true);

        Gear::query()->create($data);

        return redirect()->route('gear.index')
            ->with('success', 'Material registrado correctamente.');
    }

    public function edit(Gear $gear): View
    {
        $this->authorizeGear($gear);

        return view('gear.edit', compact('gear'));
    }

    public function update(UpdateGearRequest $request, Gear $gear): RedirectResponse
    {
        $this->authorizeGear($gear);

        $data = $request->validated();
        $data['is_active'] = $request->boolean('is_active', true);

        $gear->update($data);

        return redirect()->route('gear.index')
            ->with('success', 'Material actualizado correctamente.');
    }

    public function destroy(Gear $gear): RedirectResponse
    {
        $this->authorizeGear($gear);

        $gear->delete();

        return redirect()->route('gear.index')
            ->with('success', 'Material eliminado.');
    }

    private function authorizeGear(Gear $gear): void
    {
        abort_unless($gear->user_id === auth()->id(), 403);
    }
}
