<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NavItem;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class NavItemController extends Controller
{
    public function index(): View
    {
        $items = NavItem::ordered()->get();

        return view('admin.nav-items.index', compact('items'));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'label' => ['required', 'string', 'max:30'],
            'route_name' => ['required', 'string', 'max:100'],
            'icon_path' => ['required', 'string'],
            'match_pattern' => ['required', 'string', 'max:200'],
            'location' => ['nullable', 'in:bottom_nav,drawer'],
            'show_desktop' => ['boolean'],
            'is_premium' => ['boolean'],
        ]);

        $mobileLocation = $request->location ?: null;

        if ($mobileLocation === 'bottom_nav') {
            $count = NavItem::where('location', 'bottom_nav')->where('is_enabled', true)->count();
            if ($count >= 4) {
                return back()->with('error', 'El menú inferior sólo admite 4 elementos activos.');
            }
        }

        $maxOrder = NavItem::max('sort_order') ?? 0;

        NavItem::create([
            'key' => Str::slug($request->label).'-'.Str::random(4),
            'label' => $request->label,
            'route_name' => $request->route_name,
            'icon_path' => $request->icon_path,
            'match_pattern' => $request->match_pattern,
            'location' => $mobileLocation,
            'show_desktop' => $request->boolean('show_desktop', true),
            'sort_order' => $maxOrder + 1,
            'is_enabled' => true,
            'is_premium' => $request->boolean('is_premium'),
        ]);

        return back()->with('success', "\"{$request->label}\" añadido a la navegación.");
    }

    public function update(Request $request, NavItem $navItem): RedirectResponse
    {
        $request->validate([
            'label' => ['required', 'string', 'max:30'],
            'route_name' => ['required', 'string', 'max:100'],
            'match_pattern' => ['required', 'string', 'max:200'],
            'icon_path' => ['required', 'string'],
            'location' => ['nullable', 'in:bottom_nav,drawer'],
            'show_desktop' => ['boolean'],
        ]);

        $newLocation = $request->location ?: null;

        if ($newLocation === 'bottom_nav' && $navItem->location !== 'bottom_nav') {
            $count = NavItem::where('location', 'bottom_nav')->where('is_enabled', true)->count();
            if ($count >= 4) {
                return back()->with('error', 'El menú inferior sólo admite 4 elementos activos.');
            }
        }

        $navItem->update([
            ...$request->only('label', 'route_name', 'match_pattern', 'icon_path'),
            'location' => $newLocation,
            'show_desktop' => $request->boolean('show_desktop'),
        ]);

        return back()->with('success', "\"{$navItem->label}\" actualizado.");
    }

    public function destroy(NavItem $navItem): RedirectResponse
    {
        $navItem->delete();

        return back()->with('success', "\"{$navItem->label}\" eliminado.");
    }

    public function toggle(NavItem $navItem): RedirectResponse
    {
        if (! $navItem->is_enabled && $navItem->location === 'bottom_nav') {
            $bottomCount = NavItem::where('location', 'bottom_nav')->where('is_enabled', true)->count();
            if ($bottomCount >= 4) {
                return back()->with('error', 'El menú inferior sólo admite 4 elementos. Mueve uno al drawer primero.');
            }
        }

        $navItem->update(['is_enabled' => ! $navItem->is_enabled]);

        return back()->with('success', $navItem->is_enabled
            ? "\"{$navItem->label}\" activado."
            : "\"{$navItem->label}\" desactivado."
        );
    }

    public function toggleDesktop(NavItem $navItem): RedirectResponse
    {
        $navItem->update(['show_desktop' => ! $navItem->show_desktop]);

        return back()->with('success', $navItem->show_desktop
            ? "\"{$navItem->label}\" visible en escritorio."
            : "\"{$navItem->label}\" oculto en escritorio."
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

        $up = $request->input('direction') === 'up';

        /** @var NavItem|null $sibling */
        $sibling = NavItem::query()
            ->when($up,
                fn ($q) => $q->where('sort_order', '<', $navItem->sort_order)->orderByDesc('sort_order'),
                fn ($q) => $q->where('sort_order', '>', $navItem->sort_order)->orderBy('sort_order'),
            )
            ->first();

        if ($sibling instanceof NavItem) {
            [$navItem->sort_order, $sibling->sort_order] = [$sibling->sort_order, $navItem->sort_order];
            $navItem->save();
            $sibling->save();
        }

        return back();
    }

    public function updateLocation(Request $request, NavItem $navItem): RedirectResponse
    {
        $request->validate(['location' => ['nullable', 'in:bottom_nav,drawer,none']]);

        $newLocation = $request->location === 'none' ? null : $request->location;

        if ($newLocation === 'bottom_nav') {
            $count = NavItem::where('location', 'bottom_nav')->where('is_enabled', true)->count();
            if ($count >= 4) {
                return back()->with('error', 'El menú inferior sólo admite 4 elementos.');
            }
        }

        $navItem->update(['location' => $newLocation]);

        $labels = ['bottom_nav' => 'menú inferior', 'drawer' => 'drawer', null => 'ningún menú móvil'];

        return back()->with('success', "\"{$navItem->label}\" → ".($labels[$newLocation] ?? 'oculto en móvil').'.');
    }
}
