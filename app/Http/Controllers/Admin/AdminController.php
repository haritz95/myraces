<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Race;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminController extends Controller
{
    public function index(): View
    {
        $stats = [
            'total_users' => User::count(),
            'total_races' => Race::count(),
            'total_km' => Race::where('status', 'completed')->sum('distance'),
            'total_spent' => Race::whereNotNull('cost')->sum('cost'),
            'recent_users' => User::latest()->take(5)->get(),
            'recent_races' => Race::with('user')->latest()->take(5)->get(),
        ];

        return view('admin.dashboard', compact('stats'));
    }

    public function users(Request $request): View
    {
        $users = User::withCount('races')
            ->when($request->search, fn ($q) => $q->where('name', 'like', "%{$request->search}%")
                ->orWhere('email', 'like', "%{$request->search}%"))
            ->latest()
            ->paginate(20);

        return view('admin.users', compact('users'));
    }

    public function toggleAdmin(User $user): RedirectResponse
    {
        $user->update(['is_admin' => ! $user->is_admin]);

        return redirect()->route('admin.users')
            ->with('success', $user->is_admin ? "{$user->name} ahora es administrador." : "{$user->name} ya no es administrador.");
    }

    public function races(Request $request): View
    {
        $races = Race::with('user')
            ->when($request->search, fn ($q) => $q->where('name', 'like', "%{$request->search}%"))
            ->when($request->status, fn ($q) => $q->where('status', $request->status))
            ->latest('date')
            ->paginate(25);

        return view('admin.races', compact('races'));
    }
}
