<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RaceEvent;
use App\Models\RaceEventModality;
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
            ->whereNotIn('status', ['pending', 'rejected'])
            ->orderBy('event_date')
            ->paginate(20);

        $stats = [
            'total' => RaceEvent::whereNotIn('status', ['pending', 'rejected'])->count(),
            'upcoming' => RaceEvent::where('status', 'upcoming')->count(),
            'open' => RaceEvent::where('status', 'open')->count(),
            'pending' => RaceEvent::where('status', 'pending')->count(),
        ];

        return view('admin.events.index', compact('events', 'stats'));
    }

    public function pending(): View
    {
        $submissions = RaceEvent::where('status', 'pending')
            ->with('submitter')
            ->latest()
            ->paginate(20);

        return view('admin.events.pending', compact('submissions'));
    }

    public function approve(RaceEvent $event): RedirectResponse
    {
        abort_unless($event->status === 'pending', 404);

        $event->update([
            'status' => 'upcoming',
            'rejection_reason' => null,
        ]);

        return redirect()->route('admin.events.pending')
            ->with('success', "Carrera \"{$event->name}\" aprobada.");
    }

    public function reject(Request $request, RaceEvent $event): RedirectResponse
    {
        abort_unless($event->status === 'pending', 404);

        $request->validate([
            'rejection_reason' => ['nullable', 'string', 'max:500'],
        ]);

        $event->update([
            'status' => 'rejected',
            'rejection_reason' => $request->input('rejection_reason'),
        ]);

        return redirect()->route('admin.events.pending')
            ->with('success', "Carrera \"{$event->name}\" rechazada.");
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

        $this->resolveImage($request, $data);

        $event = RaceEvent::create($data);

        $this->syncModalities($event, $request->input('modalities', []));

        return redirect()->route('admin.events.index')
            ->with('success', "Carrera \"{$event->name}\" creada.");
    }

    public function edit(RaceEvent $event): View
    {
        return view('admin.events.edit', [
            'event' => $event->load('modalities'),
            'raceTypes' => RaceEvent::raceTypes(),
            'categories' => RaceEvent::categories(),
        ]);
    }

    public function update(Request $request, RaceEvent $event): RedirectResponse
    {
        $data = $this->validatedData($request, $event);

        $this->resolveImage($request, $data, $event);

        $event->update($data);

        $this->syncModalities($event, $request->input('modalities', []));

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
            'image_url' => ['nullable', 'url', 'max:500'],
            'event_date' => ['required', 'date'],
            'registration_deadline' => ['nullable', 'date', 'before_or_equal:event_date'],
            'location' => ['required', 'string', 'max:150'],
            'province' => ['nullable', 'string', 'max:100'],
            'country' => ['required', 'string', 'max:100'],
            'distance_km' => ['nullable', 'numeric', 'min:0'],
            'category' => ['nullable', 'string', 'max:50'],
            'race_type' => ['required', 'in:'.implode(',', array_keys(RaceEvent::raceTypes()))],
            'price' => ['nullable', 'numeric', 'min:0'],
            'max_participants' => ['nullable', 'integer', 'min:1'],
            'website_url' => ['nullable', 'url', 'max:500'],
            'registration_url' => ['nullable', 'url', 'max:500'],
            'organizer' => ['nullable', 'string', 'max:150'],
            'status' => ['required', 'in:upcoming,open,cancelled,past'],
            'is_featured' => ['boolean'],
            'modalities' => ['nullable', 'array'],
            'modalities.*.name' => ['required', 'string', 'max:100'],
            'modalities.*.distance_km' => ['nullable', 'numeric', 'min:0'],
            'modalities.*.category' => ['nullable', 'string', 'max:50'],
            'modalities.*.price' => ['nullable', 'numeric', 'min:0'],
            'modalities.*.registration_url' => ['nullable', 'url', 'max:500'],
            'modalities.*.max_participants' => ['nullable', 'integer', 'min:1'],
        ]);
    }

    /**
     * Resolve image: uploaded file takes precedence over URL.
     * If a file is uploaded, clears image_url and vice versa.
     *
     * @param  array<string,mixed>  $data
     */
    private function resolveImage(Request $request, array &$data, ?RaceEvent $event = null): void
    {
        if ($request->hasFile('image')) {
            if ($event?->image) {
                Storage::disk('public')->delete($event->image);
            }
            $data['image'] = $request->file('image')->store('events', 'public');
            $data['image_url'] = null;
        } elseif ($request->filled('image_url')) {
            if ($event?->image) {
                Storage::disk('public')->delete($event->image);
            }
            $data['image'] = null;
            $data['image_url'] = $request->input('image_url');
        }
    }

    /** @param array<int,array<string,mixed>> $modalities */
    private function syncModalities(RaceEvent $event, array $modalities): void
    {
        $event->modalities()->delete();

        foreach (array_values($modalities) as $index => $row) {
            if (empty($row['name'])) {
                continue;
            }

            RaceEventModality::create([
                'race_event_id' => $event->id,
                'name' => $row['name'],
                'distance_km' => $row['distance_km'] ?: null,
                'category' => $row['category'] ?: null,
                'price' => $row['price'] ?: null,
                'registration_url' => $row['registration_url'] ?: null,
                'max_participants' => $row['max_participants'] ?: null,
                'sort_order' => $index,
            ]);
        }
    }
}
