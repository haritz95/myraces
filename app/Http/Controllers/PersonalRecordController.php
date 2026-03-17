<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePersonalRecordRequest;
use App\Models\PersonalRecord;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PersonalRecordController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();

        $records = $user->personalRecords()
            ->with('race')
            ->orderByDesc('date')
            ->get();

        $bestByDistance = $records->groupBy('distance_label')
            ->map(fn ($group) => $group->sortBy('time_seconds')->first());

        $races = $user->races()->orderByDesc('date')->get(['id', 'name', 'date']);

        return view('personal-records.index', compact('records', 'bestByDistance', 'races'));
    }

    public function store(StorePersonalRecordRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['user_id'] = $request->user()->id;

        PersonalRecord::query()->create($data);

        return redirect()->route('personal-records.index')
            ->with('success', 'Récord personal registrado correctamente.');
    }

    public function destroy(PersonalRecord $personalRecord): RedirectResponse
    {
        abort_unless($personalRecord->user_id === auth()->id(), 403);

        $personalRecord->delete();

        return redirect()->route('personal-records.index')
            ->with('success', 'Récord eliminado.');
    }
}
