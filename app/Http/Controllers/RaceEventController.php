<?php

namespace App\Http\Controllers;

use App\Models\Ad;
use App\Models\Race;
use App\Models\RaceEvent;
use App\Models\RaceEventModality;
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

        if ($request->filled('date_from') || $request->filled('date_to')) {
            $from = $request->filled('date_from') ? $request->date('date_from') : now();
            $to = $request->filled('date_to') ? $request->date('date_to')->endOfDay() : now()->addYears(5);
            $query->whereBetween('event_date', [$from, $to]);
        } elseif ($request->filled('when')) {
            $query->when($request->when === 'month', fn ($q) => $q->whereBetween('event_date', [now(), now()->endOfMonth()]))
                ->when($request->when === '3months', fn ($q) => $q->whereBetween('event_date', [now(), now()->addMonths(3)]));
        }

        $events = $query->paginate(12)->withQueryString();
        $featured = RaceEvent::featured()->upcoming()->withCount('attendees')->take(3)->get();

        $attendingIds = $request->user()
            ->belongsToMany(RaceEvent::class, 'race_event_user')
            ->pluck('race_event_id')
            ->toArray();

        $feedAd = Ad::pick('feed');

        return view('events.index', compact('events', 'featured', 'attendingIds', 'feedAd'));
    }

    public function show(Request $request, RaceEvent $raceEvent): View
    {
        $raceEvent->load('modalities')->loadCount('attendees');
        $isAttending = $raceEvent->isAttending($request->user());

        return view('events.show', compact('raceEvent', 'isAttending'));
    }

    public function toggleAttend(Request $request, RaceEvent $raceEvent): JsonResponse
    {
        $user = $request->user();

        if ($raceEvent->isAttending($user)) {
            $raceEvent->attendees()->detach($user->id);
            $raceEvent->loadCount('attendees');

            return response()->json([
                'attending' => false,
                'count' => $raceEvent->attendees_count,
                'show_unattend_modal' => $this->raceExistsForUser($user->id, $raceEvent),
            ]);
        }

        $raceEvent->attendees()->attach($user->id);
        $raceEvent->loadCount('attendees');

        $preference = $user->profile?->attend_add_race ?? 'ask';
        $alreadyInRaces = $this->raceExistsForUser($user->id, $raceEvent);
        $raceAdded = false;

        if (! $alreadyInRaces && $preference === 'always') {
            $this->createRaceFromEvent($user->id, $raceEvent);
            $raceAdded = true;
        }

        $isPast = $raceEvent->event_date && $raceEvent->event_date->isPast();

        return response()->json([
            'attending' => true,
            'count' => $raceEvent->attendees_count,
            'race_added' => $raceAdded,
            'already_in_races' => $alreadyInRaces,
            'show_modal' => ! $alreadyInRaces && $preference === 'ask',
            'event' => [
                'name' => $raceEvent->name,
                'date' => $raceEvent->event_date?->format('d/m/Y'),
                'location' => $raceEvent->location,
                'status' => $isPast ? 'completed' : 'upcoming',
            ],
        ]);
    }

    public function addToRaces(Request $request, RaceEvent $raceEvent): JsonResponse
    {
        $user = $request->user();

        if ($request->boolean('remember')) {
            $user->profile()->updateOrCreate(
                ['user_id' => $user->id],
                ['attend_add_race' => 'always']
            );
        }

        if ($this->raceExistsForUser($user->id, $raceEvent)) {
            return response()->json(['added' => false, 'reason' => 'duplicate']);
        }

        $this->createRaceFromEvent($user->id, $raceEvent);

        return response()->json(['added' => true]);
    }

    public function removeFromRaces(Request $request, RaceEvent $raceEvent): JsonResponse
    {
        Race::where('user_id', $request->user()->id)
            ->whereRaw('LOWER(name) = ?', [strtolower($raceEvent->name)])
            ->whereDate('date', $raceEvent->event_date)
            ->delete();

        return response()->json(['removed' => true]);
    }

    public function skipAddToRaces(Request $request, RaceEvent $raceEvent): JsonResponse
    {
        $user = $request->user();

        if ($request->boolean('remember')) {
            $user->profile()->updateOrCreate(
                ['user_id' => $user->id],
                ['attend_add_race' => 'never']
            );
        }

        return response()->json(['skipped' => true]);
    }

    private function raceExistsForUser(int $userId, RaceEvent $raceEvent): bool
    {
        return Race::where('user_id', $userId)
            ->whereRaw('LOWER(name) = ?', [strtolower($raceEvent->name)])
            ->whereDate('date', $raceEvent->event_date)
            ->exists();
    }

    private function createRaceFromEvent(int $userId, RaceEvent $raceEvent): Race
    {
        $isPast = $raceEvent->event_date && $raceEvent->event_date->isPast();

        /** @var RaceEventModality|null $firstModality */
        $firstModality = $raceEvent->modalities()->orderBy('sort_order')->first();

        $distance = $firstModality?->distance_km;

        return Race::create([
            'user_id' => $userId,
            'name' => $raceEvent->name,
            'date' => $raceEvent->event_date,
            'location' => $raceEvent->location,
            'category' => $raceEvent->category,
            'website' => $raceEvent->website,
            'status' => $isPast ? 'completed' : 'upcoming',
            'distance' => $distance,
            'distance_unit' => $distance ? 'km' : null,
            'modality' => $raceEvent->race_type ?? $raceEvent->category,
            'cost' => $firstModality?->price,
        ]);
    }
}
