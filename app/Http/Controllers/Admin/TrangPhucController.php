<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KhoHang;
use App\Models\TrangPhuc;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

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

        TrangPhuc::create([
            'ten_san_pham' => $validated['ten_san_pham'],
            'ma_san_pham' => $validated['ma_san_pham'],
            'slug' => $slug,
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

        $trangPhuc->update([
            'ten_san_pham' => $validated['ten_san_pham'],
            'ma_san_pham' => $validated['ma_san_pham'],
            'slug' => $slug,
            'mo_ta' => $validated['mo_ta'] ?? null,
            'ghi_chu' => $validated['ghi_chu'] ?? null,
            'trang_thai' => $validated['trang_thai'] ?? 1,
            'gia_tri' => $validated['gia_tri'] ?? 0,
            'nha_cung_cap' => $validated['nha_cung_cap'] ?? null,
        ]);

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
}
