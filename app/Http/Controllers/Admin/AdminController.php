<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Expense;
use App\Models\PersonalRecord;
use App\Models\Pod;
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
            'total_km' => (float) Race::where('status', 'completed')->sum('distance'),
            'total_spent' => (float) Race::whereNotNull('cost')->sum('cost'),
            'premium_users' => User::where('is_premium', true)->count(),
            'banned_users' => User::where('is_banned', true)->count(),
            'active_pods' => Pod::where('status', 'active')->count(),
            'recent_users' => User::latest()->take(5)->get(),
            'recent_races' => Race::with('user')->latest()->take(5)->get(),
        ];

        return view('admin.dashboard', compact('stats'));
    }

    public function users(Request $request): View
    {
        $users = User::withCount('races')
            ->when(
                $request->search,
                fn ($q) => $q->where('name', 'like', "%{$request->search}%")
                    ->orWhere('email', 'like', "%{$request->search}%")
            )
            ->when($request->filter === 'banned', fn ($q) => $q->where('is_banned', true))
            ->when($request->filter === 'admin', fn ($q) => $q->where('is_admin', true))
            ->when($request->filter === 'premium', fn ($q) => $q->where('is_premium', true))
            ->latest()
            ->paginate(20);

        return view('admin.users', compact('users'));
    }

    public function togglePremium(User $user): RedirectResponse
    {
        $user->update(['is_premium' => ! $user->is_premium]);

        return redirect()->route('admin.users')
            ->with('success', $user->is_premium ? "{$user->name} ahora tiene premium." : "{$user->name} ya no tiene premium.");
    }

    public function toggleAdmin(User $user): RedirectResponse
    {
        $user->update(['is_admin' => ! $user->is_admin]);

        return redirect()->route('admin.users')
            ->with('success', $user->is_admin ? "{$user->name} ahora es administrador." : "{$user->name} ya no es administrador.");
    }

    public function toggleBan(User $user): RedirectResponse
    {
        if ($user->id === auth()->id()) {
            return redirect()->route('admin.users')->with('error', 'No puedes banearte a ti mismo.');
        }

        if ($user->is_banned) {
            $user->update(['is_banned' => false, 'ban_reason' => null, 'banned_at' => null]);
            $message = "{$user->name} ha sido desbaneado.";
        } else {
            $user->update(['is_banned' => true, 'banned_at' => now()]);
            $message = "{$user->name} ha sido baneado.";
        }

        return redirect()->route('admin.users')->with('success', $message);
    }

    public function destroyUser(User $user): RedirectResponse
    {
        if ($user->id === auth()->id()) {
            return redirect()->route('admin.users')->with('error', 'No puedes eliminar tu propia cuenta.');
        }

        $user->delete();

        return redirect()->route('admin.users')->with('success', "Usuario {$user->name} eliminado.");
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

    public function destroyRace(Race $race): RedirectResponse
    {
        PersonalRecord::where('race_id', $race->id)->delete();
        Expense::where('race_id', $race->id)->where('category', 'registration')->delete();
        $race->delete();

        return redirect()->route('admin.races')->with('success', "Carrera \"{$race->name}\" eliminada.");
    }
}
