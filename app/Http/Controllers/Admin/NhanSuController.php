<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NhanVien;
use App\Models\PhongBan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
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
            ->with(['nhanVien', 'nhanVien.phongBan'])
            ->when($search, function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            })
            ->orderBy('id')
            ->paginate(15)
            ->withQueryString();

        $phongBans = PhongBan::orderBy('ten_phong_ban')->get();

        return view('admin.nhan-su.danh-sach', compact('danhSach', 'phongBans'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => ['nullable', 'string', 'max:20', Rule::unique('users', 'phone')],
            'password' => 'required|string|min:8|confirmed',
            'password_confirmation' => 'required_with:password',
            'gioi_tinh' => 'nullable|string|in:nam,nu,khac',
            'ngay_sinh' => 'nullable|date',
            'cccd' => ['nullable', 'string', 'max:20', Rule::unique('nhan_vien', 'cccd')],
            'role' => 'nullable|integer|in:1,2,3',
            'vi_tri_lam_viec' => 'nullable|string|max:255',
            'ngay_vao_cong_ty' => 'nullable|date',
            'ngay_ky_hop_dong' => 'nullable|date',
            'luong_co_ban' => 'nullable|integer|min:0',
            'luong_tang_ca' => 'nullable|integer|min:0',
            'phong_ban_id' => 'required|exists:phong_ban,id',
            'hinh_anh' => 'nullable|image|max:2048',
        ], [
            'name.required' => 'Vui lòng nhập họ tên.',
            'name.string' => 'Họ tên phải là chuỗi ký tự.',
            'name.max' => 'Họ tên không được quá 255 ký tự.',
            'email.required' => 'Vui lòng nhập email.',
            'email.email' => 'Email không đúng định dạng.',
            'email.unique' => 'Email này đã được sử dụng.',
            'phone.string' => 'Số điện thoại phải là chuỗi ký tự.',
            'phone.max' => 'Số điện thoại không được quá 20 ký tự.',
            'phone.unique' => 'Số điện thoại này đã được sử dụng.',
            'password.required' => 'Vui lòng nhập mật khẩu.',
            'password.string' => 'Mật khẩu phải là chuỗi ký tự.',
            'password.min' => 'Mật khẩu tối thiểu 8 ký tự.',
            'password.confirmed' => 'Xác nhận mật khẩu không khớp.',
            'password_confirmation.required_with' => 'Vui lòng xác nhận lại mật khẩu.',
            'gioi_tinh.string' => 'Giới tính phải là chuỗi ký tự.',
            'gioi_tinh.in' => 'Giới tính không hợp lệ.',
            'ngay_sinh.date' => 'Ngày sinh không đúng định dạng.',
            'cccd.string' => 'Số CCCD phải là chuỗi ký tự.',
            'cccd.max' => 'Số CCCD không được quá 20 ký tự.',
            'cccd.unique' => 'Số CCCD này đã được sử dụng.',
            'role.integer' => 'Vai trò phải là số nguyên.',
            'role.in' => 'Vai trò không hợp lệ.',
            'vi_tri_lam_viec.string' => 'Vị trí làm việc phải là chuỗi ký tự.',
            'vi_tri_lam_viec.max' => 'Vị trí làm việc không được quá 255 ký tự.',
            'ngay_vao_cong_ty.date' => 'Ngày vào công ty không đúng định dạng.',
            'ngay_ky_hop_dong.date' => 'Ngày ký hợp đồng không đúng định dạng.',
            'luong_co_ban.integer' => 'Lương cơ bản phải là số nguyên.',
            'luong_co_ban.min' => 'Lương cơ bản không được âm.',
            'luong_tang_ca.integer' => 'Lương tăng ca phải là số nguyên.',
            'luong_tang_ca.min' => 'Lương tăng ca không được âm.',
            'phong_ban_id.required' => 'Vui lòng chọn phòng ban.',
            'phong_ban_id.exists' => 'Phòng ban không tồn tại.',
            'hinh_anh.image' => 'File tải lên phải là ảnh (jpeg, png, bmp, gif, webp).',
            'hinh_anh.max' => 'Kích thước ảnh không được quá 2MB.',
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
                'phong_ban_id' => $request->input('phong_ban_id'),
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
            'cccd' => ['nullable', 'string', 'max:20'],
            'role' => 'nullable|integer|in:1,2,3',
            'vi_tri_lam_viec' => 'nullable|string|max:255',
            'ngay_vao_cong_ty' => 'nullable|date',
            'ngay_ky_hop_dong' => 'nullable|date',
            'luong_co_ban' => 'nullable|integer|min:0',
            'luong_tang_ca' => 'nullable|integer|min:0',
            'phong_ban_id' => 'required|exists:phong_ban,id',
            'hinh_anh' => 'nullable|image|max:2048',
        ], [
            'name.required' => 'Vui lòng nhập họ tên.',
            'name.string' => 'Họ tên phải là chuỗi ký tự.',
            'name.max' => 'Họ tên không được quá 255 ký tự.',
            'gioi_tinh.string' => 'Giới tính phải là chuỗi ký tự.',
            'gioi_tinh.in' => 'Giới tính không hợp lệ.',
            'ngay_sinh.date' => 'Ngày sinh không đúng định dạng.',
            'cccd.string' => 'Số CCCD phải là chuỗi ký tự.',
            'cccd.max' => 'Số CCCD không được quá 20 ký tự.',
            'role.integer' => 'Vai trò phải là số nguyên.',
            'role.in' => 'Vai trò không hợp lệ.',
            'vi_tri_lam_viec.string' => 'Vị trí làm việc phải là chuỗi ký tự.',
            'vi_tri_lam_viec.max' => 'Vị trí làm việc không được quá 255 ký tự.',
            'ngay_vao_cong_ty.date' => 'Ngày vào công ty không đúng định dạng.',
            'ngay_ky_hop_dong.date' => 'Ngày ký hợp đồng không đúng định dạng.',
            'luong_co_ban.integer' => 'Lương cơ bản phải là số nguyên.',
            'luong_co_ban.min' => 'Lương cơ bản không được âm.',
            'luong_tang_ca.integer' => 'Lương tăng ca phải là số nguyên.',
            'luong_tang_ca.min' => 'Lương tăng ca không được âm.',
            'phong_ban_id.required' => 'Vui lòng chọn phòng ban.',
            'phong_ban_id.exists' => 'Phòng ban không tồn tại.',
            'hinh_anh.image' => 'File tải lên phải là ảnh (jpeg, png, bmp, gif, webp).',
            'hinh_anh.max' => 'Kích thước ảnh không được quá 2MB.',
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
                    'phong_ban_id' => $request->input('phong_ban_id'),
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
                    'phong_ban_id' => $request->input('phong_ban_id'),
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
            'password.string' => 'Mật khẩu phải là chuỗi ký tự.',
            'password.min' => 'Mật khẩu tối thiểu 8 ký tự.',
            'password.confirmed' => 'Xác nhận mật khẩu không khớp.',
            'password_confirmation.required_with' => 'Vui lòng xác nhận lại mật khẩu.',
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

    public function phanQuyen(Request $request)
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

        $routeDescriptions = config('route_descriptions', []);
        $adminGetRoutes = collect(Route::getRoutes())
            ->filter(function ($route) {
                $name = $route->getName();
                if (!$name || !str_starts_with($name, 'admin.')) {
                    return false;
                }
                return in_array('GET', $route->methods());
            })
            ->map(function ($route) use ($routeDescriptions) {
                $name = $route->getName();
                return [
                    'name' => $name,
                    'uri' => $route->uri(),
                    'description' => $routeDescriptions[$name] ?? '',
                ];
            })
            ->values()
            ->all();

        return view('admin.nhan-su.phan-quyen', compact('danhSach', 'adminGetRoutes'));
    }

    public function luuPhanQuyen(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'permissions' => 'nullable|array',
            'permissions.*' => 'string|max:255',
        ], [
            'user_id.required' => 'Thiếu thông tin nhân sự.',
            'user_id.exists' => 'Nhân sự không tồn tại.',
            'permissions.array' => 'Danh sách quyền không hợp lệ.',
            'permissions.*.string' => 'Mỗi quyền phải là chuỗi ký tự.',
            'permissions.*.max' => 'Mỗi quyền không được quá 255 ký tự.',
        ]);

        $user = User::findOrFail($request->user_id);
        $dsMenu = $request->input('permissions', []);

        $nhanVien = $user->nhanVien;
        if (!$nhanVien) {
            NhanVien::create([
                'user_id' => $user->id,
                'ds_menu' => $dsMenu,
            ]);
        } else {
            $nhanVien->update(['ds_menu' => $dsMenu]);
        }

        return redirect()->back()->with('success', 'Đã lưu phân quyền thành công.');
    }

    public function lichLamViec()
    {
        return view('admin.nhan-su.lich-lam-viec');
    }
}
