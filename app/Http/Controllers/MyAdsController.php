<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAdRequest;
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
