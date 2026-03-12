<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NhanVien;
use App\Models\PhongBan;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class HeThongController extends Controller
{
    public function phongBan(Request $request)
    {
        $query = PhongBan::query();

        if ($request->filled('search')) {
            $q = $request->input('search');
            $query->where(function ($qb) use ($q) {
                $qb->where('ten_phong_ban', 'like', '%' . $q . '%')
                    ->orWhere('ma_phong_ban', 'like', '%' . $q . '%');
            });
        }

        $danhSach = $query->withCount('nhanViens')->orderByDesc('id')->paginate(15)->withQueryString();

        return view('admin.he-thong.phong-ban', compact('danhSach'));
    }

    public function storePhongBan(Request $request)
    {
        $request->validate([
            'ten_phong_ban' => 'required|string|max:255',
            'ma_phong_ban' => ['required', 'string', 'max:50', Rule::unique('phong_ban', 'ma_phong_ban')],
            'mo_ta' => 'nullable|string',
            'ghi_chu' => 'nullable|string',
        ], [
            'ten_phong_ban.required' => 'Vui lòng nhập tên phòng ban.',
            'ten_phong_ban.string' => 'Tên phòng ban phải là chuỗi ký tự.',
            'ten_phong_ban.max' => 'Tên phòng ban không được quá 255 ký tự.',
            'ma_phong_ban.required' => 'Vui lòng nhập mã phòng ban.',
            'ma_phong_ban.string' => 'Mã phòng ban phải là chuỗi ký tự.',
            'ma_phong_ban.max' => 'Mã phòng ban không được quá 50 ký tự.',
            'ma_phong_ban.unique' => 'Mã phòng ban đã tồn tại, vui lòng chọn mã khác.',
            'mo_ta.string' => 'Mô tả phải là chuỗi ký tự.',
            'ghi_chu.string' => 'Ghi chú phải là chuỗi ký tự.',
        ]);

        PhongBan::create([
            'ten_phong_ban' => $request->input('ten_phong_ban'),
            'ma_phong_ban' => $request->input('ma_phong_ban'),
            'mo_ta' => $request->input('mo_ta'),
            'ghi_chu' => $request->input('ghi_chu'),
        ]);

        return redirect()->route('admin.he-thong.phong-ban')->with('success', 'Đã thêm phòng ban thành công.');
    }

    public function updatePhongBan(Request $request, PhongBan $phongBan)
    {
        $request->validate([
            'ten_phong_ban' => 'required|string|max:255',
            'ma_phong_ban' => ['required', 'string', 'max:50', Rule::unique('phong_ban', 'ma_phong_ban')->ignore($phongBan->id)],
            'mo_ta' => 'nullable|string',
            'ghi_chu' => 'nullable|string',
        ], [
            'ten_phong_ban.required' => 'Vui lòng nhập tên phòng ban.',
            'ten_phong_ban.string' => 'Tên phòng ban phải là chuỗi ký tự.',
            'ten_phong_ban.max' => 'Tên phòng ban không được quá 255 ký tự.',
            'ma_phong_ban.required' => 'Vui lòng nhập mã phòng ban.',
            'ma_phong_ban.string' => 'Mã phòng ban phải là chuỗi ký tự.',
            'ma_phong_ban.max' => 'Mã phòng ban không được quá 50 ký tự.',
            'ma_phong_ban.unique' => 'Mã phòng ban đã tồn tại, vui lòng chọn mã khác.',
            'mo_ta.string' => 'Mô tả phải là chuỗi ký tự.',
            'ghi_chu.string' => 'Ghi chú phải là chuỗi ký tự.',
        ]);

        $phongBan->update([
            'ten_phong_ban' => $request->input('ten_phong_ban'),
            'ma_phong_ban' => $request->input('ma_phong_ban'),
            'mo_ta' => $request->input('mo_ta'),
            'ghi_chu' => $request->input('ghi_chu'),
        ]);

        return redirect()->route('admin.he-thong.phong-ban')->with('success', 'Đã cập nhật phòng ban thành công.');
    }

    public function destroyPhongBan(PhongBan $phongBan)
    {
        $dangCoNhanSu = $phongBan->nhanViens()->exists();
        if ($dangCoNhanSu) {
            return redirect()
                ->route('admin.he-thong.phong-ban')
                ->with('error', 'Đang tồn tại nhân sự trực thuộc phòng ban này. Không thể xoá.');
        }

        $phongBan->delete();
        return redirect()->route('admin.he-thong.phong-ban')->with('success', 'Đã xóa phòng ban.');
    }
}
