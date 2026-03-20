<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Feedback;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class FeedbackController extends Controller
{
    public function index(): View
    {
        $feedbacks = Feedback::with('user')
            ->latest()
            ->paginate(30);

        Feedback::whereRead(false)->update(['read' => true]);

        return view('admin.feedback.index', compact('feedbacks'));
    }

    public function destroy(Feedback $feedback): RedirectResponse
    {
        $feedback->delete();

        return back()->with('success', 'Feedback eliminado.');
    }
}
