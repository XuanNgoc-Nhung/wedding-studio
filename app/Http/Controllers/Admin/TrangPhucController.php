<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HopDong;
use App\Models\HopDongThueTrangPhuc;
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
        $query = HopDongThueTrangPhuc::query()->with(['sanPham', 'nguoiChoThue']);

        if ($request->filled('search')) {
            $q = $request->input('search');
            $query->where(function ($qb) use ($q) {
                $qb->where('ten_khach_hang', 'like', '%' . $q . '%')
                    ->orWhere('sdt_khach_hang', 'like', '%' . $q . '%');
            });
        }

        $danhSach = $query->orderByDesc('id')->paginate(15)->withQueryString();
        $danhSachSanPham = TrangPhuc::orderBy('ten_san_pham')->get();
        $stockInKho = KhoHang::whereIn('trang_phuc_id', $danhSachSanPham->pluck('id'))
            ->pluck('so_luong', 'trang_phuc_id');
        $dangThueByProduct = HopDongThueTrangPhuc::query()
            ->whereIn('trang_thai', ['moi', 'dang_thue'])
            ->selectRaw('san_pham_id, SUM(so_luong) AS tong_dang_thue')
            ->groupBy('san_pham_id')
            ->pluck('tong_dang_thue', 'san_pham_id');

        $stockByProduct = $danhSachSanPham
            ->mapWithKeys(function ($sp) use ($stockInKho, $dangThueByProduct) {
                $kho = (int) ($stockInKho[$sp->id] ?? 0);
                $dangThue = (int) ($dangThueByProduct[$sp->id] ?? 0);
                $available = max($kho - $dangThue, 0);
                return [$sp->id => $available];
            });

        // Map: trang_phuc_id => ['dd/mm/YYYY', ...] (chỉ ngày chụp >= hôm nay, theo hợp đồng chụp ảnh — giống màn khách hàng)
        $today = now()->toDateString();
        $trangPhucSuDungTuHomNay = [];
        $hopDongTuHomNay = HopDong::query()
            ->whereDate('ngay_chup', '>=', $today)
            ->whereNotNull('trang_phuc')
            ->select(['ngay_chup', 'trang_phuc'])
            ->orderBy('ngay_chup')
            ->get();

        foreach ($hopDongTuHomNay as $hd) {
            $raw = (string) ($hd->trang_phuc ?? '');
            $ids = array_filter(array_map('trim', explode(',', $raw)));
            if (empty($ids)) {
                continue;
            }

            $date = null;
            if (! empty($hd->ngay_chup)) {
                try {
                    $date = ($hd->ngay_chup instanceof Carbon)
                        ? $hd->ngay_chup->format('d/m/Y')
                        : Carbon::parse($hd->ngay_chup)->format('d/m/Y');
                } catch (\Throwable $e) {
                    $date = null;
                }
            }
            if ($date === null) {
                continue;
            }

            foreach ($ids as $id) {
                $idInt = (int) $id;
                if ($idInt <= 0) {
                    continue;
                }
                $trangPhucSuDungTuHomNay[$idInt] ??= [];
                $trangPhucSuDungTuHomNay[$idInt][$date] = true;
            }
        }

        foreach ($trangPhucSuDungTuHomNay as $id => $dateSet) {
            $trangPhucSuDungTuHomNay[$id] = array_keys($dateSet);
        }

        return view('admin.trang-phuc.hop-dong', compact(
            'danhSach',
            'danhSachSanPham',
            'stockByProduct',
            'trangPhucSuDungTuHomNay'
        ));
    }

    public function storeHopDong(Request $request)
    {
        $validated = $request->validate([
            'ten_khach_hang' => 'required|string|max:255',
            'so_dien_thoai' => 'required|string|max:20',
            'trang_phuc' => 'required|array|min:1',
            'trang_phuc.*' => 'integer|exists:trang_phuc,id',
            'so_luong_thue' => 'required|integer|min:1',
            'gia_thue' => 'required|numeric|min:0',
            'thoi_gian_thue_bat_dau' => 'required|date',
            'thoi_gian_du_kien_tra' => 'required|date|after_or_equal:thoi_gian_thue_bat_dau',
            'thoi_gian_tra_hang_thuc_te' => 'nullable|date',
            'ghi_chu' => 'nullable|string',
            'trang_thai' => 'nullable|in:moi,dang_thue,hoan_thanh',
        ]);

        $productIds = array_values(array_unique(array_map('intval', $validated['trang_phuc'])));

        foreach ($productIds as $trangPhucId) {
            $kho = (int) (KhoHang::where('trang_phuc_id', $trangPhucId)->value('so_luong') ?? 0);
            $dangThue = (int) (HopDongThueTrangPhuc::where('san_pham_id', $trangPhucId)
                ->whereIn('trang_thai', ['moi', 'dang_thue'])
                ->sum('so_luong') ?? 0);
            $available = max($kho - $dangThue, 0);
            if ($validated['so_luong_thue'] > $available) {
                return redirect()->back()
                    ->withErrors(['trang_phuc' => 'Số lượng thuê vượt quá tồn cho một hoặc nhiều sản phẩm đã chọn (SP id ' . $trangPhucId . ' còn ' . $available . ').'])
                    ->withInput();
            }
        }

        $ngayTraThucTe = isset($validated['thoi_gian_tra_hang_thuc_te'])
            ? Carbon::parse($validated['thoi_gian_tra_hang_thuc_te'])->startOfDay()
            : null;

        foreach ($productIds as $trangPhucId) {
            HopDongThueTrangPhuc::create([
                'ten_khach_hang' => $validated['ten_khach_hang'],
                'sdt_khach_hang' => $validated['so_dien_thoai'],
                'san_pham_id' => $trangPhucId,
                'so_luong' => $validated['so_luong_thue'],
                'gia_thue' => $validated['gia_thue'],
                'ngay_thue' => Carbon::parse($validated['thoi_gian_thue_bat_dau'])->startOfDay(),
                'ngay_tra_du_kien' => Carbon::parse($validated['thoi_gian_du_kien_tra'])->startOfDay(),
                'ngay_tra_thuc_te' => $ngayTraThucTe,
                'ghi_chu' => $validated['ghi_chu'] ?? null,
                'trang_thai' => $validated['trang_thai'] ?? 'moi',
                'nguoi_cho_thue' => $request->user()?->id,
            ]);
        }

        $n = count($productIds);
        $msg = $n === 1
            ? 'Đã thêm hợp đồng thành công.'
            : 'Đã thêm ' . $n . ' hợp đồng (mỗi sản phẩm một dòng) thành công.';

        return redirect()->route('admin.trang-phuc.hop-dong')->with('success', $msg);
    }

    public function updateHopDong(Request $request, HopDongThueTrangPhuc $hopDong)
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
            'trang_thai' => 'nullable|in:moi,dang_thue,hoan_thanh',
        ]);

        $kho = (int) (KhoHang::where('trang_phuc_id', $validated['trang_phuc_id'])->value('so_luong') ?? 0);
        $dangThue = (int) (HopDongThueTrangPhuc::where('san_pham_id', $validated['trang_phuc_id'])
            ->whereIn('trang_thai', ['moi', 'dang_thue'])
            ->where('id', '<>', $hopDong->id)
            ->sum('so_luong') ?? 0);
        $available = max($kho - $dangThue, 0);
        if ($validated['so_luong_thue'] > $available) {
            return redirect()->back()
                ->withErrors(['so_luong_thue' => 'Số lượng thuê không được vượt quá số lượng còn khả dụng (còn ' . $available . ' trong kho).'])
                ->withInput();
        }

        $hopDong->update([
            'ten_khach_hang' => $validated['ten_khach_hang'],
            'sdt_khach_hang' => $validated['so_dien_thoai'] ?? $hopDong->sdt_khach_hang,
            'san_pham_id' => $validated['trang_phuc_id'],
            'so_luong' => $validated['so_luong_thue'],
            'gia_thue' => $validated['gia_thue'],
            'ngay_thue' => Carbon::parse($validated['thoi_gian_thue_bat_dau'])->startOfDay(),
            'ngay_tra_du_kien' => Carbon::parse($validated['thoi_gian_du_kien_tra'])->startOfDay(),
            'ngay_tra_thuc_te' => isset($validated['thoi_gian_tra_hang_thuc_te']) ? Carbon::parse($validated['thoi_gian_tra_hang_thuc_te'])->startOfDay() : null,
            'ghi_chu' => $validated['ghi_chu'] ?? null,
            'trang_thai' => $validated['trang_thai'] ?? $hopDong->trang_thai,
        ]);

        return redirect()->route('admin.trang-phuc.hop-dong')->with('success', 'Đã cập nhật hợp đồng thành công.');
    }

    public function destroyHopDong(HopDongThueTrangPhuc $hopDong)
    {
        $hopDong->delete();
        return redirect()->route('admin.trang-phuc.hop-dong')->with('success', 'Đã xóa hợp đồng.');
    }
}
