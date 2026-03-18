<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRaceRequest;
use App\Http\Requests\UpdateRaceRequest;
use App\Models\Ad;
use App\Models\Expense;
use App\Models\PersonalRecord;
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

        $feedAd = Ad::serve('feed');

        return view('races.index', compact('races', 'years', 'feedAd'));
    }

    public function create(): View
    {
        $gear = auth()->user()->gears()->where('is_active', true)->orderBy('type')->orderBy('brand')->get();

        return view('races.create', compact('gear'));
    }

    public function store(StoreRaceRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $gearIds = $data['gear_ids'] ?? [];
        unset($data['gear_ids']);

        $data['user_id'] = $request->user()->id;
        $data['finish_time'] = $this->parseTimeToSeconds($data['finish_time'] ?? null);

        $race = Race::query()->create($data);
        $race->gear()->sync($gearIds);
        $this->syncPersonalRecord($race);
        $this->syncExpense($race);

        return redirect()->route('races.index')
            ->with('success', __('races.created_successfully'));
    }

    public function show(Race $race): View
    {
        Gate::authorize('view', $race);

        $race->load('gear');

        return view('races.show', compact('race'));
    }

    public function edit(Race $race): View
    {
        Gate::authorize('update', $race);

        $gear = auth()->user()->gears()->where('is_active', true)->orderBy('type')->orderBy('brand')->get();

        return view('races.edit', compact('race', 'gear'));
    }

    public function update(UpdateRaceRequest $request, Race $race): RedirectResponse
    {
        Gate::authorize('update', $race);

        $data = $request->validated();
        $gearIds = $data['gear_ids'] ?? [];
        unset($data['gear_ids']);

        $data['finish_time'] = $this->parseTimeToSeconds($data['finish_time'] ?? null);

        $race->update($data);
        $race->gear()->sync($gearIds);
        $this->syncPersonalRecord($race->fresh());
        $this->syncExpense($race->fresh());

        return redirect()->route('races.show', $race)
            ->with('success', __('races.updated_successfully'));
    }

    public function destroy(Race $race): RedirectResponse
    {
        Gate::authorize('delete', $race);

        PersonalRecord::query()->where('race_id', $race->id)->delete();
        Expense::query()->where('race_id', $race->id)->where('category', 'registration')->whereNotNull('race_id')->delete();
        $race->delete();

        return redirect()->route('races.index')
            ->with('success', __('races.deleted_successfully'));
    }

    /**
     * Auto-create or update the personal record linked to this race.
     * Removes the linked auto-PR if the race is no longer completed or has no time.
     */
    private function syncPersonalRecord(Race $race): void
    {
        if ($race->status !== 'completed' || ! $race->finish_time) {
            PersonalRecord::query()->where('race_id', $race->id)->delete();

            return;
        }

        $distanceKm = $race->distance_unit === 'mi'
            ? (float) $race->distance * 1.60934
            : (float) $race->distance;

        PersonalRecord::query()->updateOrCreate(
            ['race_id' => $race->id],
            [
                'user_id' => $race->user_id,
                'distance_label' => $this->getDistanceLabel($distanceKm),
                'distance_km' => round($distanceKm, 3),
                'time_seconds' => $race->finish_time,
                'date' => $race->date,
                'location' => $race->location,
            ]
        );
    }

    /**
     * Auto-create or update the registration expense linked to this race cost.
     * Removes it if the race has no cost set.
     */
    private function syncExpense(Race $race): void
    {
        if (! $race->cost || (float) $race->cost <= 0) {
            Expense::query()
                ->where('race_id', $race->id)
                ->where('category', 'registration')
                ->delete();

            return;
        }

        Expense::query()->updateOrCreate(
            ['race_id' => $race->id, 'category' => 'registration'],
            [
                'user_id' => $race->user_id,
                'amount' => $race->cost,
                'currency' => 'EUR',
                'description' => 'Inscripción: '.$race->name,
                'date' => $race->date,
            ]
        );
    }

    /**
     * Map a distance in km to a standard label.
     */
    private function getDistanceLabel(float $distanceKm): string
    {
        return match (true) {
            abs($distanceKm - 5.0) < 0.1 => '5K',
            abs($distanceKm - 10.0) < 0.15 => '10K',
            abs($distanceKm - 21.097) < 0.3 => 'Half Marathon',
            abs($distanceKm - 42.195) < 0.5 => 'Marathon',
            $distanceKm >= 100.0 => '100K',
            $distanceKm >= 50.0 => '50K',
            default => number_format($distanceKm, 1).'K',
        };
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
