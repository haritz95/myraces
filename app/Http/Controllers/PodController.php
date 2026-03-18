<?php

namespace App\Http\Controllers;

use App\Models\Pod;
use App\Models\PodMessage;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PodController extends Controller
{
    public function index(Request $request): View
    {
        $myPods = $request->user()->pods()->with('creator')->withCount('members')->latest()->get();

        $discover = Pod::active()
            ->where('created_by', '!=', $request->user()->id)
            ->whereDoesntHave('members', fn ($q) => $q->where('user_id', $request->user()->id))
            ->withCount('members')
            ->latest()
            ->get();

        $streak = $request->user()->streak;
        $canCreatePod = $request->user()->is_admin || ! $this->hasReachedPodLimit($request->user());

        return view('pods.index', compact('myPods', 'discover', 'streak', 'canCreatePod'));
    }

    private const MAX_PODS_PER_USER = 3;

    public function create(Request $request): View|RedirectResponse
    {
        if (! $request->user()->is_admin && $this->hasReachedPodLimit($request->user())) {
            return redirect()->route('pods.index')
                ->with('error', 'Has alcanzado el límite de '.self::MAX_PODS_PER_USER.' pods activos.');
        }

        return view('pods.create');
    }

    public function store(Request $request): RedirectResponse
    {
        if (! $request->user()->is_admin && $this->hasReachedPodLimit($request->user())) {
            return redirect()->route('pods.index')
                ->with('error', 'Has alcanzado el límite de '.self::MAX_PODS_PER_USER.' pods activos.');
        }

        $data = $request->validate([
            'name' => ['required', 'string', 'max:60'],
            'description' => ['nullable', 'string', 'max:500'],
            'goal' => ['required', 'string', 'max:120'],
            'target_distance' => ['nullable', 'numeric', 'min:0'],
            'target_unit' => ['required', 'in:km,mi'],
            'max_members' => ['required', 'integer', 'min:2', 'max:10'],
            'starts_at' => ['nullable', 'date'],
            'ends_at' => ['nullable', 'date', 'after_or_equal:starts_at'],
        ]);

        $data['created_by'] = $request->user()->id;

        $pod = Pod::create($data);

        $pod->members()->attach($request->user()->id, [
            'role' => 'leader',
            'joined_at' => now(),
        ]);

        PodMessage::create([
            'pod_id' => $pod->id,
            'user_id' => null,
            'type' => 'system',
            'message' => "¡Pod «{$pod->name}» creado! Objetivo: {$pod->goal}. ¡Bienvenidos! 🚀",
        ]);

        return redirect()->route('pods.show', $pod)
            ->with('success', "Pod «{$pod->name}» creado. ¡Comparte el enlace!");
    }

    public function show(Request $request, Pod $pod): View
    {
        $pod->load('creator');

        $messages = $pod->messages()->with('user')->latest()->limit(50)->get()->reverse()->values();
        $members = $pod->members()->withPivot(['role', 'points', 'joined_at'])->orderByPivot('points', 'desc')->get();
        $streak = $request->user()->streak;
        $isMember = $pod->hasMember($request->user());
        $isFull = $pod->isFull();
        $userPoints = $isMember ? ($members->firstWhere('id', $request->user()->id)?->pivot->points ?? 0) : 0;

        return view('pods.show', compact('pod', 'messages', 'members', 'streak', 'isMember', 'isFull', 'userPoints'));
    }

    public function join(Request $request, Pod $pod): RedirectResponse
    {
        $user = $request->user();

        if ($pod->hasMember($user)) {
            return back()->with('error', 'Ya eres miembro de este Pod.');
        }

        if ($pod->isFull()) {
            return back()->with('error', 'Este Pod está lleno.');
        }

        $pod->members()->attach($user->id, ['role' => 'member', 'joined_at' => now()]);

        PodMessage::create([
            'pod_id' => $pod->id,
            'user_id' => null,
            'type' => 'system',
            'message' => "¡{$user->name} se unió al Pod! 👋 Bienvenido/a al equipo.",
        ]);

        return redirect()->route('pods.show', $pod)
            ->with('success', "¡Te has unido a «{$pod->name}»!");
    }

    public function leave(Request $request, Pod $pod): RedirectResponse
    {
        $user = $request->user();

        if (! $pod->hasMember($user)) {
            return back()->with('error', 'No eres miembro de este Pod.');
        }

        $pod->members()->detach($user->id);

        PodMessage::create([
            'pod_id' => $pod->id,
            'user_id' => null,
            'type' => 'system',
            'message' => "{$user->name} ha abandonado el Pod.",
        ]);

        return redirect()->route('pods.index')->with('success', 'Has salido del Pod.');
    }

    public function sendMessage(Request $request, Pod $pod): JsonResponse
    {
        abort_if(! $pod->hasMember($request->user()), 403);

        $request->validate(['message' => ['required', 'string', 'max:500']]);

        $msg = PodMessage::create([
            'pod_id' => $pod->id,
            'user_id' => $request->user()->id,
            'type' => 'text',
            'message' => $request->message,
        ]);

        $msg->load('user');

        return response()->json($this->formatMessage($msg));
    }

    public function messages(Request $request, Pod $pod): JsonResponse
    {
        abort_if(! $pod->hasMember($request->user()), 403);

        $since = $request->integer('since', 0);

        $messages = $pod->messages()
            ->with('user')
            ->when($since > 0, fn ($q) => $q->where('id', '>', $since))
            ->latest()
            ->limit(50)
            ->get()
            ->reverse()
            ->values()
            ->map(fn (PodMessage $m) => $this->formatMessage($m));

        return response()->json($messages);
    }

    private function hasReachedPodLimit(User $user): bool
    {
        return $user->pods()
            ->whereIn('status', ['active', 'pending'])
            ->wherePivot('role', 'leader')
            ->count() >= self::MAX_PODS_PER_USER;
    }

    /** @return array<string, mixed> */
    private function formatMessage(PodMessage $message): array
    {
        return [
            'id' => $message->id,
            'type' => $message->type,
            'message' => $message->message,
            'user' => $message->user ? ['name' => $message->user->name] : null,
            'created_at' => $message->created_at->diffForHumans(),
        ];
    }
}
