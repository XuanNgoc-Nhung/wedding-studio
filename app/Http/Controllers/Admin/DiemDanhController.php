<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ChamCong;
use App\Models\DiemDanh;
use App\Models\HopDong;
use App\Models\NhanVien;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Carbon;

class DiemDanhController extends Controller
{
    public function diemDanh(Request $request)
    {
        $query = DiemDanh::query()
            ->with('user')
            ->where('user_id', Auth::id());

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
            $userId = Auth::id();
            $hasAnyRecordToday = DiemDanh::query()
                ->where('user_id', $userId)
                ->whereDate('gio_vao', today())
                ->exists();
            $hasOpenRecordToday = DiemDanh::query()
                ->where('user_id', $userId)
                ->whereDate('gio_vao', today())
                ->whereNull('gio_ra')
                ->exists();
            $canCheckIn = !$hasAnyRecordToday;
            $canCheckOut = $hasOpenRecordToday;
        }

        return view('admin.diem-danh.diem-danh', compact('danhSach', 'canCheckIn', 'canCheckOut'));
    }

    public function chamCong(Request $request)
    {
        $month = (int) $request->query('month', now()->month);
        $year = (int) $request->query('year', now()->year);

        if ($month < 1 || $month > 12) {
            $month = now()->month;
        }
        if ($year < 2000 || $year > 2100) {
            $year = now()->year;
        }

        $start = Carbon::create($year, $month, 1)->startOfMonth();
        $end = (clone $start)->endOfMonth();

        $ngayTrongThang = [];
        for ($d = (clone $start); $d->lte($end); $d->addDay()) {
            $ngayTrongThang[] = (clone $d);
        }

        $startStr = $start->format('Y-m-d');
        $endStr = $end->format('Y-m-d');

        // User có chấm công trong tháng (bất kể role) để đảm bảo hiển thị đủ
        $userIdsCoChamCong = ChamCong::query()
            ->whereBetween('ngay_diem_danh', [$startStr, $endStr])
            ->distinct()
            ->pluck('user_id');

        $nhanVien = User::query()
            ->where(function ($q) use ($userIdsCoChamCong) {
                $q->where('role', User::ROLE_NHAN_VIEN)
                    ->orWhereIn('id', $userIdsCoChamCong);
            })
            ->orderBy('name')
            ->get();

        $chamCong = ChamCong::query()
            ->with(['user', 'diemDanh'])
            ->whereBetween('ngay_diem_danh', [$startStr, $endStr])
            ->whereIn('user_id', $nhanVien->pluck('id'))
            ->get();

        $bangChamCong = [];
        foreach ($chamCong as $record) {
            $date = $record->ngay_diem_danh;
            $dateKey = $date ? Carbon::parse($date)->format('Y-m-d') : null;
            if (!$dateKey) {
                continue;
            }
            $bangChamCong[$dateKey][$record->user_id] = $record;
        }

        return view('admin.diem-danh.cham-cong', [
            'month' => $month,
            'year' => $year,
            'start' => $start,
            'end' => $end,
            'ngayTrongThang' => $ngayTrongThang,
            'nhanVien' => $nhanVien,
            'bangChamCong' => $bangChamCong,
        ]);
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
     * Tính giờ làm cơ bản (từ giờ vào đến 21:00), giờ tăng ca (từ 21:00 đến giờ ra),
     * và lương cơ bản, lương tăng ca theo đơn giá ở bảng nhan_vien.
     */
    public function checkOut(Request $request)
    {
        Log::info('Check-out: yêu cầu từ user', ['user_id' => Auth::id()]);

        if (!Auth::check()) {
            Log::warning('Check-out: thất bại - chưa đăng nhập');
            return redirect()->route('admin.diem-danh.diem-danh')->with('error', 'Vui lòng đăng nhập.');
        }

        $record = DiemDanh::query()
            ->where('user_id', Auth::id())
            ->whereDate('gio_vao', today())
            ->whereNull('gio_ra')
            ->first();

        if (!$record) {
            Log::warning('Check-out: không có bản ghi check-in hôm nay hoặc đã check-out', [
                'user_id' => Auth::id(),
                'today' => today()->toDateString(),
            ]);
            return redirect()->route('admin.diem-danh.diem-danh')->with('error', 'Chưa có bản ghi check-in hôm nay hoặc đã check-out rồi.');
        }

        $gioRa = Carbon::now();
        $gioVao = Carbon::parse($record->gio_vao);
        $cuoiGioCoBan = Carbon::parse($gioVao->toDateString() . ' 21:00:00');

        // Giờ làm cơ bản: từ gio_vao đến min(gio_ra, 21:00). Nếu ra trước 21:00 thì toàn bộ là cơ bản.
        if ($gioRa->lte($cuoiGioCoBan)) {
            $gioLamCoBan = round($gioVao->diffInMinutes($gioRa) / 60, 2);
            $gioLamTangCa = 0.0;
        } else {
            $gioLamCoBan = round($gioVao->diffInMinutes($cuoiGioCoBan) / 60, 2);
            $gioLamTangCa = round($cuoiGioCoBan->diffInMinutes($gioRa) / 60, 2);
        }

        // Lấy đơn giá lương từ nhan_vien (theo user_id)
        $nhanVien = NhanVien::query()->where('user_id', $record->user_id)->first();
        $donGiaLuongCoBan = $nhanVien ? (float) $nhanVien->luong_co_ban : 0;
        $donGiaLuongTangCa = $nhanVien ? (float) $nhanVien->luong_tang_ca : 0;

        $luongCoBan = round($gioLamCoBan * $donGiaLuongCoBan, 2);
        $luongTangCa = round($gioLamTangCa * $donGiaLuongTangCa, 2);

        $record->update([
            'gio_ra' => $gioRa,
            'gio_lam_co_ban' => $gioLamCoBan,
            'gio_lam_tang_ca' => $gioLamTangCa,
            'luong_co_ban' => $luongCoBan,
            'luong_tang_ca' => $luongTangCa,
        ]);

        Log::info('Check-out: thành công', [
            'user_id' => Auth::id(),
            'diem_danh_id' => $record->id,
            'gio_vao' => $gioVao->toDateTimeString(),
            'gio_ra' => $gioRa->toDateTimeString(),
            'gio_lam_co_ban' => $gioLamCoBan,
            'gio_lam_tang_ca' => $gioLamTangCa,
            'luong_co_ban' => $luongCoBan,
            'luong_tang_ca' => $luongTangCa,
        ]);

        return redirect()->route('admin.diem-danh.diem-danh')->with('success', 'Check-out thành công lúc ' . $gioRa->format('H:i d/m/Y') . '.');
    }
    // Điều phối công việc (chỉ xem danh sách hợp đồng, không thêm/sửa)
    public function dieuPhoiCongViec(Request $request)
    {
        $search = $request->get('search');
        $danhSach = HopDong::query()
            ->with(['khachHang', 'nguoiTao', 'thoChup.user', 'thoMake.user', 'thoEdit.user'])
            ->when($search, function ($q) use ($search) {
                $q->whereHas('khachHang', function ($q2) use ($search) {
                    $q2->where('ho_ten_chu_re', 'like', "%{$search}%")
                        ->orWhere('ho_ten_co_dau', 'like', "%{$search}%")
                        ->orWhere('email_hoac_sdt_chu_re', 'like', "%{$search}%")
                        ->orWhere('email_hoac_sdt_co_dau', 'like', "%{$search}%");
                });
            })
            ->orderByDesc('id')
            ->paginate(15)
            ->withQueryString();

        $danhSachNhanVien = NhanVien::query()->with('user')->orderBy('id')->get();

        return view('admin.diem-danh.dieu-phoi-cong-viec', compact('danhSach', 'danhSachNhanVien'));
    }

    /**
     * Cập nhật phân công thợ chụp / make / edit cho hợp đồng (từ modal Phân việc).
     */
    public function phanCongCongViec(Request $request, HopDong $hopDong)
    {
        $validated = $request->validate([
            'tho_chup_id' => 'nullable|exists:nhan_vien,id',
            'tho_make_id' => 'nullable|exists:nhan_vien,id',
            'tho_edit_id' => 'nullable|exists:nhan_vien,id',
        ]);

        $hopDong->update([
            'tho_chup_id' => $validated['tho_chup_id'] ?? null,
            'tho_make_id' => $validated['tho_make_id'] ?? null,
            'tho_edit_id' => $validated['tho_edit_id'] ?? null,
        ]);

        return redirect()->route('admin.diem-danh.dieu-phoi-cong-viec')->with('success', 'Phân công công việc đã được cập nhật.');
    }

    public function storeDieuPhoiCongViec(Request $request)
    {
        return redirect()->route('admin.diem-danh.dieu-phoi-cong-viec')->with('success', 'Điều phối công việc thành công.');
    }
    public function updateDieuPhoiCongViec(Request $request, $dieuPhoiCongViec)
    {
        return redirect()->route('admin.diem-danh.dieu-phoi-cong-viec')->with('success', 'Điều phối công việc thành công.');
    }
    public function destroyDieuPhoiCongViec(Request $request, $dieuPhoiCongViec)
    {
        return redirect()->route('admin.diem-danh.dieu-phoi-cong-viec')->with('success', 'Điều phối công việc thành công.');
    }
}
