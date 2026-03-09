<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NhanVien;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class NhanSuController extends Controller
{
    public function index()
    {
        return view('admin.nhan-su.index');
    }

    public function danhSach(Request $request)
    {
        $search = $request->get('search');

        $danhSach = User::query()
            ->with('nhanVien')
            ->when($search, function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            })
            ->orderBy('id')
            ->paginate(15)
            ->withQueryString();

        return view('admin.nhan-su.danh-sach', compact('danhSach'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'nullable|string|max:20',
            'password' => 'required|string|min:8|confirmed',
            'password_confirmation' => 'required_with:password',
            'gioi_tinh' => 'nullable|string|in:nam,nu,khac',
            'ngay_sinh' => 'nullable|date',
            'cccd' => 'nullable|string|max:20',
            'role' => 'nullable|integer|in:1,2,3',
            'vi_tri_lam_viec' => 'nullable|string|max:255',
            'ngay_vao_cong_ty' => 'nullable|date',
            'ngay_ky_hop_dong' => 'nullable|date',
            'luong_co_ban' => 'nullable|integer|min:0',
            'luong_tang_ca' => 'nullable|integer|min:0',
            'hinh_anh' => 'nullable|image|max:2048',
        ], [
            'name.required' => 'Vui lòng nhập họ tên.',
            'email.required' => 'Vui lòng nhập email.',
            'email.unique' => 'Email này đã được sử dụng.',
            'password.required' => 'Vui lòng nhập mật khẩu.',
            'password.min' => 'Mật khẩu tối thiểu 8 ký tự.',
            'password.confirmed' => 'Xác nhận mật khẩu không khớp.',
        ]);

        $validated['password'] = Hash::make($request->password);
        $validated['role'] = $request->input('role', User::ROLE_NHAN_VIEN);

        DB::beginTransaction();
        try {
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'phone' => $request->input('phone'),
                'password' => $validated['password'],
                'role' => $validated['role'],
            ]);

            $hinhAnhPath = null;
            if ($request->hasFile('hinh_anh')) {
                $hinhAnhPath = $request->file('hinh_anh')->store('nhan-vien', 'public');
            }

            NhanVien::create([
                'user_id' => $user->id,
                'hinh_anh' => $hinhAnhPath,
                'gioi_tinh' => $request->input('gioi_tinh'),
                'ngay_sinh' => $request->input('ngay_sinh'),
                'cccd' => $request->input('cccd'),
                'vi_tri_lam_viec' => $request->input('vi_tri_lam_viec'),
                'ngay_vao_cong_ty' => $request->input('ngay_vao_cong_ty'),
                'ngay_ky_hop_dong' => $request->input('ngay_ky_hop_dong'),
                'luong_co_ban' => $request->input('luong_co_ban', 50000),
                'luong_tang_ca' => $request->input('luong_tang_ca', 80000),
            ]);

            DB::commit();
            return redirect()->route('admin.nhan-su.danh-sach')->with('success', 'Đã thêm nhân sự mới thành công.');
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'gioi_tinh' => 'nullable|string|in:nam,nu,khac',
            'ngay_sinh' => 'nullable|date',
            'cccd' => 'nullable|string|max:20',
            'role' => 'nullable|integer|in:1,2,3',
            'vi_tri_lam_viec' => 'nullable|string|max:255',
            'ngay_vao_cong_ty' => 'nullable|date',
            'ngay_ky_hop_dong' => 'nullable|date',
            'luong_co_ban' => 'nullable|integer|min:0',
            'luong_tang_ca' => 'nullable|integer|min:0',
            'hinh_anh' => 'nullable|image|max:2048',
        ], [
            'name.required' => 'Vui lòng nhập họ tên.',
        ]);

        DB::beginTransaction();
        try {
            $user->update([
                'name' => $validated['name'],
                'role' => $request->input('role', $user->role),
            ]);

            $nhanVien = $user->nhanVien;
            $hinhAnhPath = $nhanVien?->hinh_anh;

            if ($request->hasFile('hinh_anh')) {
                if ($hinhAnhPath) {
                    Storage::disk('public')->delete($hinhAnhPath);
                }
                $hinhAnhPath = $request->file('hinh_anh')->store('nhan-vien', 'public');
            }

            if ($nhanVien) {
                $nhanVien->update([
                    'hinh_anh' => $hinhAnhPath,
                    'gioi_tinh' => $request->input('gioi_tinh'),
                    'ngay_sinh' => $request->input('ngay_sinh'),
                    'cccd' => $request->input('cccd'),
                    'vi_tri_lam_viec' => $request->input('vi_tri_lam_viec'),
                    'ngay_vao_cong_ty' => $request->input('ngay_vao_cong_ty'),
                    'ngay_ky_hop_dong' => $request->input('ngay_ky_hop_dong'),
                    'luong_co_ban' => $request->filled('luong_co_ban') ? (int) $request->input('luong_co_ban') : $nhanVien->luong_co_ban,
                    'luong_tang_ca' => $request->filled('luong_tang_ca') ? (int) $request->input('luong_tang_ca') : $nhanVien->luong_tang_ca,
                ]);
            } else {
                NhanVien::create([
                    'user_id' => $user->id,
                    'hinh_anh' => $hinhAnhPath,
                    'gioi_tinh' => $request->input('gioi_tinh'),
                    'ngay_sinh' => $request->input('ngay_sinh'),
                    'cccd' => $request->input('cccd'),
                    'vi_tri_lam_viec' => $request->input('vi_tri_lam_viec'),
                    'ngay_vao_cong_ty' => $request->input('ngay_vao_cong_ty'),
                    'ngay_ky_hop_dong' => $request->input('ngay_ky_hop_dong'),
                    'luong_co_ban' => $request->input('luong_co_ban', 50000),
                    'luong_tang_ca' => $request->input('luong_tang_ca', 80000),
                ]);
            }

            DB::commit();
            return redirect()->route('admin.nhan-su.danh-sach')->with('success', 'Đã cập nhật nhân sự thành công.');
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    public function doiMatKhau(Request $request, User $user)
    {
        $validated = $request->validate([
            'password' => 'required|string|min:8|confirmed',
            'password_confirmation' => 'required_with:password',
        ], [
            'password.required' => 'Vui lòng nhập mật khẩu mới.',
            'password.min' => 'Mật khẩu tối thiểu 8 ký tự.',
            'password.confirmed' => 'Xác nhận mật khẩu không khớp.',
        ]);

        $user->update(['password' => Hash::make($request->password)]);
        return redirect()->route('admin.nhan-su.danh-sach')->with('success', 'Đã đổi mật khẩu thành công.');
    }

    public function destroy(User $user)
    {
        DB::beginTransaction();
        try {
            $nhanVien = $user->nhanVien;
            if ($nhanVien?->hinh_anh) {
                Storage::disk('public')->delete($nhanVien->hinh_anh);
            }
            if ($nhanVien) {
                $nhanVien->delete();
            }
            $user->delete();
            DB::commit();
            return redirect()->route('admin.nhan-su.danh-sach')->with('success', 'Đã xóa nhân sự thành công.');
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    public function phanQuyen()
    {
        return view('admin.nhan-su.phan-quyen');
    }
    public function lichLamViec()
    {
        return view('admin.nhan-su.lich-lam-viec');
    }
}
