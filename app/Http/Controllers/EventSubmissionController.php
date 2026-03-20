<?php

namespace App\Http\Controllers;

use App\Models\RaceEvent;
use App\Models\RaceEventModality;
use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class EventSubmissionController extends Controller
{
    public function create(): View
    {
        abort_if(Setting::get('event_submissions_open', '1') === '0', 403, 'El envío de eventos está desactivado.');

        return view('events.submit', [
            'raceTypes' => RaceEvent::raceTypes(),
            'categories' => RaceEvent::categories(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        abort_if(Setting::get('event_submissions_open', '1') === '0', 403, 'El envío de eventos está desactivado.');

        $data = $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'description' => ['nullable', 'string', 'max:2000'],
            'image' => ['nullable', 'image', 'max:4096'],
            'image_url' => ['nullable', 'url', 'max:500'],
            'event_date' => ['required', 'date', 'after:today'],
            'registration_deadline' => ['nullable', 'date', 'before_or_equal:event_date'],
            'location' => ['required', 'string', 'max:150'],
            'province' => ['nullable', 'string', 'max:100'],
            'country' => ['required', 'string', 'max:100'],
            'race_type' => ['required', 'in:'.implode(',', array_keys(RaceEvent::raceTypes()))],
            'category' => ['nullable', 'string', 'max:50'],
            'distance_km' => ['nullable', 'numeric', 'min:0'],
            'price' => ['nullable', 'numeric', 'min:0'],
            'website_url' => ['nullable', 'url', 'max:500'],
            'registration_url' => ['nullable', 'url', 'max:500'],
            'organizer' => ['nullable', 'string', 'max:150'],
            'modalities' => ['nullable', 'array'],
            'modalities.*.name' => ['required', 'string', 'max:100'],
            'modalities.*.distance_km' => ['nullable', 'numeric', 'min:0'],
            'modalities.*.category' => ['nullable', 'string', 'max:50'],
            'modalities.*.price' => ['nullable', 'numeric', 'min:0'],
            'modalities.*.registration_url' => ['nullable', 'url', 'max:500'],
        ]);

        $user = $request->user();

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('events', 'public');
            $data['image_url'] = null;
        } elseif ($request->filled('image_url')) {
            $data['image_url'] = $request->input('image_url');
            $data['image'] = null;
        }

        $data['submitted_by'] = $user->id;
        $data['created_by'] = $user->id;
        $data['status'] = 'pending';
        $data['source'] = 'community';

        $modalities = $data['modalities'] ?? [];
        unset($data['modalities']);

        $event = RaceEvent::create($data);

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
                'sort_order' => $index,
            ]);
        }

        return redirect()->route('events.my-submissions')
            ->with('success', 'Carrera enviada. La revisaremos pronto.');
    }

    public function mySubmissions(Request $request): View
    {
        $submissions = RaceEvent::where('submitted_by', $request->user()->id)
            ->withCount('attendees')
            ->latest()
            ->paginate(15);

        return view('events.my-submissions', compact('submissions'));
    }

    public function edit(RaceEvent $event): View
    {
        $this->authorizeSubmission($event);

        return view('events.submit', [
            'event' => $event->load('modalities'),
            'raceTypes' => RaceEvent::raceTypes(),
            'categories' => RaceEvent::categories(),
        ]);
    }

    public function update(Request $request, RaceEvent $event): RedirectResponse
    {
        $this->authorizeSubmission($event);

        $data = $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'description' => ['nullable', 'string', 'max:2000'],
            'image' => ['nullable', 'image', 'max:4096'],
            'image_url' => ['nullable', 'url', 'max:500'],
            'event_date' => ['required', 'date', 'after:today'],
            'registration_deadline' => ['nullable', 'date', 'before_or_equal:event_date'],
            'location' => ['required', 'string', 'max:150'],
            'province' => ['nullable', 'string', 'max:100'],
            'country' => ['required', 'string', 'max:100'],
            'race_type' => ['required', 'in:'.implode(',', array_keys(RaceEvent::raceTypes()))],
            'category' => ['nullable', 'string', 'max:50'],
            'distance_km' => ['nullable', 'numeric', 'min:0'],
            'price' => ['nullable', 'numeric', 'min:0'],
            'website_url' => ['nullable', 'url', 'max:500'],
            'registration_url' => ['nullable', 'url', 'max:500'],
            'organizer' => ['nullable', 'string', 'max:150'],
            'modalities' => ['nullable', 'array'],
            'modalities.*.name' => ['required', 'string', 'max:100'],
            'modalities.*.distance_km' => ['nullable', 'numeric', 'min:0'],
            'modalities.*.category' => ['nullable', 'string', 'max:50'],
            'modalities.*.price' => ['nullable', 'numeric', 'min:0'],
            'modalities.*.registration_url' => ['nullable', 'url', 'max:500'],
        ]);

        if ($request->hasFile('image')) {
            if ($event->image) {
                Storage::disk('public')->delete($event->image);
            }
            $data['image'] = $request->file('image')->store('events', 'public');
            $data['image_url'] = null;
        } elseif ($request->filled('image_url')) {
            if ($event->image) {
                Storage::disk('public')->delete($event->image);
            }
            $data['image'] = null;
            $data['image_url'] = $request->input('image_url');
        }

        $data['status'] = 'pending';
        $data['rejection_reason'] = null;

        $modalities = $data['modalities'] ?? [];
        unset($data['modalities']);

        $event->update($data);

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
                'sort_order' => $index,
            ]);
        }

        return redirect()->route('events.my-submissions')
            ->with('success', 'Envío actualizado. Lo revisaremos de nuevo.');
    }

    private function authorizeSubmission(RaceEvent $event): void
    {
        abort_unless(
            $event->submitted_by === auth()->id() && $event->status === 'pending',
            403
        );
    }
}
