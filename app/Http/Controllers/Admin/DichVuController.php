<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DichVuLe;
use App\Models\NhomDichVu;
use App\Models\PhongBan;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class DichVuController extends Controller
{
    public function index()
    {
        return redirect()->route('admin.dich-vu.dich-vu-le');
    }

    public function dichVuLe(Request $request)
    {
        $query = DichVuLe::query()->with(['nguoiTao', 'phongBan']);

        if ($request->filled('search')) {
            $q = $request->input('search');
            $query->where(function ($qb) use ($q) {
                $qb->where('ten_dich_vu', 'like', '%' . $q . '%')
                    ->orWhere('ma_dich_vu', 'like', '%' . $q . '%');
            });
        }

        $danhSach = $query->orderByDesc('id')->paginate(15)->withQueryString();
        $phongBans = PhongBan::orderBy('ten_phong_ban')->get();

        return view('admin.dich-vu.dich-vu-le', compact('danhSach', 'phongBans'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'ten_dich_vu' => 'required|string|max:255',
            'ma_dich_vu' => ['required', 'string', 'max:50', Rule::unique('dich_vu_le', 'ma_dich_vu')],
            'mo_ta' => 'nullable|string',
            'trang_thai' => 'nullable|integer|in:0,1',
            'ghi_chu' => 'nullable|string',
            'gia_dich_vu' => 'required|numeric|min:0',
            'phong_ban_id' => 'required|integer|exists:phong_ban,id',
        ], [
            'ten_dich_vu.required' => 'Vui lòng nhập tên dịch vụ.',
            'ten_dich_vu.string' => 'Tên dịch vụ phải là chuỗi ký tự.',
            'ten_dich_vu.max' => 'Tên dịch vụ không được quá 255 ký tự.',
            'ma_dich_vu.required' => 'Vui lòng nhập mã dịch vụ.',
            'ma_dich_vu.string' => 'Mã dịch vụ phải là chuỗi ký tự.',
            'ma_dich_vu.max' => 'Mã dịch vụ không được quá 50 ký tự.',
            'ma_dich_vu.unique' => 'Mã dịch vụ đã tồn tại, vui lòng chọn mã khác.',
            'mo_ta.string' => 'Mô tả phải là chuỗi ký tự.',
            'trang_thai.integer' => 'Trạng thái phải là số nguyên.',
            'trang_thai.in' => 'Trạng thái không hợp lệ.',
            'ghi_chu.string' => 'Ghi chú phải là chuỗi ký tự.',
            'gia_dich_vu.required' => 'Vui lòng nhập giá dịch vụ.',
            'gia_dich_vu.numeric' => 'Giá dịch vụ phải là số.',
            'gia_dich_vu.min' => 'Giá dịch vụ không được âm.',
            'phong_ban_id.required' => 'Vui lòng chọn phòng ban phụ trách.',
            'phong_ban_id.integer' => 'Phòng ban không hợp lệ.',
            'phong_ban_id.exists' => 'Phòng ban không tồn tại.',
        ]);

        DichVuLe::create([
            'ten_dich_vu' => $request->input('ten_dich_vu'),
            'ma_dich_vu' => $request->input('ma_dich_vu'),
            'mo_ta' => $request->input('mo_ta'),
            'trang_thai' => (int) $request->input('trang_thai', DichVuLe::TRANG_THAI_HIEN_THI),
            'ghi_chu' => $request->input('ghi_chu'),
            'gia_dich_vu' => $request->input('gia_dich_vu'),
            'phong_ban_id' => (int) $request->input('phong_ban_id'),
            'nguoi_tao_id' => $request->user()?->id,
        ]);

        return redirect()->route('admin.dich-vu.dich-vu-le')->with('success', 'Đã thêm dịch vụ lẻ thành công.');
    }

    public function update(Request $request, DichVuLe $dichVu)
    {
        $request->validate([
            'ten_dich_vu' => 'required|string|max:255',
            'ma_dich_vu' => ['required', 'string', 'max:50', Rule::unique('dich_vu_le', 'ma_dich_vu')->ignore($dichVu->id)],
            'mo_ta' => 'nullable|string',
            'trang_thai' => 'nullable|integer|in:0,1',
            'ghi_chu' => 'nullable|string',
            'gia_dich_vu' => 'required|numeric|min:0',
            'phong_ban_id' => 'required|integer|exists:phong_ban,id',
        ], [
            'ten_dich_vu.required' => 'Vui lòng nhập tên dịch vụ.',
            'ten_dich_vu.string' => 'Tên dịch vụ phải là chuỗi ký tự.',
            'ten_dich_vu.max' => 'Tên dịch vụ không được quá 255 ký tự.',
            'ma_dich_vu.required' => 'Vui lòng nhập mã dịch vụ.',
            'ma_dich_vu.string' => 'Mã dịch vụ phải là chuỗi ký tự.',
            'ma_dich_vu.max' => 'Mã dịch vụ không được quá 50 ký tự.',
            'ma_dich_vu.unique' => 'Mã dịch vụ đã tồn tại, vui lòng chọn mã khác.',
            'mo_ta.string' => 'Mô tả phải là chuỗi ký tự.',
            'trang_thai.integer' => 'Trạng thái phải là số nguyên.',
            'trang_thai.in' => 'Trạng thái không hợp lệ.',
            'ghi_chu.string' => 'Ghi chú phải là chuỗi ký tự.',
            'gia_dich_vu.required' => 'Vui lòng nhập giá dịch vụ.',
            'gia_dich_vu.numeric' => 'Giá dịch vụ phải là số.',
            'gia_dich_vu.min' => 'Giá dịch vụ không được âm.',
            'phong_ban_id.required' => 'Vui lòng chọn phòng ban phụ trách.',
            'phong_ban_id.integer' => 'Phòng ban không hợp lệ.',
            'phong_ban_id.exists' => 'Phòng ban không tồn tại.',
        ]);

        $dichVu->update([
            'ten_dich_vu' => $request->input('ten_dich_vu'),
            'ma_dich_vu' => $request->input('ma_dich_vu'),
            'mo_ta' => $request->input('mo_ta'),
            'trang_thai' => (int) $request->input('trang_thai', DichVuLe::TRANG_THAI_HIEN_THI),
            'ghi_chu' => $request->input('ghi_chu'),
            'gia_dich_vu' => $request->input('gia_dich_vu'),
            'phong_ban_id' => (int) $request->input('phong_ban_id'),
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
        ], [
            'ten_nhom.required' => 'Vui lòng nhập tên nhóm dịch vụ.',
            'ten_nhom.string' => 'Tên nhóm phải là chuỗi ký tự.',
            'ten_nhom.max' => 'Tên nhóm không được quá 255 ký tự.',
            'ma_nhom.string' => 'Mã nhóm phải là chuỗi ký tự.',
            'ma_nhom.max' => 'Mã nhóm không được quá 50 ký tự.',
            'gia_tien.numeric' => 'Giá tiền phải là số.',
            'gia_tien.min' => 'Giá tiền không được âm.',
            'the.string' => 'Thẻ phải là chuỗi ký tự.',
            'ghi_chu.string' => 'Ghi chú phải là chuỗi ký tự.',
            'mo_ta.string' => 'Mô tả phải là chuỗi ký tự.',
            'trang_thai.integer' => 'Trạng thái phải là số nguyên.',
            'trang_thai.in' => 'Trạng thái không hợp lệ.',
            'dich_vu_le_ids.array' => 'Danh sách dịch vụ lẻ không hợp lệ.',
            'dich_vu_le_ids.*.integer' => 'Mỗi dịch vụ lẻ phải là số nguyên.',
            'dich_vu_le_ids.*.exists' => 'Một hoặc nhiều dịch vụ lẻ không tồn tại trong hệ thống.',
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
        ], [
            'ten_nhom.required' => 'Vui lòng nhập tên nhóm dịch vụ.',
            'ten_nhom.string' => 'Tên nhóm phải là chuỗi ký tự.',
            'ten_nhom.max' => 'Tên nhóm không được quá 255 ký tự.',
            'ma_nhom.string' => 'Mã nhóm phải là chuỗi ký tự.',
            'ma_nhom.max' => 'Mã nhóm không được quá 50 ký tự.',
            'gia_tien.numeric' => 'Giá tiền phải là số.',
            'gia_tien.min' => 'Giá tiền không được âm.',
            'the.string' => 'Thẻ phải là chuỗi ký tự.',
            'ghi_chu.string' => 'Ghi chú phải là chuỗi ký tự.',
            'mo_ta.string' => 'Mô tả phải là chuỗi ký tự.',
            'trang_thai.integer' => 'Trạng thái phải là số nguyên.',
            'trang_thai.in' => 'Trạng thái không hợp lệ.',
            'dich_vu_le_ids.array' => 'Danh sách dịch vụ lẻ không hợp lệ.',
            'dich_vu_le_ids.*.integer' => 'Mỗi dịch vụ lẻ phải là số nguyên.',
            'dich_vu_le_ids.*.exists' => 'Một hoặc nhiều dịch vụ lẻ không tồn tại trong hệ thống.',
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
