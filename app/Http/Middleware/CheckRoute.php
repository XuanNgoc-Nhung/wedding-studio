<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Chỉ cho phép truy cập các route đã phân quyền (nằm trong nhan_vien.ds_menu).
 * User có role = 1 (admin) được phép truy cập mọi route.
 * Dùng cho nhóm route prefix admin.
 */
class CheckRoute
{
    /** Các route luôn được phép khi user chưa có ds_menu (thông tin cá nhân, đổi mật khẩu, tổng quan). */
    private const ALLOWED_WITHOUT_DS_MENU = [
        'admin.index',
        'admin.thong-tin-ca-nhan',
        'admin.thong-tin-ca-nhan.update',
        'admin.doi-mat-khau',
    ];

    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        if ($user && (int) $user->role === User::ROLE_ADMIN) {
            return $next($request);
        }

        $routeName = $request->route()?->getName();
        if ($routeName === null) {
            return $next($request);
        }
        $ds_menu = [];

        if ($user && $user->nhanVien) {
            $ds_menu = (array) ($user->nhanVien->ds_menu ?? []);
        }

        $allowed = count($ds_menu) > 0
            ? $ds_menu
            : self::ALLOWED_WITHOUT_DS_MENU;

        if (! in_array($routeName, $allowed, true)) {
            abort(403, 'Bạn không có quyền truy cập trang này.');
        }

        return $next($request);
    }
}
