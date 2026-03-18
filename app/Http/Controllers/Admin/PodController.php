<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pod;
use App\Models\PodMessage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PodController extends Controller
{
    public function index(Request $request): View
    {
        $pods = Pod::with('creator')
            ->withCount('members')
            ->when($request->search, fn ($q) => $q->where('name', 'like', "%{$request->search}%")
                ->orWhere('goal', 'like', "%{$request->search}%"))
            ->when($request->status, fn ($q) => $q->where('status', $request->status))
            ->latest()
            ->paginate(20);

        $stats = [
            'total' => Pod::count(),
            'active' => Pod::where('status', 'active')->count(),
            'completed' => Pod::where('status', 'completed')->count(),
            'archived' => Pod::where('status', 'archived')->count(),
        ];

        return view('admin.pods.index', compact('pods', 'stats'));
    }

    public function show(Pod $pod): View
    {
        $pod->load('creator');
        $members = $pod->members()->withPivot(['role', 'points', 'joined_at'])->orderByPivot('points', 'desc')->get();
        $messages = $pod->messages()->with('user')->latest()->limit(30)->get();

        return view('admin.pods.show', compact('pod', 'members', 'messages'));
    }

    public function edit(Pod $pod): View
    {
        $pod->load('creator');

        return view('admin.pods.edit', compact('pod'));
    }

    public function update(Request $request, Pod $pod): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:60'],
            'goal' => ['required', 'string', 'max:120'],
            'description' => ['nullable', 'string', 'max:500'],
            'status' => ['required', 'in:active,completed,archived'],
            'target_distance' => ['nullable', 'numeric', 'min:0'],
            'target_unit' => ['required', 'in:km,mi'],
            'max_members' => ['required', 'integer', 'min:2', 'max:50'],
            'starts_at' => ['nullable', 'date'],
            'ends_at' => ['nullable', 'date', 'after_or_equal:starts_at'],
        ]);

        $pod->update($data);

        return redirect()->route('admin.pods.show', $pod)
            ->with('success', "Pod \"{$pod->name}\" actualizado.");
    }

    public function destroy(Pod $pod): RedirectResponse
    {
        $name = $pod->name;
        $pod->members()->detach();
        $pod->messages()->delete();
        $pod->delete();

        return redirect()->route('admin.pods.index')
            ->with('success', "Pod \"{$name}\" eliminado.");
    }

    public function removeMember(Pod $pod, int $userId): RedirectResponse
    {
        $pod->members()->detach($userId);

        PodMessage::create([
            'pod_id' => $pod->id,
            'user_id' => null,
            'type' => 'system',
            'message' => 'Un miembro fue eliminado del Pod por un administrador.',
        ]);

        return redirect()->route('admin.pods.show', $pod)
            ->with('success', 'Miembro eliminado del Pod.');
    }
}
