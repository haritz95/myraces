<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ad;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdController extends Controller
{
    public function index(): View
    {
        $ads = Ad::with('user')->latest()->get()->groupBy('status');

        return view('admin.ads.index', compact('ads'));
    }

    public function show(Ad $ad): View
    {
        $ad->load('user');

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

        return view('admin.ads.show', compact('ad', 'chartData'));
    }

    public function approve(Ad $ad): RedirectResponse
    {
        $ad->update(['status' => 'approved', 'rejection_reason' => null]);

        return back()->with('success', "\"{$ad->title}\" aprobado y activo.");
    }

    public function reject(Request $request, Ad $ad): RedirectResponse
    {
        $request->validate(['reason' => ['nullable', 'string', 'max:500']]);

        $ad->update([
            'status' => 'rejected',
            'rejection_reason' => $request->reason,
        ]);

        return back()->with('success', "\"{$ad->title}\" rechazado.");
    }

    public function pause(Ad $ad): RedirectResponse
    {
        $ad->update(['status' => $ad->status === 'paused' ? 'approved' : 'paused']);

        return back()->with('success', $ad->status === 'approved'
            ? "\"{$ad->title}\" reactivado."
            : "\"{$ad->title}\" pausado."
        );
    }

    public function destroy(Ad $ad): RedirectResponse
    {
        $ad->delete();

        return back()->with('success', 'Anuncio eliminado.');
    }
}
