<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PhieuThuChi;
use Illuminate\Http\Request;

class TaiChinhKeToanController extends Controller
{
    public function index()
    {
        return view('admin.tai-chinh.index');
    }
    public function congNo()
    {
        return view('admin.tai-chinh.cong-no');
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
}
