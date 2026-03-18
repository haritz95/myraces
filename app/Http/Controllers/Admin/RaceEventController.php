<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RaceEvent;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class RaceEventController extends Controller
{
    public function index(Request $request): View
    {
        $events = RaceEvent::withCount('attendees')
            ->when($request->filled('q'), fn ($query) => $query->where('name', 'like', "%{$request->q}%")
                ->orWhere('location', 'like', "%{$request->q}%"))
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->status))
            ->orderBy('event_date')
            ->paginate(20);

        $stats = [
            'total' => RaceEvent::count(),
            'upcoming' => RaceEvent::where('status', 'upcoming')->count(),
            'open' => RaceEvent::where('status', 'open')->count(),
            'cancelled' => RaceEvent::where('status', 'cancelled')->count(),
        ];

        return view('admin.events.index', compact('events', 'stats'));
    }

    public function create(): View
    {
        return view('admin.events.create', [
            'raceTypes' => RaceEvent::raceTypes(),
            'categories' => RaceEvent::categories(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validatedData($request);
        $data['created_by'] = $request->user()->id;
        $data['image'] = $this->handleImageUpload($request);

        RaceEvent::create($data);

        return redirect()->route('admin.events.index')
            ->with('success', "Carrera \"{$data['name']}\" creada.");
    }

    public function edit(RaceEvent $event): View
    {
        return view('admin.events.edit', [
            'event' => $event,
            'raceTypes' => RaceEvent::raceTypes(),
            'categories' => RaceEvent::categories(),
        ]);
    }

    public function update(Request $request, RaceEvent $event): RedirectResponse
    {
        $data = $this->validatedData($request, $event);

        if ($request->hasFile('image')) {
            if ($event->image) {
                Storage::disk('public')->delete($event->image);
            }
            $data['image'] = $request->file('image')->store('events', 'public');
        }

        $event->update($data);

        return redirect()->route('admin.events.index')
            ->with('success', "Carrera \"{$event->name}\" actualizada.");
    }

    public function destroy(RaceEvent $event): RedirectResponse
    {
        if ($event->image) {
            Storage::disk('public')->delete($event->image);
        }

        $name = $event->name;
        $event->attendees()->detach();
        $event->delete();

        return redirect()->route('admin.events.index')
            ->with('success', "Carrera \"{$name}\" eliminada.");
    }

    /** @return array<string,mixed> */
    private function validatedData(Request $request, ?RaceEvent $event = null): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'description' => ['nullable', 'string', 'max:2000'],
            'image' => ['nullable', 'image', 'max:4096'],
            'event_date' => ['required', 'date'],
            'registration_deadline' => ['nullable', 'date', 'before_or_equal:event_date'],
            'location' => ['required', 'string', 'max:150'],
            'province' => ['nullable', 'string', 'max:100'],
            'country' => ['required', 'string', 'max:100'],
            'distance_km' => ['nullable', 'numeric', 'min:0'],
            'category' => ['required', 'string', 'max:50'],
            'race_type' => ['required', 'in:'.implode(',', array_keys(RaceEvent::raceTypes()))],
            'price' => ['nullable', 'numeric', 'min:0'],
            'max_participants' => ['nullable', 'integer', 'min:1'],
            'website_url' => ['nullable', 'url', 'max:500'],
            'registration_url' => ['nullable', 'url', 'max:500'],
            'organizer' => ['nullable', 'string', 'max:150'],
            'status' => ['required', 'in:upcoming,open,cancelled,past'],
            'is_featured' => ['boolean'],
        ]);
    }

    private function handleImageUpload(Request $request): ?string
    {
        if (! $request->hasFile('image')) {
            return null;
        }

        return $request->file('image')->store('events', 'public');
    }
}
