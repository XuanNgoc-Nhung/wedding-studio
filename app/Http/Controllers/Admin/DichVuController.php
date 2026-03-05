<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DichVuLe;
use Illuminate\Http\Request;

class DichVuController extends Controller
{
    public function index()
    {
        return redirect()->route('admin.dich-vu.dich-vu-le');
    }

    public function dichVuLe(Request $request)
    {
        $query = DichVuLe::query()->with('nguoiTao');

        if ($request->filled('search')) {
            $q = $request->input('search');
            $query->where(function ($qb) use ($q) {
                $qb->where('ten_dich_vu', 'like', '%' . $q . '%')
                    ->orWhere('ma_dich_vu', 'like', '%' . $q . '%');
            });
        }

        $danhSach = $query->orderByDesc('id')->paginate(15)->withQueryString();

        return view('admin.dich-vu.dich-vu-le', compact('danhSach'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'ten_dich_vu' => 'required|string|max:255',
            'ma_dich_vu' => 'nullable|string|max:50',
            'mo_ta' => 'nullable|string',
            'trang_thai' => 'nullable|integer|in:0,1',
            'ghi_chu' => 'nullable|string',
            'gia_dich_vu' => 'nullable|numeric|min:0',
        ]);

        DichVuLe::create([
            'ten_dich_vu' => $request->input('ten_dich_vu'),
            'ma_dich_vu' => $request->input('ma_dich_vu'),
            'mo_ta' => $request->input('mo_ta'),
            'trang_thai' => (int) $request->input('trang_thai', DichVuLe::TRANG_THAI_HIEN_THI),
            'ghi_chu' => $request->input('ghi_chu'),
            'gia_dich_vu' => $request->input('gia_dich_vu'),
            'nguoi_tao_id' => $request->user()?->id,
        ]);

        return redirect()->route('admin.dich-vu.dich-vu-le')->with('success', 'Đã thêm dịch vụ lẻ thành công.');
    }

    public function update(Request $request, DichVuLe $dichVu)
    {
        $request->validate([
            'ten_dich_vu' => 'required|string|max:255',
            'ma_dich_vu' => 'nullable|string|max:50',
            'mo_ta' => 'nullable|string',
            'trang_thai' => 'nullable|integer|in:0,1',
            'ghi_chu' => 'nullable|string',
            'gia_dich_vu' => 'nullable|numeric|min:0',
        ]);

        $dichVu->update([
            'ten_dich_vu' => $request->input('ten_dich_vu'),
            'ma_dich_vu' => $request->input('ma_dich_vu'),
            'mo_ta' => $request->input('mo_ta'),
            'trang_thai' => (int) $request->input('trang_thai', DichVuLe::TRANG_THAI_HIEN_THI),
            'ghi_chu' => $request->input('ghi_chu'),
            'gia_dich_vu' => $request->input('gia_dich_vu'),
        ]);

        return redirect()->route('admin.dich-vu.dich-vu-le')->with('success', 'Đã cập nhật dịch vụ lẻ thành công.');
    }

    public function destroy(DichVuLe $dichVu)
    {
        $dichVu->delete();
        return redirect()->route('admin.dich-vu.dich-vu-le')->with('success', 'Đã xóa dịch vụ lẻ.');
    }
}
