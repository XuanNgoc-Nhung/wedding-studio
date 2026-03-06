<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KhachHang;
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
    public function hopDong()
    {
        return view('admin.khach-hang.hop-dong');
    }
    public function storeHopDong(Request $request)
    {
        $validated = $request->validate([
            'khach_hang_id' => 'required|exists:khach_hang,id',
        ]);
    }
}
