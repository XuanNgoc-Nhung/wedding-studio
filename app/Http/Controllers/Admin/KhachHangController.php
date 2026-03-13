<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DichVuLe;
use App\Models\HopDong;
use App\Models\KhachHang;
use App\Models\NhanVien;
use App\Models\NhomDichVu;
use Illuminate\Http\Request;

class KhachHangController extends Controller
{
    public function index()
    {
        return redirect()->route('admin.khach-hang.danh-sach');
    }

    public function danhSach(Request $request)
    {
        $search = $request->get('search');

        $danhSach = KhachHang::query()
            ->when($search, function ($q) use ($search) {
                $q->where('ho_ten_chu_re', 'like', "%{$search}%")
                    ->orWhere('ho_ten_co_dau', 'like', "%{$search}%")
                    ->orWhere('email_hoac_sdt_chu_re', 'like', "%{$search}%")
                    ->orWhere('email_hoac_sdt_co_dau', 'like', "%{$search}%")
                    ->orWhere('dia_chi_chu_re', 'like', "%{$search}%")
                    ->orWhere('dia_chi_co_dau', 'like', "%{$search}%")
                    ->orWhere('ghi_chu', 'like', "%{$search}%");
            })
            ->orderBy('id')
            ->paginate(15)
            ->withQueryString();

        return view('admin.khach-hang.danh-sach', compact('danhSach'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'ho_ten_chu_re' => 'nullable|string|max:255',
            'ngay_sinh_chu_re' => 'nullable|date',
            'gioi_tinh_chu_re' => 'nullable|string|in:nam,nu,khac',
            'email_hoac_sdt_chu_re' => 'nullable|string|max:255',
            'dia_chi_chu_re' => 'nullable|string',
            'ho_ten_co_dau' => 'nullable|string|max:255',
            'ngay_sinh_co_dau' => 'nullable|date',
            'gioi_tinh_co_dau' => 'nullable|string|in:nam,nu,khac',
            'dia_chi_co_dau' => 'nullable|string',
            'email_hoac_sdt_co_dau' => 'nullable|string|max:255',
            'ghi_chu' => 'nullable|string',
        ]);

        KhachHang::create($validated);

        return redirect()->route('admin.khach-hang.danh-sach')->with('success', 'Đã thêm khách hàng thành công.');
    }

    public function update(Request $request, KhachHang $khachHang)
    {
        $validated = $request->validate([
            'ho_ten_chu_re' => 'nullable|string|max:255',
            'ngay_sinh_chu_re' => 'nullable|date',
            'gioi_tinh_chu_re' => 'nullable|string|in:nam,nu,khac',
            'email_hoac_sdt_chu_re' => 'nullable|string|max:255',
            'dia_chi_chu_re' => 'nullable|string',
            'ho_ten_co_dau' => 'nullable|string|max:255',
            'ngay_sinh_co_dau' => 'nullable|date',
            'gioi_tinh_co_dau' => 'nullable|string|in:nam,nu,khac',
            'dia_chi_co_dau' => 'nullable|string',
            'email_hoac_sdt_co_dau' => 'nullable|string|max:255',
            'ghi_chu' => 'nullable|string',
        ]);

        $khachHang->update($validated);

        return redirect()->route('admin.khach-hang.danh-sach')->with('success', 'Đã cập nhật khách hàng thành công.');
    }

    public function destroy(KhachHang $khachHang)
    {
        $khachHang->delete();
        return redirect()->route('admin.khach-hang.danh-sach')->with('success', 'Đã xóa khách hàng thành công.');
    }
    public function hopDong(Request $request)
    {
        $search = $request->get('search');
        $danhSach = HopDong::query()
            ->with(['khachHang', 'thoChup.user', 'thoMake.user', 'thoEdit.user'])
            ->when($search, function ($q) use ($search) {
                $q->whereHas('khachHang', function ($q2) use ($search) {
                    $q2->where('ho_ten_chu_re', 'like', "%{$search}%")
                        ->orWhere('ho_ten_co_dau', 'like', "%{$search}%")
                        ->orWhere('email_hoac_sdt_chu_re', 'like', "%{$search}%")
                        ->orWhere('email_hoac_sdt_co_dau', 'like', "%{$search}%");
                });
            })
            ->orderByDesc('id')
            ->paginate(15)
            ->withQueryString();

        $danhSachKhachHang = KhachHang::query()->orderBy('id')->get();
        $danhSachNhanVien = NhanVien::query()->with('user')->orderBy('id')->get();
        $danhSachNhomDichVu = NhomDichVu::query()
            ->with('dichVuLe')
            ->where('trang_thai', NhomDichVu::TRANG_THAI_HIEN_THI)
            ->orderBy('ten_nhom')
            ->get();
        $danhSachDichVuLe = DichVuLe::query()
            ->where('trang_thai', DichVuLe::TRANG_THAI_HIEN_THI)
            ->orderBy('ten_dich_vu')
            ->get();

        return view('admin.khach-hang.hop-dong', compact(
            'danhSach',
            'danhSachKhachHang',
            'danhSachNhanVien',
            'danhSachNhomDichVu',
            'danhSachDichVuLe'
        ));
    }

