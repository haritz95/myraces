<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NavItem;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class NavItemController extends Controller
{
    public function index(): View
    {
        $items = NavItem::ordered()->get()->groupBy('location');

        return view('admin.nav-items.index', compact('items'));
    }

    public function toggle(NavItem $navItem): RedirectResponse
    {
        if (! $navItem->is_enabled) {
            $bottomCount = NavItem::where('location', 'bottom_nav')->where('is_enabled', true)->count();
            if ($navItem->location === 'bottom_nav' && $bottomCount >= 4) {
                return back()->with('error', 'El menú inferior sólo admite 4 elementos. Mueve uno al drawer primero.');
            }
        }

        $navItem->update(['is_enabled' => ! $navItem->is_enabled]);

        return back()->with('success', $navItem->is_enabled
            ? "\"{$navItem->label}\" activado."
            : "\"{$navItem->label}\" desactivado."
        );
    }

    public function togglePremium(NavItem $navItem): RedirectResponse
    {
        $navItem->update(['is_premium' => ! $navItem->is_premium]);

        return back()->with('success', "\"{$navItem->label}\" marcado como ".($navItem->is_premium ? 'premium' : 'gratuito').'.');
    }

    public function move(Request $request, NavItem $navItem): RedirectResponse
    {
        $request->validate(['direction' => ['required', 'in:up,down']]);

        $sibling = NavItem::where('location', $navItem->location)
            ->when($request->direction === 'up',
                fn ($q) => $q->where('sort_order', '<', $navItem->sort_order)->orderByDesc('sort_order'),
                fn ($q) => $q->where('sort_order', '>', $navItem->sort_order)->orderBy('sort_order'),
            )
            ->first();

        if ($sibling) {
            [$navItem->sort_order, $sibling->sort_order] = [$sibling->sort_order, $navItem->sort_order];
            $navItem->save();
            $sibling->save();
        }

        return back();
    }

    public function updateLocation(Request $request, NavItem $navItem): RedirectResponse
    {
        $request->validate(['location' => ['required', 'in:bottom_nav,drawer']]);

        if ($request->location === 'bottom_nav') {
            $count = NavItem::where('location', 'bottom_nav')->where('is_enabled', true)->count();
            if ($count >= 4) {
                return back()->with('error', 'El menú inferior sólo admite 4 elementos.');
            }
        }

        $maxOrder = NavItem::where('location', $request->location)->max('sort_order') ?? 0;
        $navItem->update(['location' => $request->location, 'sort_order' => $maxOrder + 1]);

        return back()->with('success', "\"{$navItem->label}\" movido a ".($request->location === 'bottom_nav' ? 'menú inferior' : 'drawer').'.');
    }
}
