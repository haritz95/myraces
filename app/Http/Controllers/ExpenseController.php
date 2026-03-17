<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreExpenseRequest;
use App\Http\Requests\UpdateExpenseRequest;
use App\Models\Expense;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ExpenseController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();

        $query = $user->expenses()->with('race')->orderByDesc('date');

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        if ($request->filled('year')) {
            $query->whereYear('date', $request->year);
        }

        $expenses = $query->paginate(20)->withQueryString();

        $totalByCategory = $user->expenses()
            ->selectRaw('category, SUM(amount) as total')
            ->groupBy('category')
            ->pluck('total', 'category');

        $yearlyTotal = $user->expenses()
            ->whereYear('date', now()->year)
            ->sum('amount');

        $years = $user->expenses()
            ->selectRaw('strftime("%Y", date) as year')
            ->distinct()
            ->orderByDesc('year')
            ->pluck('year');

        $races = $user->races()->orderByDesc('date')->get(['id', 'name', 'date']);

        return view('expenses.index', compact('expenses', 'totalByCategory', 'yearlyTotal', 'years', 'races'));
    }

    public function create(Request $request): View
    {
        $races = $request->user()->races()->orderByDesc('date')->get(['id', 'name', 'date']);

        return view('expenses.create', compact('races'));
    }

    public function store(StoreExpenseRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['user_id'] = $request->user()->id;

        Expense::query()->create($data);

        return redirect()->route('expenses.index')
            ->with('success', 'Gasto registrado correctamente.');
    }

    public function edit(Expense $expense): View
    {
        $this->authorizeExpense($expense);

        $races = auth()->user()->races()->orderByDesc('date')->get(['id', 'name', 'date']);

        return view('expenses.edit', compact('expense', 'races'));
    }

    public function update(UpdateExpenseRequest $request, Expense $expense): RedirectResponse
    {
        $this->authorizeExpense($expense);

        $expense->update($request->validated());

        return redirect()->route('expenses.index')
            ->with('success', 'Gasto actualizado correctamente.');
    }

    public function destroy(Expense $expense): RedirectResponse
    {
        $this->authorizeExpense($expense);

        $expense->delete();

        return redirect()->route('expenses.index')
            ->with('success', 'Gasto eliminado.');
    }

    private function authorizeExpense(Expense $expense): void
    {
        abort_unless($expense->user_id === auth()->id(), 403);
    }
}
