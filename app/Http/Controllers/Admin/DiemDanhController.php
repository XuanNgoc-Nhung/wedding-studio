<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ChamCong;
use App\Models\DiemDanh;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DiemDanhController extends Controller
{
    public function diemDanh(Request $request)
    {
        $query = DiemDanh::query()->with('user');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%');
            });
        }

        if ($request->filled('tu_ngay')) {
            $query->whereDate('gio_vao', '>=', $request->tu_ngay);
        }
        if ($request->filled('den_ngay')) {
            $query->whereDate('gio_vao', '<=', $request->den_ngay);
        }

        $danhSach = $query->orderByDesc('gio_vao')->paginate(15)->withQueryString();

        // Trạng thái check-in/check-out của user đăng nhập trong ngày hôm nay
        $canCheckIn = false;
        $canCheckOut = false;
        if (Auth::check()) {
            $recordToday = DiemDanh::query()
                ->where('user_id', Auth::id())
                ->whereDate('gio_vao', today())
                ->first();
            if (!$recordToday) {
                $canCheckIn = true;
            } elseif ($recordToday->gio_ra === null) {
                $canCheckOut = true;
            }
        }

        return view('admin.diem-danh.diem-danh', compact('danhSach', 'canCheckIn', 'canCheckOut'));
    }

    public function chamCong()
    {
        return view('admin.diem-danh.cham-cong');
    }

    /**
     * Ghi nhận giờ vào (check-in) cho user đăng nhập.
     */
    public function checkIn(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('admin.diem-danh.diem-danh')->with('error', 'Vui lòng đăng nhập để điểm danh.');
        }

        $userId = Auth::id();

        $exists = DiemDanh::query()
            ->where('user_id', $userId)
            ->whereDate('gio_vao', today())
            ->exists();

        if ($exists) {
            return redirect()->route('admin.diem-danh.diem-danh')->with('error', 'Bạn đã điểm danh vào hôm nay rồi.');
        }

        DB::transaction(function () use ($userId) {
            $diemDanh = DiemDanh::create([
                'user_id' => $userId,
                'gio_vao' => now(),
                'gio_ra' => null,
            ]);

            ChamCong::query()->updateOrCreate(
                [
                    'user_id' => $userId,
                    'ngay_diem_danh' => today()->toDateString(),
                ],
                [
                    'diem_danh_id' => $diemDanh->id,
                ]
            );
        });

        return redirect()->route('admin.diem-danh.diem-danh')->with('success', 'Check-in thành công lúc ' . now()->format('H:i d/m/Y') . '.');
    }

    /**
     * Ghi nhận giờ ra (check-out) cho user đăng nhập.
     */
    public function checkOut(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('admin.diem-danh.diem-danh')->with('error', 'Vui lòng đăng nhập.');
        }

        $record = DiemDanh::query()
            ->where('user_id', Auth::id())
            ->whereDate('gio_vao', today())
            ->whereNull('gio_ra')
            ->first();

        if (!$record) {
            return redirect()->route('admin.diem-danh.diem-danh')->with('error', 'Chưa có bản ghi check-in hôm nay hoặc đã check-out rồi.');
        }

        $record->update(['gio_ra' => now()]);

        return redirect()->route('admin.diem-danh.diem-danh')->with('success', 'Check-out thành công lúc ' . now()->format('H:i d/m/Y') . '.');
    }
}
