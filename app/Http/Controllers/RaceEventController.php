<?php

namespace App\Http\Controllers;

use App\Models\RaceEvent;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RaceEventController extends Controller
{
    public function index(Request $request): View
    {
        $query = RaceEvent::withCount('attendees')->upcoming();

        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(fn ($s) => $s->where('name', 'like', "%{$q}%")
                ->orWhere('location', 'like', "%{$q}%")
                ->orWhere('province', 'like', "%{$q}%"));
        }

        if ($request->filled('type')) {
            $query->where('race_type', $request->type);
        }

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        if ($request->filled('when')) {
            $query->when($request->when === 'month', fn ($q) => $q->whereBetween('event_date', [now(), now()->endOfMonth()]))
                ->when($request->when === '3months', fn ($q) => $q->whereBetween('event_date', [now(), now()->addMonths(3)]));
        }

        $events = $query->paginate(12)->withQueryString();
        $featured = RaceEvent::featured()->upcoming()->withCount('attendees')->take(3)->get();

        $attendingIds = $request->user()
            ->belongsToMany(RaceEvent::class, 'race_event_user')
            ->pluck('race_event_id')
            ->toArray();

        return view('events.index', compact('events', 'featured', 'attendingIds'));
    }

    public function show(Request $request, RaceEvent $raceEvent): View
    {
        $raceEvent->loadCount('attendees');
        $isAttending = $raceEvent->isAttending($request->user());

        return view('events.show', compact('raceEvent', 'isAttending'));
    }

    public function toggleAttend(Request $request, RaceEvent $raceEvent): JsonResponse
    {
        $user = $request->user();

        if ($raceEvent->isAttending($user)) {
            $raceEvent->attendees()->detach($user->id);
            $attending = false;
        } else {
            $raceEvent->attendees()->attach($user->id);
            $attending = true;
        }

        $raceEvent->loadCount('attendees');

        return response()->json([
            'attending' => $attending,
            'count' => $raceEvent->attendees_count,
        ]);
    }
}
