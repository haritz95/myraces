<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CalendarController extends Controller
{
    public function index(Request $request): View
    {
        $year = (int) $request->get('year', now()->year);
        $month = (int) $request->get('month', now()->month);

        $startOfMonth = Carbon::create($year, $month, 1)->startOfMonth();
        $endOfMonth = $startOfMonth->copy()->endOfMonth();

        $races = $request->user()->races()
            ->whereBetween('date', [$startOfMonth, $endOfMonth])
            ->orderBy('date')
            ->get();

        $upcomingRaces = $request->user()->races()
            ->where('status', 'upcoming')
            ->where('date', '>=', now())
            ->orderBy('date')
            ->limit(5)
            ->get();

        return view('calendar.index', compact('races', 'upcomingRaces', 'year', 'month', 'startOfMonth'));
    }
}
