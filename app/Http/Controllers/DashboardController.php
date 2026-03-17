<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();

        $month = (int) $request->get('month', now()->month);
        $year = (int) $request->get('year', now()->year);

        $currentDate = Carbon::createFromDate($year, $month, 1);

        $racesThisMonth = $user->races()
            ->whereYear('date', $year)
            ->whereMonth('date', $month)
            ->orderBy('date')
            ->get();

        $upcomingRaces = $user->races()
            ->where('status', 'upcoming')
            ->where('date', '>=', now())
            ->orderBy('date')
            ->limit(5)
            ->get();

        $recentRaces = $user->races()
            ->where('status', 'completed')
            ->orderByDesc('date')
            ->limit(3)
            ->get();

        $stats = [
            'total_races' => $user->races()->where('status', 'completed')->count(),
            'total_km' => $user->races()->where('status', 'completed')->sum('distance'),
            'total_spent' => $user->races()->whereNotNull('cost')->sum('cost'),
            'year_races' => $user->races()->where('status', 'completed')->whereYear('date', now()->year)->count(),
        ];

        return view('dashboard', compact(
            'racesThisMonth',
            'upcomingRaces',
            'recentRaces',
            'stats',
            'currentDate',
        ));
    }
}
