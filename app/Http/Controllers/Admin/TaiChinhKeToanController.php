<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ChamCong;
use App\Models\HopDong;
use App\Models\PhieuThuChi;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class TaiChinhKeToanController extends Controller
{
    public function index()
    {
        return view('admin.tai-chinh.index');
    }
    public function congNo(Request $request)
    {
        $request->validate([
            'tu_ngay' => 'nullable|date',
            'den_ngay' => 'nullable|date',
            'trang_thai_tt' => 'nullable|in:chua,da',
        ]);

        $conLaiExpr = '(COALESCE(tong_tien,0) - COALESCE(so_tien_giam_gia,0) - COALESCE(thanh_toan_lan_1,0) - COALESCE(thanh_toan_lan_2,0) - COALESCE(thanh_toan_lan_3,0))';

        $query = HopDong::query()
            ->with(['khachHang'])
            ->orderByDesc('id');

        if ($request->filled('tu_ngay')) {
            $query->whereDate('created_at', '>=', $request->input('tu_ngay'));
        }
        if ($request->filled('den_ngay')) {
            $query->whereDate('created_at', '<=', $request->input('den_ngay'));
        }

        $trangThaiTt = $request->input('trang_thai_tt');
        if ($trangThaiTt === 'chua') {
            $query->whereRaw("{$conLaiExpr} > 0");
        } elseif ($trangThaiTt === 'da') {
            $query->whereRaw("{$conLaiExpr} <= 0");
        }

        $danhSach = $query->paginate(15)->withQueryString();

        return view('admin.tai-chinh.cong-no', compact('danhSach'));
    }
    public function phieuThuChi(Request $request)
    {
        $query = PhieuThuChi::query()->with(['nguoiTao', 'nguoiDuyet']);
        if ($request->filled('search')) {
            $query->where('ly_do', 'like', '%' . $request->search . '%');
        }
        if ($request->filled('loai_phieu')) {
            $query->where('loai_phieu', (int) $request->loai_phieu);
        }
        if ($request->filled('trang_thai') && $request->trang_thai !== '') {
            $query->where('trang_thai', (int) $request->trang_thai);
        }
        $danhSach = $query->orderByDesc('created_at')->paginate(15)->withQueryString();
        return view('admin.tai-chinh.phieu-thu-chi', compact('danhSach'));
    }
    public function storeCongNo(Request $request)
    {
        $validated = $request->validate([
            'ten_cong_no' => 'required|string|max:255',
            'so_tien' => 'required|numeric|min:0',
        ]);
    }
    public function updateCongNo(Request $request, CongNo $congNo)
    {
        $validated = $request->validate([
            'ten_cong_no' => 'required|string|max:255',
            'so_tien' => 'required|numeric|min:0',
        ]);
    }
    public function destroyCongNo(CongNo $congNo)
    {
        $congNo->delete();
        return redirect()->route('admin.tai-chinh.cong-no')->with('success', 'Đã xóa công nợ thành công.');
    }
    public function storePhieuThuChi(Request $request)
    {
        $validated = $request->validate([
            'loai_phieu' => 'required|in:1,2',
            'so_tien' => 'required|numeric|min:0',
            'ly_do' => 'required|string|max:255',
            'ghi_chu' => 'nullable|string|max:500',
        ]);
        $validated['nguoi_tao_id'] = $request->user()->id;
        $validated['trang_thai'] = PhieuThuChi::TRANG_THAI_CHO_XU_LY;
        PhieuThuChi::create($validated);
        return redirect()->route('admin.tai-chinh.phieu-thu-chi')->with('success', 'Đã thêm phiếu thu chi thành công.');
    }
    public function updatePhieuThuChi(Request $request, PhieuThuChi $phieuThuChi)
    {
        $validated = $request->validate([
            'loai_phieu' => 'required|in:1,2',
            'so_tien' => 'required|numeric|min:0',
            'ly_do' => 'required|string|max:255',
            'trang_thai' => 'nullable|in:-1,0,1,2',
            'ghi_chu' => 'nullable|string|max:500',
        ]);
        $validated['trang_thai'] = (int) ($validated['trang_thai'] ?? $phieuThuChi->trang_thai);
        $phieuThuChi->update($validated);
        return redirect()->route('admin.tai-chinh.phieu-thu-chi')->with('success', 'Đã cập nhật phiếu thu chi thành công.');
    }
    public function destroyPhieuThuChi(PhieuThuChi $phieuThuChi)
    {
        $phieuThuChi->delete();
        return redirect()->route('admin.tai-chinh.phieu-thu-chi')->with('success', 'Đã xóa phiếu thu chi thành công.');
    }

    public function duyetPhieuThuChi(PhieuThuChi $phieuThuChi)
    {
        if ((int) $phieuThuChi->trang_thai !== PhieuThuChi::TRANG_THAI_CHO_XU_LY) {
            return redirect()->route('admin.tai-chinh.phieu-thu-chi')->with('error', 'Chỉ được duyệt phiếu đang chờ xử lý.');
        }
        $phieuThuChi->update([
            'trang_thai' => PhieuThuChi::TRANG_THAI_DONG_Y,
            'nguoi_duyet_id' => request()->user()->id,
            'ngay_duyet' => now(),
        ]);
        return redirect()->route('admin.tai-chinh.phieu-thu-chi')->with('success', 'Đã duyệt phiếu thu chi.');
    }

    public function huyPhieuThuChi(PhieuThuChi $phieuThuChi)
    {
        if ((int) $phieuThuChi->trang_thai !== PhieuThuChi::TRANG_THAI_CHO_XU_LY) {
            return redirect()->route('admin.tai-chinh.phieu-thu-chi')->with('error', 'Chỉ được hủy phiếu đang chờ xử lý.');
        }
        $phieuThuChi->update([
            'trang_thai' => PhieuThuChi::TRANG_THAI_TU_CHOI,
            'nguoi_duyet_id' => request()->user()->id,
            'ngay_duyet' => now(),
        ]);
        return redirect()->route('admin.tai-chinh.phieu-thu-chi')->with('success', 'Đã hủy phiếu thu chi.');
    }
    public function tinhLuong(Request $request)
    {
        $month = (int) $request->query('month', now()->month);
        $year = (int) $request->query('year', now()->year);

        if ($month < 1 || $month > 12) {
            $month = now()->month;
        }
        if ($year < 2000 || $year > 2100) {
            $year = now()->year;
        }

        $start = Carbon::create($year, $month, 1)->startOfMonth();
        $end = (clone $start)->endOfMonth();

        $startStr = $start->format('Y-m-d');
        $endStr = $end->format('Y-m-d');

        $ngayTrongThang = [];
        for ($d = (clone $start); $d->lte($end); $d->addDay()) {
            $ngayTrongThang[] = (clone $d);
        }

        // User có chấm công trong tháng (giống màn chấm công) để dữ liệu điểm danh khớp
        $userIdsCoChamCong = ChamCong::query()
            ->whereBetween('ngay_diem_danh', [$startStr, $endStr])
            ->distinct()
            ->pluck('user_id');

        $nhanVien = User::query()
            ->where(function ($q) use ($userIdsCoChamCong) {
                $q->where('role', User::ROLE_NHAN_VIEN)
                    ->orWhereIn('id', $userIdsCoChamCong);
            })
            ->orderBy('name')
            ->get();

        $chamCong = ChamCong::query()
            ->with(['user', 'diemDanh'])
            ->whereBetween('ngay_diem_danh', [$startStr, $endStr])
            ->whereIn('user_id', $nhanVien->pluck('id'))
            ->get();

        $bangChamCong = [];
        $bangLuongThang = [];
        foreach ($nhanVien as $u) {
            $bangLuongThang[$u->id] = [
                'luong_co_ban' => 0,
                'luong_tang_ca' => 0,
                'tong_luong' => 0,
            ];
        }
        foreach ($chamCong as $record) {
            $date = $record->ngay_diem_danh;
            $dateKey = $date ? Carbon::parse($date)->format('Y-m-d') : null;
            if (!$dateKey) {
                continue;
            }
            $bangChamCong[$dateKey][$record->user_id] = $record;

            $diemDanh = $record->diemDanh;
            if ($diemDanh) {
                $uid = $record->user_id;
                $bangLuongThang[$uid]['luong_co_ban'] += (float) ($diemDanh->luong_co_ban ?? 0);
                $bangLuongThang[$uid]['luong_tang_ca'] += (float) ($diemDanh->luong_tang_ca ?? 0);
            }
        }
        foreach (array_keys($bangLuongThang) as $uid) {
            $bangLuongThang[$uid]['tong_luong'] = $bangLuongThang[$uid]['luong_co_ban'] + $bangLuongThang[$uid]['luong_tang_ca'];
        }

        return view('admin.tai-chinh.tinh-luong', [
            'month' => $month,
            'year' => $year,
            'start' => $start,
            'end' => $end,
            'ngayTrongThang' => $ngayTrongThang,
            'nhanVien' => $nhanVien,
            'bangChamCong' => $bangChamCong,
            'bangLuongThang' => $bangLuongThang,
        ]);
    }
    public function storeTinhLuong(Request $request)
    {
        $validated = $request->validate([
            'thang' => 'required|integer|min:1|max:12',
        ]);
    }
}
