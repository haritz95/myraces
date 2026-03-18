<?php

namespace Database\Seeders;

use App\Models\NavItem;
use Illuminate\Database\Seeder;

class NavItemSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            // ── Bottom nav (FAB + "Más" are always rendered separately) ──
            [
                'key' => 'dashboard',
                'label' => 'Inicio',
                'route_name' => 'dashboard',
                'match_pattern' => 'dashboard',
                'icon_path' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6',
                'location' => 'bottom_nav',
                'sort_order' => 1,
                'is_enabled' => true,
                'is_premium' => false,
            ],
            [
                'key' => 'races',
                'label' => 'Carreras',
                'route_name' => 'races.index',
                'match_pattern' => 'races.index|races.show',
                'icon_path' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2',
                'location' => 'bottom_nav',
                'sort_order' => 2,
                'is_enabled' => true,
                'is_premium' => false,
            ],
            [
                'key' => 'coach',
                'label' => 'Coach',
                'route_name' => 'coach.index',
                'match_pattern' => 'coach.*',
                'icon_path' => 'M13 10V3L4 14h7v7l9-11h-7z',
                'location' => 'bottom_nav',
                'sort_order' => 3,
                'is_enabled' => false,
                'is_premium' => true,
            ],

            // ── Drawer ────────────────────────────────────────────────────
            [
                'key' => 'calendar',
                'label' => 'Calendario',
                'route_name' => 'calendar.index',
                'match_pattern' => 'calendar.*',
                'icon_path' => 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z',
                'location' => 'drawer',
                'sort_order' => 1,
                'is_enabled' => true,
                'is_premium' => false,
            ],
            [
                'key' => 'stats',
                'label' => 'Estadísticas',
                'route_name' => 'stats.index',
                'match_pattern' => 'stats.*',
                'icon_path' => 'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z',
                'location' => 'drawer',
                'sort_order' => 2,
                'is_enabled' => true,
                'is_premium' => false,
            ],
            [
                'key' => 'expenses',
                'label' => 'Gastos',
                'route_name' => 'expenses.index',
                'match_pattern' => 'expenses.*',
                'icon_path' => 'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z',
                'location' => 'drawer',
                'sort_order' => 3,
                'is_enabled' => true,
                'is_premium' => false,
            ],
            [
                'key' => 'records',
                'label' => 'Récords',
                'route_name' => 'personal-records.index',
                'match_pattern' => 'personal-records.*',
                'icon_path' => 'M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z',
                'location' => 'drawer',
                'sort_order' => 4,
                'is_enabled' => true,
                'is_premium' => false,
            ],
            [
                'key' => 'gear',
                'label' => 'Material',
                'route_name' => 'gear.index',
                'match_pattern' => 'gear.*',
                'icon_path' => 'M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z',
                'location' => 'drawer',
                'sort_order' => 5,
                'is_enabled' => true,
                'is_premium' => false,
            ],
            [
                'key' => 'profile',
                'label' => 'Perfil',
                'route_name' => 'profile.edit',
                'match_pattern' => 'profile.*',
                'icon_path' => 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z',
                'location' => 'drawer',
                'sort_order' => 6,
                'is_enabled' => true,
                'is_premium' => false,
            ],
        ];

        foreach ($items as $item) {
            NavItem::updateOrCreate(['key' => $item['key']], $item);
        }
    }
}
