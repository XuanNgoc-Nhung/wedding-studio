<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class AdminController extends Controller
{
    public function index()
    {
        return view('admin.index');
    }

    public function thongTinCaNhan()
    {
        $user = auth()->user()->load('nhanVien');
        $nhanVien = $user->nhanVien;
        return view('admin.thong-tin-ca-nhan', compact('user', 'nhanVien'));
    }

    public function capNhatThongTinCaNhan(Request $request)
    {
        $user = auth()->user();

        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'phone' => ['nullable', 'string', 'max:20', Rule::unique('users')->ignore($user->id)],
        ];

        $nhanVien = $user->nhanVien;
        if ($nhanVien) {
            $rules['gioi_tinh'] = ['nullable', 'string', 'in:nam,nu,khac'];
            $rules['ngay_sinh'] = ['nullable', 'date'];
            $rules['cccd'] = ['nullable', 'string', 'max:20'];
            $rules['hinh_anh'] = ['nullable', 'image', 'max:2048'];
        }

        $validated = $request->validate($rules);

        $user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
        ]);

        if ($nhanVien) {
            $data = [
                'gioi_tinh' => $validated['gioi_tinh'] ?? null,
                'ngay_sinh' => $validated['ngay_sinh'] ?? null,
                'cccd' => $validated['cccd'] ?? null,
            ];
            if ($request->hasFile('hinh_anh')) {
                if ($nhanVien->hinh_anh) {
                    Storage::disk('public')->delete($nhanVien->hinh_anh);
                }
                $data['hinh_anh'] = $request->file('hinh_anh')->store('nhan-vien', 'public');
            }
            $nhanVien->update($data);
        }

        return redirect()->route('admin.thong-tin-ca-nhan')->with('success', 'Đã cập nhật thông tin cá nhân.');
    }

    public function doiMatKhau(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'password_current' => [
                'required',
                function ($attribute, $value, $fail) use ($user) {
                    if (! Hash::check($value, $user->password)) {
                        $fail('Mật khẩu hiện tại không đúng.');
                    }
                },
            ],
            'password_new' => [
                'required',
                'confirmed',
                Password::min(8)
                    ->letters()
                    ->mixedCase()
                    ->numbers()
                    ->symbols(),
            ],
        ], [
            'password_new.required' => 'Vui lòng nhập mật khẩu mới.',
            'password_new.confirmed' => 'Xác nhận mật khẩu mới không khớp.',
            'password_new.min' => 'Mật khẩu mới phải có ít nhất 8 ký tự.',
        ]);

        $user->update([
            'password' => Hash::make($request->password_new),
        ]);

        if ($request->wantsJson()) {
            return response()->json(['message' => 'Đã đổi mật khẩu thành công.']);
        }

        return redirect()->route('admin.thong-tin-ca-nhan')->with('success_password', 'Đã đổi mật khẩu thành công.');
    }
}
