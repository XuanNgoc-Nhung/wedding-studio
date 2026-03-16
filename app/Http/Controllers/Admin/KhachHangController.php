<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DichVuLe;
use App\Models\DichVuTrongHopDong;
use App\Models\HopDong;
use App\Models\KhachHang;
use App\Models\NhanVien;
use App\Models\NhomDichVu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class KhachHangController extends Controller
{
    public function dichVuTrongHopDong(HopDong $hopDong)
    {
        $rows = DichVuTrongHopDong::query()
            ->where('dich_vu_trong_hop_dong.id_hop_dong', $hopDong->id)
            ->join('dich_vu_le', 'dich_vu_le.id', '=', 'dich_vu_trong_hop_dong.id_dich_vu')
            ->select([
                'dich_vu_trong_hop_dong.id',
                'dich_vu_trong_hop_dong.id_hop_dong',
                'dich_vu_trong_hop_dong.id_dich_vu',
                'dich_vu_trong_hop_dong.gia_goc',
                'dich_vu_trong_hop_dong.gia_thuc',
                'dich_vu_trong_hop_dong.ghi_chu',
                'dich_vu_le.ten_dich_vu',
                'dich_vu_le.ma_dich_vu',
            ])
            ->orderBy('dich_vu_trong_hop_dong.id')
            ->get();

        return response()->json([
            'data' => $rows,
        ]);
    }

    public function index()
    {
        return redirect()->route('admin.khach-hang.danh-sach');
    }

    public function danhSach(Request $request)
    {
        $search = $request->get('search');

        $danhSach = KhachHang::query()
            ->when($search, function ($q) use ($search) {
                $q->where('ho_ten_chu_re', 'like', "%{$search}%")
                    ->orWhere('ho_ten_co_dau', 'like', "%{$search}%")
                    ->orWhere('email_hoac_sdt_chu_re', 'like', "%{$search}%")
                    ->orWhere('email_hoac_sdt_co_dau', 'like', "%{$search}%")
                    ->orWhere('dia_chi_chu_re', 'like', "%{$search}%")
                    ->orWhere('dia_chi_co_dau', 'like', "%{$search}%")
                    ->orWhere('ghi_chu', 'like', "%{$search}%");
            })
            ->orderBy('id')
            ->paginate(15)
            ->withQueryString();

        return view('admin.khach-hang.danh-sach', compact('danhSach'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'ho_ten_chu_re' => 'nullable|string|max:255',
            'ngay_sinh_chu_re' => 'nullable|date',
            'gioi_tinh_chu_re' => 'nullable|string|in:nam,nu,khac',
            'email_hoac_sdt_chu_re' => 'nullable|string|max:255',
            'dia_chi_chu_re' => 'nullable|string',
            'ho_ten_co_dau' => 'nullable|string|max:255',
            'ngay_sinh_co_dau' => 'nullable|date',
            'gioi_tinh_co_dau' => 'nullable|string|in:nam,nu,khac',
            'dia_chi_co_dau' => 'nullable|string',
            'email_hoac_sdt_co_dau' => 'nullable|string|max:255',
            'ghi_chu' => 'nullable|string',
        ]);

        KhachHang::create($validated);

        return redirect()->route('admin.khach-hang.danh-sach')->with('success', 'Đã thêm khách hàng thành công.');
    }

    public function update(Request $request, KhachHang $khachHang)
    {
        $validated = $request->validate([
            'ho_ten_chu_re' => 'nullable|string|max:255',
            'ngay_sinh_chu_re' => 'nullable|date',
            'gioi_tinh_chu_re' => 'nullable|string|in:nam,nu,khac',
            'email_hoac_sdt_chu_re' => 'nullable|string|max:255',
            'dia_chi_chu_re' => 'nullable|string',
            'ho_ten_co_dau' => 'nullable|string|max:255',
            'ngay_sinh_co_dau' => 'nullable|date',
            'gioi_tinh_co_dau' => 'nullable|string|in:nam,nu,khac',
            'dia_chi_co_dau' => 'nullable|string',
            'email_hoac_sdt_co_dau' => 'nullable|string|max:255',
            'ghi_chu' => 'nullable|string',
        ]);

        $khachHang->update($validated);

        return redirect()->route('admin.khach-hang.danh-sach')->with('success', 'Đã cập nhật khách hàng thành công.');
    }

    public function destroy(KhachHang $khachHang)
    {
        $khachHang->delete();
        return redirect()->route('admin.khach-hang.danh-sach')->with('success', 'Đã xóa khách hàng thành công.');
    }
    public function hopDong(Request $request)
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

        $danhSachKhachHang = KhachHang::query()->orderBy('id')->get();
        $danhSachNhanVien = NhanVien::query()->with('user')->orderBy('id')->get();
        $danhSachNhomDichVu = NhomDichVu::query()
            ->with('dichVuLe')
            ->where('trang_thai', NhomDichVu::TRANG_THAI_HIEN_THI)
            ->orderBy('ten_nhom')
            ->get();
        $danhSachDichVuLe = DichVuLe::query()
            ->where('trang_thai', DichVuLe::TRANG_THAI_HIEN_THI)
            ->orderBy('ten_dich_vu')
            ->get();

        return view('admin.khach-hang.hop-dong', compact(
            'danhSach',
            'danhSachKhachHang',
            'danhSachNhanVien',
            'danhSachNhomDichVu',
            'danhSachDichVuLe'
        ));
    }

    public function storeHopDong(Request $request)
    {
        // --- Bước 1: Validate và tách dữ liệu hợp đồng vs dịch vụ lẻ ---
        $validated = $request->validate([
            'khach_hang_id' => 'required|exists:khach_hang,id',
            'tho_chup_id' => 'nullable|exists:nhan_vien,id',
            'tho_make_id' => 'nullable|exists:nhan_vien,id',
            'tho_edit_id' => 'nullable|exists:nhan_vien,id',
            'dia_diem' => 'nullable|string|max:255',
            'ngay_chup' => 'nullable|date',
            'trang_phuc' => 'nullable|string',
            'concept' => 'nullable|string',
            'ghi_chu_chup' => 'nullable|string',
            'trang_thai_chup' => 'nullable|string|max:50',
            'tong_tien' => 'nullable|numeric|min:0',
            'thanh_toan_lan_1' => 'nullable|numeric|min:0',
            'thanh_toan_lan_2' => 'nullable|numeric|min:0',
            'thanh_toan_lan_3' => 'nullable|numeric|min:0',
            'trang_thai_hop_dong' => 'nullable|string|max:50',
            'trang_thai_edit' => 'nullable|string|max:50',
            'link_file_demo' => 'nullable|string|max:500',
            'link_file_in' => 'nullable|string|max:500',
            'ngay_tra_link_in' => 'nullable|date',
            'ngay_hen_tra_hang' => 'nullable|date',
            'dich_vu_le_hop_dong' => 'nullable|array',
            'dich_vu_le_hop_dong.*.dich_vu_le_id' => 'required_with:dich_vu_le_hop_dong|integer|exists:dich_vu_le,id',
            'dich_vu_le_hop_dong.*.gia_goc' => 'nullable|numeric|min:0',
            'dich_vu_le_hop_dong.*.gia_thuc' => 'nullable|numeric|min:0',
            'dich_vu_le_hop_dong.*.ghi_chu' => 'nullable|string|max:500',
        ]);

        $dichVuLeHopDong = $request->input('dich_vu_le_hop_dong', []);
        unset($validated['dich_vu_le_hop_dong']);
        $validated['nguoi_tao_id'] = $request->user()?->id;

        // --- Bước 2: Tạo hợp đồng (chỉ thông tin hợp đồng) ---
        $hopDong = HopDong::create($validated);

        // --- Bước 3: Chỉ khi đã có id hợp đồng mới thêm dịch vụ lẻ vào bảng dich_vu_trong_hop_dong ---
        if ($hopDong->id && ! empty($dichVuLeHopDong)) {
            foreach ($dichVuLeHopDong as $item) {
                $idDichVu = (int) ($item['dich_vu_le_id'] ?? 0);
                if ($idDichVu <= 0) {
                    continue;
                }
                DichVuTrongHopDong::updateOrCreate(
                    [
                        'id_hop_dong' => $hopDong->id,
                        'id_dich_vu' => $idDichVu,
                    ],
                    [
                        'gia_goc' => (float) ($item['gia_goc'] ?? 0),
                        'gia_thuc' => (float) ($item['gia_thuc'] ?? 0),
                        'ghi_chu' => $item['ghi_chu'] ?? null,
                    ]
                );
            }
        }

        return redirect()->route('admin.khach-hang.hop-dong')->with('success', 'Đã thêm hợp đồng thành công.');
    }

    public function updateHopDong(Request $request, HopDong $hopDong)
    {
        $validated = $request->validate([
            'khach_hang_id' => 'required|exists:khach_hang,id',
            'tho_chup_id' => 'nullable|exists:nhan_vien,id',
            'tho_make_id' => 'nullable|exists:nhan_vien,id',
            'tho_edit_id' => 'nullable|exists:nhan_vien,id',
            'dia_diem' => 'nullable|string|max:255',
            'ngay_chup' => 'nullable|date',
            'trang_phuc' => 'nullable|string',
            'concept' => 'nullable|string',
            'ghi_chu_chup' => 'nullable|string',
            'trang_thai_chup' => 'nullable|string|max:50',
            'tong_tien' => 'nullable|numeric|min:0',
            'thanh_toan_lan_1' => 'nullable|numeric|min:0',
            'thanh_toan_lan_2' => 'nullable|numeric|min:0',
            'thanh_toan_lan_3' => 'nullable|numeric|min:0',
            'trang_thai_hop_dong' => 'nullable|string|max:50',
            'trang_thai_edit' => 'nullable|string|max:50',
            'link_file_demo' => 'nullable|string|max:500',
            'link_file_in' => 'nullable|string|max:500',
            'ngay_tra_link_in' => 'nullable|date',
            'ngay_hen_tra_hang' => 'nullable|date',
            'dich_vu_le_hop_dong' => 'nullable|array',
            'dich_vu_le_hop_dong.*.dich_vu_le_id' => 'required_with:dich_vu_le_hop_dong|integer|exists:dich_vu_le,id',
            'dich_vu_le_hop_dong.*.gia_goc' => 'nullable|numeric|min:0',
            'dich_vu_le_hop_dong.*.gia_thuc' => 'nullable|numeric|min:0',
            'dich_vu_le_hop_dong.*.ghi_chu' => 'nullable|string|max:500',
        ]);

        $dichVuLeHopDong = $request->input('dich_vu_le_hop_dong', []);
        unset($validated['dich_vu_le_hop_dong']);

        $hopDong->update($validated);

        // Cập nhật dịch vụ trong hợp đồng: xóa cũ, thêm lại theo danh sách gửi lên
        DichVuTrongHopDong::where('id_hop_dong', $hopDong->id)->delete();
        if (! empty($dichVuLeHopDong)) {
            foreach ($dichVuLeHopDong as $item) {
                $idDichVu = (int) ($item['dich_vu_le_id'] ?? 0);
                if ($idDichVu <= 0) {
                    continue;
                }
                DichVuTrongHopDong::create([
                    'id_hop_dong' => $hopDong->id,
                    'id_dich_vu' => $idDichVu,
                    'gia_goc' => (float) ($item['gia_goc'] ?? 0),
                    'gia_thuc' => (float) ($item['gia_thuc'] ?? 0),
                    'ghi_chu' => $item['ghi_chu'] ?? null,
                ]);
            }
        }

        return redirect()->route('admin.khach-hang.hop-dong')->with('success', 'Đã cập nhật hợp đồng thành công.');
    }

    public function destroyHopDong(HopDong $hopDong)
    {
        // Xóa tất cả bản ghi dịch vụ trong hợp đồng (id_hop_dong trùng) trước khi xóa hợp đồng
        DichVuTrongHopDong::where('id_hop_dong', $hopDong->id)->delete();
        $hopDong->delete();
        return redirect()->route('admin.khach-hang.hop-dong')->with('success', 'Đã xóa hợp đồng.');
    }

    /**
     * Upload ảnh thanh toán và quay lại trang hợp đồng.
     */
    public function uploadAnhThanhToan(Request $request)
    {
        $validated = $request->validate([
            'hop_dong_id' => 'required|exists:hop_dong,id',
            'lan_thanh_toan' => 'required|in:1,2,3',
            'anh_thanh_toan' => 'required|image|max:5120', // 5MB
        ]);

        /** @var \Illuminate\Http\UploadedFile $file */
        $file = $request->file('anh_thanh_toan');

        $hopDong = HopDong::findOrFail($validated['hop_dong_id']);
        $lan = (int) $validated['lan_thanh_toan'];

        $column = 'anh_thanh_toan_' . $lan;

        // Lưu file vào storage/app/public/hop-dong/anh-thanh-toan
        $path = $file->store('hop-dong/anh-thanh-toan', 'public');

        // Cập nhật đường dẫn ảnh cho hợp đồng
        $hopDong->{$column} = $path;
        $hopDong->save();

        return redirect()
            ->route('admin.khach-hang.hop-dong')
            ->with('success', 'Upload ảnh thanh toán thành công.');
    }
}
