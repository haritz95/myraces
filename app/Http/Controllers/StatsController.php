<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class StatsController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();

        $completedRaces = $user->races()
            ->where('status', 'completed')
            ->whereNotNull('finish_time')
            ->orderBy('date')
            ->get();

        $personalRecords = [];
        $standardDistances = [5, 10, 21.097, 42.195];

        foreach ($standardDistances as $dist) {
            $best = $completedRaces
                ->filter(fn ($r) => abs((float) $r->distance - $dist) < 0.5)
                ->sortBy('finish_time')
                ->first();

            if ($best) {
                $personalRecords[$dist] = $best;
            }
        }

        $byModality = $completedRaces
            ->groupBy('modality')
            ->map(fn ($races) => [
                'count' => $races->count(),
                'best_time' => $races->sortBy('finish_time')->first(),
                'total_km' => $races->sum(fn ($r) => (float) $r->distance),
            ]);

        $yearlyStats = $user->races()
            ->where('status', 'completed')
            ->selectRaw('YEAR(date) as year, COUNT(*) as count, SUM(distance) as total_km, SUM(cost) as total_spent')
            ->groupByRaw('YEAR(date)')
            ->orderByDesc('year')
            ->get();

        $totalSpent = $user->races()->whereNotNull('cost')->sum('cost');

        return view('stats.index', compact(
            'personalRecords',
            'byModality',
            'yearlyStats',
            'totalSpent',
            'completedRaces',
        ));
    }
}
