<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HopDong;
use App\Models\Concept;
use App\Models\NhanVien;
use App\Models\PhongBan;
use App\Models\TrangPhuc;
use App\Models\User;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class NhanSuController extends Controller
{
    public function index()
    {
        return view('admin.nhan-su.index');
    }

    public function danhSach(Request $request)
    {
        // return view('admin.nhan-su.demo');
        $search = $request->get('search');

        $danhSach = User::query()
            ->with(['nhanVien', 'nhanVien.phongBans'])
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
            'phong_ban_ids' => 'required|array|min:1',
            'phong_ban_ids.*' => 'exists:phong_ban,id',
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
            'phong_ban_ids.required' => 'Vui lòng chọn ít nhất một phòng ban.',
            'phong_ban_ids.min' => 'Vui lòng chọn ít nhất một phòng ban.',
            'phong_ban_ids.*.exists' => 'Phòng ban không tồn tại.',
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

            $nhanVien = NhanVien::create([
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
            $nhanVien->phongBans()->sync($request->input('phong_ban_ids', []));

            DB::commit();

            return redirect()->route('admin.nhan-su.danh-sach')->with('success', 'Đã thêm nhân sự mới thành công.');
        } catch (\Throwable $e) {
            DB::rollBack();

            return back()->withInput()->with('error', 'Có lỗi xảy ra: '.$e->getMessage());
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
            'phong_ban_ids' => 'required|array|min:1',
            'phong_ban_ids.*' => 'exists:phong_ban,id',
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
            'phong_ban_ids.required' => 'Vui lòng chọn ít nhất một phòng ban.',
            'phong_ban_ids.min' => 'Vui lòng chọn ít nhất một phòng ban.',
            'phong_ban_ids.*.exists' => 'Phòng ban không tồn tại.',
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
                $nhanVien->phongBans()->sync($request->input('phong_ban_ids', []));
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
                $nhanVien = NhanVien::create([
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
                $nhanVien->phongBans()->sync($request->input('phong_ban_ids', []));
            }

            DB::commit();

            return redirect()->route('admin.nhan-su.danh-sach')->with('success', 'Đã cập nhật nhân sự thành công.');
        } catch (\Throwable $e) {
            DB::rollBack();

            return back()->withInput()->with('error', 'Có lỗi xảy ra: '.$e->getMessage());
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

            return back()->with('error', 'Có lỗi xảy ra: '.$e->getMessage());
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
                if (! $name || ! str_starts_with($name, 'admin.')) {
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
        if (! $nhanVien) {
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
        $user = auth()->user();
        $isAdmin = (int) ($user?->role) === User::ROLE_ADMIN;
        $nhanVienId = $user?->nhanVien?->id;

        $homNay = Carbon::now(config('app.timezone'));
        $batDauTuan = $homNay->copy()->startOfWeek(Carbon::MONDAY)->startOfDay();
        $ketThucTuan = $homNay->copy()->endOfWeek(Carbon::SUNDAY)->endOfDay();

        $dsNgayTrongTuan = collect(
            CarbonPeriod::create($batDauTuan->copy()->startOfDay(), '1 day', $ketThucTuan->copy()->startOfDay())
        );

        $hopDongTrongTuan = collect();
        if ($isAdmin || $nhanVienId) {
            $hopDongTrongTuan = HopDong::query()
                ->with(['khachHang', 'thoChup', 'thoMake', 'thoEdit'])
                ->whereBetween('ngay_chup', [$batDauTuan->toDateString(), $ketThucTuan->toDateString()])
                ->when(! $isAdmin, function ($q) use ($nhanVienId) {
                    $q->where(function ($qq) use ($nhanVienId) {
                        $qq->where('tho_chup_id', $nhanVienId)
                            ->orWhere('tho_make_id', $nhanVienId)
                            ->orWhere('tho_edit_id', $nhanVienId);
                    });
                })
                ->orderBy('ngay_chup')
                ->get();
        }

        return view('admin.nhan-su.lich-lam-viec', compact(
            'batDauTuan',
            'ketThucTuan',
            'dsNgayTrongTuan',
            'hopDongTrongTuan',
            'nhanVienId',
            'isAdmin'
        ));
    }

    public function lichLamViecData(Request $request)
    {
        $user = auth()->user();
        $isAdmin = (int) ($user?->role) === User::ROLE_ADMIN;
        $nhanVienId = $user?->nhanVien?->id;
        if (! $isAdmin && ! $nhanVienId) {
            return response()->json([]);
        }

        $start = $request->query('start');
        $end = $request->query('end');
        $view = (string) $request->query('view', 'dayGridMonth');
        if (! $start || ! $end) {
            return response()->json([]);
        }

        $tz = config('app.timezone');
        $startDt = Carbon::parse($start, $tz);
        $endDtExclusive = Carbon::parse($end, $tz); // FullCalendar end là mốc exclusive

        // View tuần/ngày: trả event theo từng hợp đồng (có giờ)
        if (in_array($view, ['timeGridDay', 'timeGridWeek'], true)) {
            $items = HopDong::query()
                ->with(['khachHang', 'thoChup.user', 'thoMake.user', 'thoEdit.user'])
                ->where('ngay_chup', '>=', $startDt)
                ->where('ngay_chup', '<', $endDtExclusive)
                ->when(! $isAdmin, function ($q) use ($nhanVienId) {
                    $q->where(function ($qq) use ($nhanVienId) {
                        $qq->where('tho_chup_id', $nhanVienId)
                            ->orWhere('tho_make_id', $nhanVienId)
                            ->orWhere('tho_edit_id', $nhanVienId);
                    });
                })
                ->orderBy('ngay_chup')
                ->get();

            $events = $items->map(function (HopDong $hd) use ($nhanVienId, $tz) {
                $start = $hd->ngay_chup ? Carbon::parse($hd->ngay_chup, $tz) : null;
                if (! $start) {
                    return null;
                }

                $roles = [];
                if (! $nhanVienId) {
                    if ($hd->tho_chup_id) {
                        $roles[] = 'Chụp';
                    }
                    if ($hd->tho_make_id) {
                        $roles[] = 'Make';
                    }
                    if ($hd->tho_edit_id) {
                        $roles[] = 'Edit';
                    }
                } else {
                    if ((int) $hd->tho_chup_id === (int) $nhanVienId) {
                        $roles[] = 'Chụp';
                    }
                    if ((int) $hd->tho_make_id === (int) $nhanVienId) {
                        $roles[] = 'Make';
                    }
                    if ((int) $hd->tho_edit_id === (int) $nhanVienId) {
                        $roles[] = 'Edit';
                    }
                }
                $roleText = implode(', ', $roles);

                $badges = [];
                $chupTen = trim((string) ($hd->thoChup?->user?->name ?? ''));
                if ($chupTen !== '') {
                    $badges[] = ['code' => 'C', 'name' => $chupTen];
                }
                $makeTen = trim((string) ($hd->thoMake?->user?->name ?? ''));
                if ($makeTen !== '') {
                    $badges[] = ['code' => 'M', 'name' => $makeTen];
                }
                $editTen = trim((string) ($hd->thoEdit?->user?->name ?? ''));
                if ($editTen !== '') {
                    $badges[] = ['code' => 'E', 'name' => $editTen];
                }

                $kh = $hd->khachHang;
                $ten = $kh->ten_khach_hang ?? $kh->ten ?? $kh->ho_ten ?? $kh->name ?? ('HĐ #'.$hd->id);

                return [
                    'id' => 'hd-'.$hd->id,
                    'title' => $ten,
                    'start' => $start->toIso8601String(),
                    'end' => $start->copy()->addMinutes(60)->toIso8601String(),
                    'allDay' => false,
                    'extendedProps' => [
                        'hop_dong_id' => $hd->id,
                        'role' => $roleText,
                        'badges' => $badges,
                    ],
                ];
            })->filter()->values();

            return response()->json($events);
        }

        // View tháng/danh sách: theo ngày, mỗi dòng C/M/E + tên nhân sự
        $startDate = $startDt->toDateString();
        $endDate = $endDtExclusive->copy()->subDay()->toDateString();

        $hopDongs = HopDong::query()
            ->with(['thoChup.user', 'thoMake.user', 'thoEdit.user'])
            ->whereBetween(DB::raw('DATE(ngay_chup)'), [$startDate, $endDate])
            ->when(! $isAdmin, function ($q) use ($nhanVienId) {
                $q->where(function ($qq) use ($nhanVienId) {
                    $qq->where('tho_chup_id', $nhanVienId)
                        ->orWhere('tho_make_id', $nhanVienId)
                        ->orWhere('tho_edit_id', $nhanVienId);
                });
            })
            ->orderBy('ngay_chup')
            ->orderBy('id')
            ->get();

        $grouped = $hopDongs->groupBy(function (HopDong $hd) use ($tz) {
            if (! $hd->ngay_chup) {
                return null;
            }

            return Carbon::parse($hd->ngay_chup, $tz)->toDateString();
        })->filter(fn ($_, $key) => $key !== null && $key !== '');

        $events = $grouped->map(function ($group, $ngay) {
            $badges = [];
            foreach ($group as $hd) {
                $chupTen = trim((string) ($hd->thoChup?->user?->name ?? ''));
                if ($chupTen !== '') {
                    $badges[] = ['code' => 'C', 'name' => $chupTen];
                }
                $makeTen = trim((string) ($hd->thoMake?->user?->name ?? ''));
                if ($makeTen !== '') {
                    $badges[] = ['code' => 'M', 'name' => $makeTen];
                }
                $editTen = trim((string) ($hd->thoEdit?->user?->name ?? ''));
                if ($editTen !== '') {
                    $badges[] = ['code' => 'E', 'name' => $editTen];
                }
            }
            if ($badges === []) {
                return null;
            }

            return [
                'id' => 'work-'.$ngay,
                'start' => $ngay,
                'allDay' => true,
                'display' => 'block',
                'title' => '',
                'extendedProps' => [
                    'badges' => $badges,
                ],
            ];
        })->filter()->values();

        return response()->json($events);
    }

    public function lichLamViecChiTietNgay(Request $request)
    {
        $user = auth()->user();
        $isAdmin = (int) ($user?->role) === User::ROLE_ADMIN;
        $nhanVienId = $user?->nhanVien?->id;
        if (! $isAdmin && ! $nhanVienId) {
            return response()->json(['date' => null, 'items' => []]);
        }

        $date = $request->query('date');
        if (! $date) {
            return response()->json(['date' => null, 'items' => []]);
        }

        $tz = config('app.timezone');
        try {
            $day = Carbon::parse($date, $tz)->toDateString();
        } catch (\Throwable $e) {
            return response()->json(['date' => null, 'items' => []]);
        }

        $items = HopDong::query()
            ->with(['khachHang', 'thoChup.user', 'thoMake.user', 'thoEdit.user'])
            ->whereDate('ngay_chup', $day)
            ->when(! $isAdmin, function ($q) use ($nhanVienId) {
                $q->where(function ($qq) use ($nhanVienId) {
                    $qq->where('tho_chup_id', $nhanVienId)
                        ->orWhere('tho_make_id', $nhanVienId)
                        ->orWhere('tho_edit_id', $nhanVienId);
                });
            })
            ->orderBy('ngay_chup')
            ->get()
            ->map(function (HopDong $hd) use ($nhanVienId, $tz, $isAdmin) {
                $dt = $hd->ngay_chup ? Carbon::parse($hd->ngay_chup, $tz) : null;

                $roles = [];
                if ($isAdmin || ! $nhanVienId) {
                    if ($hd->tho_chup_id) {
                        $roles[] = 'Chụp';
                    }
                    if ($hd->tho_make_id) {
                        $roles[] = 'Make';
                    }
                    if ($hd->tho_edit_id) {
                        $roles[] = 'Edit';
                    }
                } else {
                    if ((int) $hd->tho_chup_id === (int) $nhanVienId) {
                        $roles[] = 'Chụp';
                    }
                    if ((int) $hd->tho_make_id === (int) $nhanVienId) {
                        $roles[] = 'Make';
                    }
                    if ((int) $hd->tho_edit_id === (int) $nhanVienId) {
                        $roles[] = 'Edit';
                    }
                }

                $kh = $hd->khachHang;
                $ten = $kh->ten_khach_hang ?? $kh->ten ?? $kh->ho_ten ?? $kh->name ?? null;

                return [
                    'id' => $hd->id,
                    'time' => $dt ? $dt->format('H:i') : null,
                    'datetime' => $dt ? $dt->toIso8601String() : null,
                    'ma_hop_dong' => $hd->ma_hop_dong ? (string) $hd->ma_hop_dong : null,
                    'khach_hang' => $ten,
                    'dia_diem' => $hd->dia_diem,
                    'concept' => $hd->concept,
                    'trang_thai_chup' => $hd->trang_thai_chup,
                    'trang_thai_edit' => $hd->trang_thai_edit,
                    'roles' => $roles,
                    'phan_cong' => [
                        'chup' => $hd->thoChup?->user?->name,
                        'make' => $hd->thoMake?->user?->name,
                        'edit' => $hd->thoEdit?->user?->name,
                    ],
                ];
            })
            ->values();

        return response()->json([
            'date' => $day,
            'items' => $items,
        ]);
    }

    public function congViecCuaToi(Request $request)
    {
        $user = auth()->user();
        $isAdmin = (int) ($user?->role) === User::ROLE_ADMIN;
        $nhanVienId = $user?->nhanVien?->id;

        $search = $request->get('search');

        $danhSach = HopDong::query()
            ->with(['khachHang', 'nguoiTao', 'thoChup.user', 'thoMake.user', 'thoEdit.user', 'nguoiChupUser'])
            ->when($search, function ($q) use ($search) {
                $q->whereHas('khachHang', function ($q2) use ($search) {
                    $q2->where('ho_ten_chu_re', 'like', "%{$search}%")
                        ->orWhere('ho_ten_co_dau', 'like', "%{$search}%")
                        ->orWhere('email_hoac_sdt_chu_re', 'like', "%{$search}%")
                        ->orWhere('email_hoac_sdt_co_dau', 'like', "%{$search}%");
                });
            })
            ->when(! $isAdmin, function ($q) use ($nhanVienId) {
                if (! $nhanVienId) {
                    // Tài khoản không có nhanVien liên kết => không có dữ liệu.
                    $q->whereRaw('1=0');

                    return;
                }

                $q->where(function ($qq) use ($nhanVienId) {
                    $qq->where('tho_chup_id', $nhanVienId)
                        ->orWhere('tho_make_id', $nhanVienId)
                        ->orWhere('tho_edit_id', $nhanVienId);
                });
            })
            ->orderByDesc('id')
            ->paginate(15)
            ->withQueryString();

        $danhSachTrangPhuc = TrangPhuc::query()
            ->where('trang_thai', TrangPhuc::TRANG_THAI_ACTIVE)
            ->orderBy('ten_san_pham')
            ->get();

        $conceptMap = Concept::query()
            ->where('trang_thai', Concept::TRANG_THAI_ACTIVE)
            ->orderBy('ten_concept')
            ->get()
            ->keyBy('id');

        return view('admin.nhan-su.cong-viec-cua-toi', compact(
            'danhSach',
            'danhSachTrangPhuc',
            'conceptMap',
            'nhanVienId',
            'isAdmin'
        ));
    }

    public function capNhatLinkFileCongViec(Request $request, HopDong $hopDong)
    {
        $user = $request->user();
        $isAdmin = (int) ($user?->role) === User::ROLE_ADMIN;
        $nhanVienId = $user?->nhanVien?->id;

        $validated = $request->validate([
            'type' => ['required', 'string', Rule::in(['demo', 'in'])],
            'link' => ['nullable', 'string', 'max:500'],
        ], [
            'type.required' => 'Thiếu loại link cần cập nhật.',
            'type.in' => 'Loại link không hợp lệ.',
            'link.max' => 'Link không được quá 500 ký tự.',
        ]);

        $type = $validated['type'];
        $link = $validated['link'] ?? null;

        if (! $isAdmin) {
            if (! $nhanVienId) {
                abort(403, 'Bạn không có quyền cập nhật link.');
            }

            if ($type === 'demo' && (int) ($hopDong->tho_chup_id ?? 0) !== (int) $nhanVienId) {
                abort(403, 'Chỉ thợ chụp mới được cập nhật link file chụp.');
            }
            if ($type === 'in' && (int) ($hopDong->tho_edit_id ?? 0) !== (int) $nhanVienId) {
                abort(403, 'Chỉ thợ edit mới được cập nhật link file edit.');
            }
        }

        if ($type === 'demo') {
            $hopDong->update(['link_file_demo' => $link]);

            return back()->with('success', 'Đã cập nhật link file chụp.');
        }

        $hopDong->update(['link_file_in' => $link]);

        return back()->with('success', 'Đã cập nhật link file edit.');
    }
}