    public function storeHopDong(Request $request)
    {
        $validated = $request->validate([
            'khach_hang_id' => 'required|exists:khach_hang,id',
            'tho_chup_id' => 'nullable|exists:nhan_vien,id',
            'tho_make_id' => 'nullable|exists:nhan_vien,id',
            'tho_edit_id' => 'nullable|exists:nhan_vien,id',
            'dia_diem' => 'nullable|string|max:255',
            'ngay_chup' => 'nullable|date',
            'trang_phuc' => 'nullable|string',
            'concept' => 'nullable|string',
            'ghi_chu_chup' => 'nullable|string',
            'trang_thai_chup' => 'nullable|string|max:50',
            'tong_tien' => 'nullable|numeric|min:0',
            'thanh_toan_lan_1' => 'nullable|numeric|min:0',
            'thanh_toan_lan_2' => 'nullable|numeric|min:0',
            'thanh_toan_lan_3' => 'nullable|numeric|min:0',
            'trang_thai_hop_dong' => 'nullable|string|max:50',
            'trang_thai_edit' => 'nullable|string|max:50',
            'link_file_demo' => 'nullable|string|max:500',
            'link_file_in' => 'nullable|string|max:500',
            'ngay_tra_link_in' => 'nullable|date',
            'ngay_hen_tra_hang' => 'nullable|date',
            'dich_vu_le_hop_dong' => 'nullable|array',
            'dich_vu_le_hop_dong.*.dich_vu_le_id' => 'required|integer|exists:dich_vu_le,id',
            'dich_vu_le_hop_dong.*.gia_goc' => 'required|numeric|min:0',
            'dich_vu_le_hop_dong.*.gia_thuc' => 'required|numeric|min:0',
        ]);

        $validated['nguoi_tao_id'] = $request->user()?->id;
        $dichVuLeHopDong = $request->input('dich_vu_le_hop_dong', []);
        unset($validated['dich_vu_le_hop_dong']);

        $hopDong = HopDong::create($validated);

        if (! empty($dichVuLeHopDong)) {
            $sync = [];
            foreach ($dichVuLeHopDong as $item) {
                $id = (int) ($item['dich_vu_le_id'] ?? 0);
                if ($id > 0) {
                    $sync[$id] = [
                        'gia_goc' => (float) ($item['gia_goc'] ?? 0),
                        'gia_thuc' => (float) ($item['gia_thuc'] ?? 0),
                    ];
                }
            }
            $hopDong->dichVuLe()->sync($sync);
        }

        return redirect()->route('admin.khach-hang.hop-dong')->with('success', 'Đã thêm hợp đồng thành công.');
    }

    public function updateHopDong(Request $request, HopDong $hopDong)
    {
        $validated = $request->validate([
            'khach_hang_id' => 'required|exists:khach_hang,id',
            'tho_chup_id' => 'nullable|exists:nhan_vien,id',
            'tho_make_id' => 'nullable|exists:nhan_vien,id',
            'tho_edit_id' => 'nullable|exists:nhan_vien,id',
            'dia_diem' => 'nullable|string|max:255',
            'ngay_chup' => 'nullable|date',
            'trang_phuc' => 'nullable|string',
            'concept' => 'nullable|string',
            'ghi_chu_chup' => 'nullable|string',
            'trang_thai_chup' => 'nullable|string|max:50',
            'tong_tien' => 'nullable|numeric|min:0',
            'thanh_toan_lan_1' => 'nullable|numeric|min:0',
            'thanh_toan_lan_2' => 'nullable|numeric|min:0',
            'thanh_toan_lan_3' => 'nullable|numeric|min:0',
            'trang_thai_hop_dong' => 'nullable|string|max:50',
            'trang_thai_edit' => 'nullable|string|max:50',
            'link_file_demo' => 'nullable|string|max:500',
            'link_file_in' => 'nullable|string|max:500',
            'ngay_tra_link_in' => 'nullable|date',
            'ngay_hen_tra_hang' => 'nullable|date',
        ]);

        $hopDong->update($validated);

        return redirect()->route('admin.khach-hang.hop-dong')->with('success', 'Đã cập nhật hợp đồng thành công.');
    }

    public function destroyHopDong(HopDong $hopDong)
    {
        $hopDong->delete();
        return redirect()->route('admin.khach-hang.hop-dong')->with('success', 'Đã xóa hợp đồng.');
    }
}
