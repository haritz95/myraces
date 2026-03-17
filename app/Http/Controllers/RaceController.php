<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRaceRequest;
use App\Http\Requests\UpdateRaceRequest;
use App\Models\Race;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class RaceController extends Controller
{
    public function index(Request $request): View
    {
        $query = $request->user()->races()->orderByDesc('date');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('modality')) {
            $query->where('modality', $request->modality);
        }

        if ($request->filled('year')) {
            $query->whereYear('date', $request->year);
        }

        $races = $query->paginate(15)->withQueryString();

        $years = $request->user()->races()
            ->selectRaw('YEAR(date) as year')
            ->distinct()
            ->orderByDesc('year')
            ->pluck('year');

        return view('races.index', compact('races', 'years'));
    }

    public function create(): View
    {
        return view('races.create');
    }

    public function store(StoreRaceRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['user_id'] = $request->user()->id;
        $data['finish_time'] = $this->parseTimeToSeconds($data['finish_time'] ?? null);

        Race::query()->create($data);

        return redirect()->route('races.index')
            ->with('success', __('races.created_successfully'));
    }

    public function show(Race $race): View
    {
        Gate::authorize('view', $race);

        return view('races.show', compact('race'));
    }

    public function edit(Race $race): View
    {
        Gate::authorize('update', $race);

        return view('races.edit', compact('race'));
    }

    public function update(UpdateRaceRequest $request, Race $race): RedirectResponse
    {
        Gate::authorize('update', $race);

        $data = $request->validated();
        $data['finish_time'] = $this->parseTimeToSeconds($data['finish_time'] ?? null);

        $race->update($data);

        return redirect()->route('races.show', $race)
            ->with('success', __('races.updated_successfully'));
    }

    public function destroy(Race $race): RedirectResponse
    {
        Gate::authorize('delete', $race);

        $race->delete();

        return redirect()->route('races.index')
            ->with('success', __('races.deleted_successfully'));
    }

    /**
     * Converts H:i:s or i:s string to total seconds.
     */
    private function parseTimeToSeconds(?string $time): ?int
    {
        if (blank($time)) {
            return null;
        }

        $parts = explode(':', $time);

        if (count($parts) === 3) {
            return ((int) $parts[0] * 3600) + ((int) $parts[1] * 60) + (int) $parts[2];
        }

        if (count($parts) === 2) {
            return ((int) $parts[0] * 60) + (int) $parts[1];
        }

        return null;
    }
}
