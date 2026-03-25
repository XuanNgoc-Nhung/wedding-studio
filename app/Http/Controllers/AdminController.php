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
    public function index()
    {
        $conLaiExpr = '(COALESCE(tong_tien,0) - COALESCE(so_tien_giam_gia,0) - COALESCE(thanh_toan_lan_1,0) - COALESCE(thanh_toan_lan_2,0) - COALESCE(thanh_toan_lan_3,0))';

        $tongNhanVien = NhanVien::query()->count();
        $tongKhachHang = KhachHang::query()->count();
        $tongHopDong = HopDong::query()->count();

        $tongCongNo = (float) HopDong::query()
            ->selectRaw('COALESCE(SUM(GREATEST(0, '.$conLaiExpr.')),0) as t')
            ->value('t');

        $soHopDongConNo = HopDong::query()->whereRaw("{$conLaiExpr} > 0")->count();

        $tongDaThuTuHopDong = (float) HopDong::query()
            ->selectRaw('COALESCE(SUM(COALESCE(thanh_toan_lan_1,0) + COALESCE(thanh_toan_lan_2,0) + COALESCE(thanh_toan_lan_3,0)),0) as t')
            ->value('t');

        $tongGiaTriHopDong = (float) HopDong::query()
            ->selectRaw('COALESCE(SUM(COALESCE(tong_tien,0) - COALESCE(so_tien_giam_gia,0)),0) as t')
            ->value('t');

        // Trạng thái HĐ trên dashboard: chỉ 2 nhóm — các giá trị lưu trong DB được coi là "đã hoàn thành"
        $giaTriTrangThaiDaHoanThanh = [
            'Đã hoàn thành',
            'đã hoàn thành',
            'Hoàn thành',
            'hoàn thành',
            'hoan_thanh',
            'da_hoan_thanh',
            '1'
        ];
        $soHopDongDaHoanThanh = HopDong::query()
            ->whereIn('trang_thai_hop_dong', $giaTriTrangThaiDaHoanThanh)
            ->count();
        $soHopDongChuaHoanThanh = max(0, $tongHopDong - $soHopDongDaHoanThanh);

        $hopDongTheoTrangThai = collect([
            ['trang_thai' => 'Đã hoàn thành', 'so_luong' => $soHopDongDaHoanThanh],
            ['trang_thai' => 'Chưa hoàn thành', 'so_luong' => $soHopDongChuaHoanThanh],
        ]);

        $tuThang = Carbon::now()->startOfMonth()->subMonths(5);
        $denCuoiThang = Carbon::now()->endOfMonth();

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
