<?php

namespace App\Http\Controllers;

use App\Models\HopDong;
use App\Models\KhachHang;
use App\Models\NhanVien;
use App\Models\PhieuThuChi;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class AdminController extends Controller
{
    public function index(Request $request)
    {
        $conLaiExpr = '(COALESCE(tong_tien,0) - COALESCE(so_tien_giam_gia,0) - COALESCE(thanh_toan_lan_1,0) - COALESCE(thanh_toan_lan_2,0) - COALESCE(thanh_toan_lan_3,0))';

        $tz = config('app.timezone') ?: 'Asia/Ho_Chi_Minh';
        $now = Carbon::now($tz);

        $allowedPresets = ['tuan_nay', 'tuan_truoc', 'thang_nay', 'thang_truoc'];
        $filterPreset = $request->query('preset');
        if (! is_string($filterPreset) || ! in_array($filterPreset, $allowedPresets, true)) {
            $filterPreset = null;
        }

        $tuKy = null;
        $denKy = null;
        $filterTuNgay = null;
        $filterDenNgay = null;

        if ($filterPreset) {
            switch ($filterPreset) {
                case 'tuan_nay':
                    $tuKy = $now->copy()->startOfWeek();
                    $denKy = $now->copy()->endOfWeek();
                    break;
                case 'tuan_truoc':
                    $ref = $now->copy()->subWeek();
                    $tuKy = $ref->copy()->startOfWeek();
                    $denKy = $ref->copy()->endOfWeek();
                    break;
                case 'thang_nay':
                    $tuKy = $now->copy()->startOfMonth();
                    $denKy = $now->copy()->endOfMonth();
                    break;
                case 'thang_truoc':
                    $ref = $now->copy()->subMonthNoOverflow();
                    $tuKy = $ref->copy()->startOfMonth();
                    $denKy = $ref->copy()->endOfMonth();
                    break;
            }
        } else {
            $tuStr = $request->query('tu_ngay');
            $denStr = $request->query('den_ngay');
            if (is_string($tuStr) && $tuStr !== '' && is_string($denStr) && $denStr !== '') {
                try {
                    $tuKy = Carbon::parse($tuStr, $tz)->startOfDay();
                    $denKy = Carbon::parse($denStr, $tz)->endOfDay();
                    if ($tuKy->gt($denKy)) {
                        [$tuKy, $denKy] = [$denKy->copy()->startOfDay(), $tuKy->copy()->endOfDay()];
                    }
                } catch (\Throwable $e) {
                    $tuKy = $denKy = null;
                }
            }
        }

        $modeLocKy = $tuKy !== null && $denKy !== null;

        if ($modeLocKy) {
            if ($tuKy->diffInDays($denKy) > 730) {
                $denKy = $tuKy->copy()->addDays(730)->endOfDay();
            }
            $filterTuNgay = $tuKy->format('Y-m-d');
            $filterDenNgay = $denKy->format('Y-m-d');
        }

        $hdTrongKy = fn () => HopDong::query()->when(
            $modeLocKy,
            fn ($b) => $b->whereBetween('created_at', [$tuKy, $denKy])
        );

        $tongNhanVien = NhanVien::query()
            ->when($modeLocKy, fn ($q) => $q->whereBetween('created_at', [$tuKy, $denKy]))
            ->count();

        $tongKhachHang = KhachHang::query()
            ->when($modeLocKy, fn ($q) => $q->whereBetween('created_at', [$tuKy, $denKy]))
            ->count();

        $tongHopDong = $hdTrongKy()->count();

        $tongCongNo = (float) $hdTrongKy()
            ->selectRaw('COALESCE(SUM(GREATEST(0, '.$conLaiExpr.')),0) as t')
            ->value('t');

        $soHopDongConNo = $hdTrongKy()
            ->whereRaw("{$conLaiExpr} > 0")
            ->count();

        $tongDaThuTuHopDong = (float) $hdTrongKy()
            ->selectRaw('COALESCE(SUM(COALESCE(thanh_toan_lan_1,0) + COALESCE(thanh_toan_lan_2,0) + COALESCE(thanh_toan_lan_3,0)),0) as t')
            ->value('t');

        $tongGiaTriHopDong = (float) $hdTrongKy()
            ->selectRaw('COALESCE(SUM(COALESCE(tong_tien,0) - COALESCE(so_tien_giam_gia,0)),0) as t')
            ->value('t');

        // Trạng thái HĐ: trong kỳ lọc chỉ tính các HĐ có created_at trong khoảng
        $giaTriTrangThaiDaHoanThanh = [
            'Đã hoàn thành',
            'đã hoàn thành',
            'Hoàn thành',
            'hoàn thành',
            'hoan_thanh',
            'da_hoan_thanh',
            '1',
        ];
        $soHopDongDaHoanThanh = $hdTrongKy()
            ->whereIn('trang_thai_hop_dong', $giaTriTrangThaiDaHoanThanh)
            ->count();
        $soHopDongChuaHoanThanh = max(0, $tongHopDong - $soHopDongDaHoanThanh);

        $hopDongTheoTrangThai = collect([
            ['trang_thai' => 'Đã hoàn thành', 'so_luong' => $soHopDongDaHoanThanh],
            ['trang_thai' => 'Chưa hoàn thành', 'so_luong' => $soHopDongChuaHoanThanh],
        ]);

        $tomTatKy = null;
        $chartTheoNgay = false;

        if (! $modeLocKy) {
            $tuThang = $now->copy()->startOfMonth()->subMonths(5);
            $denCuoiThang = $now->copy()->endOfMonth();

            $thangNhanLabels = [];
            $thangNhanKeys = [];
            for ($d = $tuThang->copy(); $d->lte($denCuoiThang); $d->addMonth()) {
                $thangNhanKeys[] = $d->format('Y-m');
                $thangNhanLabels[] = $d->format('m/Y');
            }

            $giaTriHopDongTheoThang = HopDong::query()
                ->where('created_at', '>=', $tuThang)
                ->selectRaw("DATE_FORMAT(created_at, '%Y-%m') as ym")
                ->selectRaw('SUM(COALESCE(tong_tien,0) - COALESCE(so_tien_giam_gia,0)) as total')
                ->groupBy(DB::raw("DATE_FORMAT(created_at, '%Y-%m')"))
                ->pluck('total', 'ym');

            $phieuThuTheoThang = PhieuThuChi::query()
                ->where('loai_phieu', PhieuThuChi::LOAI_THU)
                ->whereIn('trang_thai', [
                    PhieuThuChi::TRANG_THAI_DONG_Y,
                    PhieuThuChi::TRANG_THAI_HOAN_THANH,
                ])
                ->whereRaw('COALESCE(ngay_duyet, created_at) >= ?', [$tuThang])
                ->selectRaw("DATE_FORMAT(COALESCE(ngay_duyet, created_at), '%Y-%m') as ym")
                ->selectRaw('SUM(COALESCE(so_tien,0)) as total')
                ->groupBy(DB::raw("DATE_FORMAT(COALESCE(ngay_duyet, created_at), '%Y-%m')"))
                ->pluck('total', 'ym');
        } else {
            $spanDays = $tuKy->copy()->startOfDay()->diffInDays($denKy->copy()->startOfDay()) + 1;
            $chartTheoNgay = $spanDays <= 45;

            $thangNhanLabels = [];
            $thangNhanKeys = [];

            if ($chartTheoNgay) {
                for ($d = $tuKy->copy()->startOfDay(); $d->lte($denKy); $d->addDay()) {
                    $thangNhanKeys[] = $d->format('Y-m-d');
                    $thangNhanLabels[] = $d->format('d/m');
                }
            } else {
                $startM = $tuKy->copy()->startOfMonth();
                $endM = $denKy->copy()->endOfMonth();
                for ($d = $startM->copy(); $d->lte($endM); $d->addMonth()) {
                    $thangNhanKeys[] = $d->format('Y-m');
                    $thangNhanLabels[] = $d->format('m/Y');
                }
            }

            $tomTatKy = [
                'so_hd_moi' => HopDong::query()->whereBetween('created_at', [$tuKy, $denKy])->count(),
                'gia_tri_hd_moi' => (float) HopDong::query()
                    ->whereBetween('created_at', [$tuKy, $denKy])
                    ->selectRaw('COALESCE(SUM(COALESCE(tong_tien,0) - COALESCE(so_tien_giam_gia,0)),0) as t')
                    ->value('t'),
                'phieu_thu' => (float) PhieuThuChi::query()
                    ->where('loai_phieu', PhieuThuChi::LOAI_THU)
                    ->whereIn('trang_thai', [
                        PhieuThuChi::TRANG_THAI_DONG_Y,
                        PhieuThuChi::TRANG_THAI_HOAN_THANH,
                    ])
                    ->whereRaw('COALESCE(ngay_duyet, created_at) BETWEEN ? AND ?', [$tuKy, $denKy])
                    ->selectRaw('COALESCE(SUM(COALESCE(so_tien,0)),0) as t')
                    ->value('t'),
            ];

            if ($chartTheoNgay) {
                $giaTriHopDongTheoThang = HopDong::query()
                    ->whereBetween('created_at', [$tuKy, $denKy])
                    ->selectRaw('DATE(created_at) as d')
                    ->selectRaw('SUM(COALESCE(tong_tien,0) - COALESCE(so_tien_giam_gia,0)) as total')
                    ->groupBy(DB::raw('DATE(created_at)'))
                    ->pluck('total', 'd');

                $phieuThuTheoThang = PhieuThuChi::query()
                    ->where('loai_phieu', PhieuThuChi::LOAI_THU)
                    ->whereIn('trang_thai', [
                        PhieuThuChi::TRANG_THAI_DONG_Y,
                        PhieuThuChi::TRANG_THAI_HOAN_THANH,
                    ])
                    ->whereRaw('COALESCE(ngay_duyet, created_at) BETWEEN ? AND ?', [$tuKy, $denKy])
                    ->selectRaw('DATE(COALESCE(ngay_duyet, created_at)) as d')
                    ->selectRaw('SUM(COALESCE(so_tien,0)) as total')
                    ->groupBy(DB::raw('DATE(COALESCE(ngay_duyet, created_at))'))
                    ->pluck('total', 'd');
            } else {
                $giaTriHopDongTheoThang = HopDong::query()
                    ->whereBetween('created_at', [$tuKy, $denKy])
                    ->selectRaw("DATE_FORMAT(created_at, '%Y-%m') as ym")
                    ->selectRaw('SUM(COALESCE(tong_tien,0) - COALESCE(so_tien_giam_gia,0)) as total')
                    ->groupBy(DB::raw("DATE_FORMAT(created_at, '%Y-%m')"))
                    ->pluck('total', 'ym');

                $phieuThuTheoThang = PhieuThuChi::query()
                    ->where('loai_phieu', PhieuThuChi::LOAI_THU)
                    ->whereIn('trang_thai', [
                        PhieuThuChi::TRANG_THAI_DONG_Y,
                        PhieuThuChi::TRANG_THAI_HOAN_THANH,
                    ])
                    ->whereRaw('COALESCE(ngay_duyet, created_at) BETWEEN ? AND ?', [$tuKy, $denKy])
                    ->selectRaw("DATE_FORMAT(COALESCE(ngay_duyet, created_at), '%Y-%m') as ym")
                    ->selectRaw('SUM(COALESCE(so_tien,0)) as total')
                    ->groupBy(DB::raw("DATE_FORMAT(COALESCE(ngay_duyet, created_at), '%Y-%m')"))
                    ->pluck('total', 'ym');
            }
        }

        $seriesGiaTriHopDong = [];
        $seriesPhieuThu = [];
        $bangDoanhThuThang = [];
        foreach ($thangNhanKeys as $i => $key) {
            $gv = (float) ($giaTriHopDongTheoThang[$key] ?? 0);
            $pt = (float) ($phieuThuTheoThang[$key] ?? 0);
            $seriesGiaTriHopDong[] = round($gv, 2);
            $seriesPhieuThu[] = round($pt, 2);
            $bangDoanhThuThang[] = [
                'label' => $thangNhanLabels[$i],
                'gia_tri_hd_moi' => $gv,
                'phieu_thu_duyet' => $pt,
            ];
        }

        return view('admin.index', compact(
            'tongNhanVien',
            'tongKhachHang',
            'tongHopDong',
            'tongCongNo',
            'soHopDongConNo',
            'tongDaThuTuHopDong',
            'tongGiaTriHopDong',
            'hopDongTheoTrangThai',
            'thangNhanLabels',
            'seriesGiaTriHopDong',
            'seriesPhieuThu',
            'bangDoanhThuThang',
            'modeLocKy',
            'filterTuNgay',
            'filterDenNgay',
            'filterPreset',
            'tomTatKy',
            'chartTheoNgay',
        ));
    }

    public function thongTinCaNhan()
    {
        $user = auth()->user()->load('nhanVien');
        $nhanVien = $user->nhanVien;
        return view('admin.thong-tin-ca-nhan', compact('user', 'nhanVien'));
    }

    public function capNhatThongTinCaNhan(Request $request)
    {
        $user = auth()->user();

        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'phone' => ['nullable', 'string', 'max:20', Rule::unique('users')->ignore($user->id)],
        ];

        $nhanVien = $user->nhanVien;
        if ($nhanVien) {
            $rules['gioi_tinh'] = ['nullable', 'string', 'in:nam,nu,khac'];
            $rules['ngay_sinh'] = ['nullable', 'date'];
            $rules['cccd'] = ['nullable', 'string', 'max:20'];
            $rules['hinh_anh'] = ['nullable', 'image', 'max:2048'];
        }

        $validated = $request->validate($rules);

        $user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
        ]);

        if ($nhanVien) {
            $data = [
                'gioi_tinh' => $validated['gioi_tinh'] ?? null,
                'ngay_sinh' => $validated['ngay_sinh'] ?? null,
                'cccd' => $validated['cccd'] ?? null,
            ];
            if ($request->hasFile('hinh_anh')) {
                if ($nhanVien->hinh_anh) {
                    Storage::disk('public')->delete($nhanVien->hinh_anh);
                }
                $data['hinh_anh'] = $request->file('hinh_anh')->store('nhan-vien', 'public');
            }
            $nhanVien->update($data);
        }

        return redirect()->route('admin.thong-tin-ca-nhan')->with('success', 'Đã cập nhật thông tin cá nhân.');
    }

    public function doiMatKhau(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'password_current' => [
                'required',
                function ($attribute, $value, $fail) use ($user) {
                    if (! Hash::check($value, $user->password)) {
                        $fail('Mật khẩu hiện tại không đúng.');
                    }
                },
            ],
            'password_new' => [
                'required',
                'confirmed',
                Password::min(8)
                    ->letters()
                    ->mixedCase()
                    ->numbers()
                    ->symbols(),
            ],
        ], [
            'password_new.required' => 'Vui lòng nhập mật khẩu mới.',
            'password_new.confirmed' => 'Xác nhận mật khẩu mới không khớp.',
            'password_new.min' => 'Mật khẩu mới phải có ít nhất 8 ký tự.',
        ]);

        $user->update([
            'password' => Hash::make($request->password_new),
        ]);

        if ($request->wantsJson()) {
            return response()->json(['message' => 'Đã đổi mật khẩu thành công.']);
        }

        return redirect()->route('admin.thong-tin-ca-nhan')->with('success_password', 'Đã đổi mật khẩu thành công.');
    }
}
