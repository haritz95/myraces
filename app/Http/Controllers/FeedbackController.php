<?php

namespace App\Http\Controllers;

use App\Models\Feedback;
use App\Models\Setting;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FeedbackController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        abort_if(Setting::get('feedback_widget_enabled', '1') === '0', 403);

        // Hard cap: no more than 20 total feedbacks per user
        abort_if(Feedback::where('user_id', $request->user()->id)->count() >= 20, 429);

        $data = $request->validate([
            'type' => ['required', 'in:bug,suggestion,other'],
            'message' => ['required', 'string', 'min:10', 'max:1000'],
            'url' => ['nullable', 'string', 'max:500'],
        ]);

        Feedback::create([
            'user_id' => $request->user()->id,
            'type' => $data['type'],
            'message' => $data['message'],
            'url' => $data['url'] ?? null,
        ]);

        return response()->json(['ok' => true]);
    }
}
