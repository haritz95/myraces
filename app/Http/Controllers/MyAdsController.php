<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAdRequest;
use App\Http\Requests\UpdateAdRequest;
use App\Models\Ad;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class MyAdsController extends Controller
{
    public function index(Request $request): View
    {
        $ads = Ad::where('user_id', $request->user()->id)
            ->latest()
            ->get();

        return view('my-ads.index', compact('ads'));
    }

    public function create(): View
    {
        return view('my-ads.create');
    }

    public function store(StoreAdRequest $request): RedirectResponse
    {
        $data = $request->safe()->except('image');
        $data['user_id'] = $request->user()->id;
        $data['max_impressions'] = $data['max_impressions'] ?? 0;
        $data['status'] = 'pending';

        if ($request->hasFile('image')) {
            $data['image_path'] = $request->file('image')->store('ads', 'public');
        }

        Ad::create($data);

        return redirect()->route('my-ads.index')
            ->with('success', 'Anuncio enviado para revisión. Te avisaremos cuando esté activo.');
    }

    public function show(Request $request, Ad $ad): View
    {
        abort_if($ad->user_id !== $request->user()->id, 403);

        $clicksByDay = $ad->clicks()
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->where('created_at', '>=', now()->subDays(13)->startOfDay())
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->keyBy('date');

        $chartData = collect(range(13, 0))->map(function (int $daysAgo) use ($clicksByDay): array {
            $date = now()->subDays($daysAgo)->format('Y-m-d');

            return [
                'date' => now()->subDays($daysAgo)->format('d/m'),
                'count' => (int) ($clicksByDay->get($date)?->count ?? 0),
            ];
        })->values();

        return view('my-ads.show', compact('ad', 'chartData'));
    }

    public function update(UpdateAdRequest $request, Ad $ad): RedirectResponse
    {
        $data = $request->safe()->except('image');
        $data['max_impressions'] = $data['max_impressions'] ?? 0;

        if ($ad->status === 'rejected') {
            $data['status'] = 'pending';
            $data['rejection_reason'] = null;
        }

        if ($request->hasFile('image')) {
            if ($ad->image_path) {
                Storage::disk('public')->delete($ad->image_path);
            }
            $data['image_path'] = $request->file('image')->store('ads', 'public');
        }

        $ad->update($data);

        return redirect()->route('my-ads.show', $ad)
            ->with('success', $ad->status === 'pending'
                ? 'Anuncio actualizado y reenviado para revisión.'
                : 'Anuncio actualizado.');
    }

    public function destroy(Request $request, Ad $ad): RedirectResponse
    {
        abort_if($ad->user_id !== $request->user()->id, 403);

        if ($ad->image_path) {
            Storage::disk('public')->delete($ad->image_path);
        }

        $ad->delete();

        return redirect()->route('my-ads.index')->with('success', 'Anuncio eliminado.');
    }
}
