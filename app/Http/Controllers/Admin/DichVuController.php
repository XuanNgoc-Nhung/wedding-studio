<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DichVuLe;
use App\Models\NhomDichVu;
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

    public function nhomDichVu(Request $request)
    {
        $query = NhomDichVu::query()->with(['dichVuLe', 'nguoiTao']);

        if ($request->filled('search')) {
            $q = $request->input('search');
            $query->where(function ($qb) use ($q) {
                $qb->where('ten_nhom', 'like', '%' . $q . '%')
                    ->orWhere('ma_nhom', 'like', '%' . $q . '%')
                    ->orWhere('the', 'like', '%' . $q . '%');
            });
        }

        $danhSach = $query->orderByDesc('id')->paginate(15)->withQueryString();

        $tatCaDichVuLe = DichVuLe::query()->orderBy('ten_dich_vu')->get();

        return view('admin.dich-vu.nhom-dich-vu', compact('danhSach', 'tatCaDichVuLe'));
    }

    public function storeNhomDichVu(Request $request)
    {
        $request->validate([
            'ten_nhom' => 'required|string|max:255',
            'ma_nhom' => 'nullable|string|max:50',
            'gia_tien' => 'nullable|numeric|min:0',
            'the' => 'nullable|string',
            'ghi_chu' => 'nullable|string',
            'mo_ta' => 'nullable|string',
            'trang_thai' => 'nullable|integer|in:0,1',
            'dich_vu_le_ids' => 'nullable|array',
            'dich_vu_le_ids.*' => 'integer|exists:dich_vu_le,id',
        ]);

        $ids = array_map('intval', (array) $request->input('dich_vu_le_ids', []));
        $giaGoc = empty($ids)
            ? 0
            : (float) DichVuLe::whereIn('id', $ids)->sum('gia_dich_vu');

        $nhomDichVu = NhomDichVu::create([
            'ten_nhom' => $request->input('ten_nhom'),
            'ma_nhom' => $request->input('ma_nhom'),
            'gia_tien' => $request->input('gia_tien'),
            'gia_goc' => $giaGoc,
            'the' => $request->input('the'),
            'ghi_chu' => $request->input('ghi_chu'),
            'mo_ta' => $request->input('mo_ta'),
            'trang_thai' => (int) $request->input('trang_thai', NhomDichVu::TRANG_THAI_HIEN_THI),
            'nguoi_tao_id' => $request->user()?->id,
        ]);

        if (! empty($ids)) {
            $nhomDichVu->dichVuLe()->attach(collect($ids)->mapWithKeys(fn ($id) => [$id => ['so_luong' => 1]])->all());
        }

        return redirect()->route('admin.dich-vu.nhom-dich-vu')->with('success', 'Đã thêm nhóm dịch vụ thành công.');
    }

    public function updateNhomDichVu(Request $request, NhomDichVu $nhomDichVu)
    {
        $request->validate([
            'ten_nhom' => 'required|string|max:255',
            'ma_nhom' => 'nullable|string|max:50',
            'gia_tien' => 'nullable|numeric|min:0',
            'the' => 'nullable|string',
            'ghi_chu' => 'nullable|string',
            'mo_ta' => 'nullable|string',
            'trang_thai' => 'nullable|integer|in:0,1',
            'dich_vu_le_ids' => 'nullable|array',
            'dich_vu_le_ids.*' => 'integer|exists:dich_vu_le,id',
        ]);

        $ids = array_map('intval', (array) $request->input('dich_vu_le_ids', []));
        $giaGoc = empty($ids)
            ? 0
            : (float) DichVuLe::whereIn('id', $ids)->sum('gia_dich_vu');

        $nhomDichVu->update([
            'ten_nhom' => $request->input('ten_nhom'),
            'ma_nhom' => $request->input('ma_nhom'),
            'gia_tien' => $request->input('gia_tien'),
            'gia_goc' => $giaGoc,
            'the' => $request->input('the'),
            'ghi_chu' => $request->input('ghi_chu'),
            'mo_ta' => $request->input('mo_ta'),
            'trang_thai' => (int) $request->input('trang_thai', NhomDichVu::TRANG_THAI_HIEN_THI),
        ]);

        $nhomDichVu->dichVuLe()->sync(collect($ids)->mapWithKeys(fn ($id) => [$id => ['so_luong' => 1]])->all());

        return redirect()->route('admin.dich-vu.nhom-dich-vu')->with('success', 'Đã cập nhật nhóm dịch vụ thành công.');
    }

    public function destroyNhomDichVu(NhomDichVu $nhomDichVu)
    {
        $nhomDichVu->dichVuLe()->detach();
        $nhomDichVu->delete();
        return redirect()->route('admin.dich-vu.nhom-dich-vu')->with('success', 'Đã xóa nhóm dịch vụ.');
    }
}
