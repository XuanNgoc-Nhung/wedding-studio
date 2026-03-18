<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HopDong;
use App\Models\KhoHang;
use App\Models\TrangPhuc;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class TrangPhucController extends Controller
{
    public function index()
    {
        return redirect()->route('admin.trang-phuc.san-pham');
    }

    public function sanPham()
    {
        return $this->danhSachSanPham(request());
    }

    public function khoHang(Request $request)
    {
        $query = KhoHang::query()->with('trangPhuc');

        if ($request->filled('search')) {
            $q = $request->input('search');
            $query->whereHas('trangPhuc', function ($qb) use ($q) {
                $qb->where('ten_san_pham', 'like', '%' . $q . '%')
                    ->orWhere('ma_san_pham', 'like', '%' . $q . '%');
            });
        }

        $danhSach = $query->orderByDesc('id')->paginate(15)->withQueryString();
        $sanPhamChuaCoTrongKho = TrangPhuc::whereDoesntHave('khoHang')->orderBy('ten_san_pham')->get();

        return view('admin.trang-phuc.kho-hang', compact('danhSach', 'sanPhamChuaCoTrongKho'));
    }

    public function danhSachSanPham(Request $request)
    {
        $query = TrangPhuc::query();

        if ($request->filled('search')) {
            $q = $request->input('search');
            $query->where(function ($qb) use ($q) {
                $qb->where('ten_san_pham', 'like', '%' . $q . '%')
                    ->orWhere('ma_san_pham', 'like', '%' . $q . '%');
            });
        }

        $danhSach = $query->orderByDesc('id')->paginate(15)->withQueryString();

        return view('admin.trang-phuc.san-pham', compact('danhSach'));
    }

    public function storeSanPham(Request $request)
    {
        $validated = $request->validate([
            'ten_san_pham' => 'required|string|max:255',
            'ma_san_pham' => 'required|string|max:255|unique:trang_phuc,ma_san_pham',
            'slug' => 'nullable|string|max:255|unique:trang_phuc,slug',
            'hinh_anh' => 'nullable|image|mimes:jpeg,png,gif,webp|max:5120',
            'mo_ta' => 'nullable|string',
            'ghi_chu' => 'nullable|string',
            'trang_thai' => 'nullable|in:0,1',
            'gia_tri' => 'nullable|numeric|min:0',
            'nha_cung_cap' => 'nullable|string|max:255',
        ]);

        $slug = $validated['slug'] ?? null;
        if (empty($slug)) {
            $slug = Str::slug($validated['ten_san_pham']);
        }

        $slug = $this->uniqueSlug($slug);

        $hinhAnhPath = null;
        if ($request->hasFile('hinh_anh')) {
            $hinhAnhPath = $request->file('hinh_anh')->store('trang-phuc/san-pham', 'public');
        }

        TrangPhuc::create([
            'ten_san_pham' => $validated['ten_san_pham'],
            'ma_san_pham' => $validated['ma_san_pham'],
            'slug' => $slug,
            'hinh_anh' => $hinhAnhPath,
            'mo_ta' => $validated['mo_ta'] ?? null,
            'ghi_chu' => $validated['ghi_chu'] ?? null,
            'trang_thai' => $validated['trang_thai'] ?? 1,
            'gia_tri' => $validated['gia_tri'] ?? 0,
            'nha_cung_cap' => $validated['nha_cung_cap'] ?? null,
        ]);

        return redirect()->route('admin.trang-phuc.san-pham')->with('success', 'Đã thêm sản phẩm trang phục thành công.');
    }

    public function updateSanPham(Request $request, TrangPhuc $trangPhuc)
    {
        $validated = $request->validate([
            'ten_san_pham' => 'required|string|max:255',
            'ma_san_pham' => 'required|string|max:255|unique:trang_phuc,ma_san_pham,' . $trangPhuc->id,
            'slug' => 'nullable|string|max:255|unique:trang_phuc,slug,' . $trangPhuc->id,
            'hinh_anh' => 'nullable|image|mimes:jpeg,png,gif,webp|max:5120',
            'mo_ta' => 'nullable|string',
            'ghi_chu' => 'nullable|string',
            'trang_thai' => 'nullable|in:0,1',
            'gia_tri' => 'nullable|numeric|min:0',
            'nha_cung_cap' => 'nullable|string|max:255',
        ]);

        $slug = $validated['slug'] ?? null;
        if (empty($slug)) {
            $slug = Str::slug($validated['ten_san_pham']);
        }

        $slug = $this->uniqueSlug($slug, $trangPhuc->id);

        $updateData = [
            'ten_san_pham' => $validated['ten_san_pham'],
            'ma_san_pham' => $validated['ma_san_pham'],
            'slug' => $slug,
            'mo_ta' => $validated['mo_ta'] ?? null,
            'ghi_chu' => $validated['ghi_chu'] ?? null,
            'trang_thai' => $validated['trang_thai'] ?? 1,
            'gia_tri' => $validated['gia_tri'] ?? 0,
            'nha_cung_cap' => $validated['nha_cung_cap'] ?? null,
        ];

        if ($request->hasFile('hinh_anh')) {
            $newPath = $request->file('hinh_anh')->store('trang-phuc/san-pham', 'public');
            $updateData['hinh_anh'] = $newPath;

            if (!empty($trangPhuc->hinh_anh) && Storage::disk('public')->exists($trangPhuc->hinh_anh)) {
                Storage::disk('public')->delete($trangPhuc->hinh_anh);
            }
        }

        $trangPhuc->update($updateData);

        return redirect()->route('admin.trang-phuc.san-pham')->with('success', 'Đã cập nhật sản phẩm trang phục thành công.');
    }

    public function destroySanPham(TrangPhuc $trangPhuc)
    {
        $trangPhuc->delete();
        return redirect()->route('admin.trang-phuc.san-pham')->with('success', 'Đã xóa sản phẩm trang phục.');
    }

    public function storeKhoHang(Request $request)
    {
        $validated = $request->validate([
            'trang_phuc_id' => 'required|exists:trang_phuc,id|unique:kho_hang,trang_phuc_id',
            'so_luong' => 'required|integer|min:0',
            'gia_cho_thue' => 'nullable|numeric|min:0',
            'ghi_chu' => 'nullable|string',
            'trang_thai' => 'nullable|in:0,1',
        ], [
            'trang_phuc_id.unique' => 'Sản phẩm này đã có trong kho. Vui lòng cập nhật số lượng tại mục chỉnh sửa.',
        ]);

        KhoHang::create([
            'trang_phuc_id' => $validated['trang_phuc_id'],
            'so_luong' => $validated['so_luong'],
            'gia_cho_thue' => $validated['gia_cho_thue'] ?? 0,
            'ghi_chu' => $validated['ghi_chu'] ?? null,
            'trang_thai' => $validated['trang_thai'] ?? 1,
        ]);

        return redirect()->route('admin.trang-phuc.kho-hang')->with('success', 'Đã thêm sản phẩm vào kho thành công.');
    }

    public function updateKhoHang(Request $request, KhoHang $khoHang)
    {
        $validated = $request->validate([
            'so_luong' => 'required|integer|min:0',
            'gia_cho_thue' => 'nullable|numeric|min:0',
            'ghi_chu' => 'nullable|string',
            'trang_thai' => 'nullable|in:0,1',
        ]);

        $khoHang->update([
            'so_luong' => $validated['so_luong'],
            'gia_cho_thue' => $validated['gia_cho_thue'] ?? 0,
            'ghi_chu' => $validated['ghi_chu'] ?? null,
            'trang_thai' => $validated['trang_thai'] ?? 1,
        ]);

        return redirect()->route('admin.trang-phuc.kho-hang')->with('success', 'Đã cập nhật kho hàng thành công.');
    }

    public function destroyKhoHang(KhoHang $khoHang)
    {
        $khoHang->delete();
        return redirect()->route('admin.trang-phuc.kho-hang')->with('success', 'Đã xóa khỏi kho hàng.');
    }

    private function uniqueSlug(string $slug, ?int $ignoreId = null): string
    {
        $base = $slug;
        $i = 2;

        $exists = function (string $candidate) use ($ignoreId): bool {
            return TrangPhuc::query()
                ->when($ignoreId, fn ($q) => $q->where('id', '<>', $ignoreId))
                ->where('slug', $candidate)
                ->exists();
        };

        while ($exists($slug)) {
            $slug = $base . '-' . $i;
            $i++;
        }

        return $slug;
    }
    public function hopDong(Request $request)
    {
        $query = HopDong::query()->with(['trangPhuc', 'user']);

        if ($request->filled('search')) {
            $q = $request->input('search');
            $query->where(function ($qb) use ($q) {
                $qb->where('ten_khach_hang', 'like', '%' . $q . '%')
                    ->orWhere('so_dien_thoai', 'like', '%' . $q . '%');
            });
        }

        $danhSach = $query->orderByDesc('id')->paginate(15)->withQueryString();
        $danhSachSanPham = TrangPhuc::orderBy('ten_san_pham')->get();
        $stockByProduct = KhoHang::whereIn('trang_phuc_id', $danhSachSanPham->pluck('id'))->pluck('so_luong', 'trang_phuc_id');

        return view('admin.trang-phuc.hop-dong', compact('danhSach', 'danhSachSanPham', 'stockByProduct'));
    }

    public function storeHopDong(Request $request)
    {
        $validated = $request->validate([
            'ten_khach_hang' => 'required|string|max:255',
            'so_dien_thoai' => 'required|string|max:20',
            'trang_phuc_id' => 'required|exists:trang_phuc,id',
            'so_luong_thue' => 'required|integer|min:1',
            'gia_thue' => 'required|numeric|min:0',
            'thoi_gian_thue_bat_dau' => 'required|date',
            'thoi_gian_du_kien_tra' => 'required|date|after_or_equal:thoi_gian_thue_bat_dau',
            'thoi_gian_tra_hang_thuc_te' => 'nullable|date',
            'ghi_chu' => 'nullable|string',
            'trang_thai' => 'nullable|in:0,1,2',
        ]);

        $stock = (int) (KhoHang::where('trang_phuc_id', $validated['trang_phuc_id'])->value('so_luong') ?? 0);
        if ($validated['so_luong_thue'] > $stock) {
            return redirect()->back()
                ->withErrors(['so_luong_thue' => 'Số lượng thuê không được vượt quá số lượng trong kho (trong kho còn ' . $stock . ').'])
                ->withInput();
        }

        HopDong::create([
            'ten_khach_hang' => $validated['ten_khach_hang'],
            'so_dien_thoai' => $validated['so_dien_thoai'] ?? null,
            'trang_phuc_id' => $validated['trang_phuc_id'],
            'so_luong_thue' => $validated['so_luong_thue'],
            'gia_thue' => $validated['gia_thue'],
            'thoi_gian_thue_bat_dau' => Carbon::parse($validated['thoi_gian_thue_bat_dau'])->startOfDay(),
            'thoi_gian_du_kien_tra' => Carbon::parse($validated['thoi_gian_du_kien_tra'])->startOfDay(),
            'thoi_gian_tra_hang_thuc_te' => isset($validated['thoi_gian_tra_hang_thuc_te']) ? Carbon::parse($validated['thoi_gian_tra_hang_thuc_te'])->startOfDay() : null,
            'ghi_chu' => $validated['ghi_chu'] ?? null,
            'trang_thai' => $validated['trang_thai'] ?? HopDong::TRANG_THAI_CHO_XU_LY,
            'user_id' => $request->user()?->id,
        ]);

        return redirect()->route('admin.trang-phuc.hop-dong')->with('success', 'Đã thêm hợp đồng thành công.');
    }

    public function updateHopDong(Request $request, HopDong $hopDong)
    {
        $validated = $request->validate([
            'ten_khach_hang' => 'required|string|max:255',
            'so_dien_thoai' => 'nullable|string|max:20',
            'trang_phuc_id' => 'required|exists:trang_phuc,id',
            'so_luong_thue' => 'required|integer|min:1',
            'gia_thue' => 'required|numeric|min:0',
            'thoi_gian_thue_bat_dau' => 'required|date',
            'thoi_gian_du_kien_tra' => 'required|date',
            'thoi_gian_tra_hang_thuc_te' => 'nullable|date',
            'ghi_chu' => 'nullable|string',
            'trang_thai' => 'nullable|in:0,1,2',
        ]);

        $stock = (int) (KhoHang::where('trang_phuc_id', $validated['trang_phuc_id'])->value('so_luong') ?? 0);
        $available = $stock;
        if ((int) $hopDong->trang_phuc_id === (int) $validated['trang_phuc_id']) {
            $available += $hopDong->so_luong_thue;
        }
        if ($validated['so_luong_thue'] > $available) {
            return redirect()->back()
                ->withErrors(['so_luong_thue' => 'Số lượng thuê không được vượt quá số lượng trong kho (trong kho còn ' . $stock . ', tối đa có thể thuê: ' . $available . ').'])
                ->withInput();
        }

        $hopDong->update([
            'ten_khach_hang' => $validated['ten_khach_hang'],
            'so_dien_thoai' => $validated['so_dien_thoai'] ?? null,
            'trang_phuc_id' => $validated['trang_phuc_id'],
            'so_luong_thue' => $validated['so_luong_thue'],
            'gia_thue' => $validated['gia_thue'],
            'thoi_gian_thue_bat_dau' => Carbon::parse($validated['thoi_gian_thue_bat_dau'])->startOfDay(),
            'thoi_gian_du_kien_tra' => Carbon::parse($validated['thoi_gian_du_kien_tra'])->startOfDay(),
            'thoi_gian_tra_hang_thuc_te' => isset($validated['thoi_gian_tra_hang_thuc_te']) ? Carbon::parse($validated['thoi_gian_tra_hang_thuc_te'])->startOfDay() : null,
            'ghi_chu' => $validated['ghi_chu'] ?? null,
            'trang_thai' => $validated['trang_thai'] ?? $hopDong->trang_thai,
        ]);

        return redirect()->route('admin.trang-phuc.hop-dong')->with('success', 'Đã cập nhật hợp đồng thành công.');
    }

    public function destroyHopDong(HopDong $hopDong)
    {
        $hopDong->delete();
        return redirect()->route('admin.trang-phuc.hop-dong')->with('success', 'Đã xóa hợp đồng.');
    }
}
