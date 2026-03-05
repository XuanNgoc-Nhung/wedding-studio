<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
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

    public function khoHang()
    {
        return view('admin.trang-phuc.kho-hang');
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
        return redirect()->back()->with('error', 'Chức năng kho hàng đang được cập nhật.');
    }

    public function updateKhoHang(Request $request, $khoHang)
    {
        return redirect()->back()->with('error', 'Chức năng kho hàng đang được cập nhật.');
    }

    public function destroyKhoHang($khoHang)
    {
        return redirect()->back()->with('error', 'Chức năng kho hàng đang được cập nhật.');
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
