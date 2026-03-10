<?php

namespace App\Providers;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     * Sidebar: danh sách menu đầy đủ lấy từ config (admin_menu).
     * Chỉ hiển thị các mục có route nằm trong nhan_vien.ds_menu.
     */
    public function boot(): void
    {
        View::composer(['admin.layouts.app', 'admin.layouts.components.sidebar'], function ($view) {
            Log::debug('[Sidebar] View composer bắt đầu', ['view' => $view->name()]);

            $user = auth()->user();
            // ds_menu: danh sách route được phép xem (từ bảng nhan_vien)
            $ds_menu = [];
            if ($user && $user->nhanVien) {
                $ds_menu = (array) ($user->nhanVien->ds_menu ?? []);
            }

            Log::debug('[Sidebar] User & ds_menu', [
                'user_id' => $user?->id,
                'ds_menu_count' => count($ds_menu),
                'ds_menu' => $ds_menu,
            ]);

            // Toàn bộ menu từ config, lọc theo ds_menu (chỉ giữ mục có route trong ds_menu)
            $sidebarMenuItems = self::filterMenuByDsMenu($ds_menu);

            Log::debug('[Sidebar] Kết quả menu sau lọc', [
                'sidebar_menu_items_count' => count($sidebarMenuItems),
            ]);

            $view->with('sidebarDsMenu', $ds_menu);
            $view->with('sidebarMenuItems', $sidebarMenuItems);
        });
    }

    /**
     * Lấy menu đầy đủ từ config, chỉ giữ lại mục có route nằm trong ds_menu.
     * @param array<string> $ds_menu nhan_vien.ds_menu
     */
    private static function filterMenuByDsMenu(array $ds_menu): array
    {
        Log::debug('[Sidebar] filterMenuByDsMenu', ['ds_menu_count' => count($ds_menu)]);

        $allItems = config('admin_menu', []);
        $result = [];

        foreach ($allItems as $item) {
            if ($item['type'] === 'single') {
                $route = $item['route'];
                if (in_array($route, $ds_menu, true)) {
                    $result[] = $item;
                }
                continue;
            }

            if ($item['type'] === 'group') {
                $children = [];
                foreach ($item['children'] as $child) {
                    $route = $child['route'];
                    if (in_array($route, $ds_menu, true)) {
                        $children[] = $child;
                    }
                }
                if (count($children) > 0) {
                    $result[] = array_merge($item, ['children' => $children]);
                }
            }
        }

        Log::debug('[Sidebar] filterMenuByDsMenu xong', [
            'config_items' => count($allItems),
            'result_items' => count($result),
        ]);

        return $result;
    }
}
