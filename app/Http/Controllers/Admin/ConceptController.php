<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Concept;
use App\Models\HopDong;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ConceptController extends Controller
{
    public function concept(Request $request)
    {
        $query = Concept::query();

        if ($request->filled('search')) {
            $q = (string) $request->input('search');
            $query->where('ten_concept', 'like', '%' . $q . '%');
        }

        $danhSach = $query
            ->orderByDesc('id')
            ->paginate(15)
            ->withQueryString();

        // Tính số lần concept đang được dùng trong các hợp đồng.
        // Sau thay đổi: `hop_dong.concept` lưu ID concept (không lưu tên).
        // Vẫn hỗ trợ tương thích dữ liệu cũ: có thể còn tồn tại các hàng lưu bằng `ten_concept`.
        $tenConcepts = $danhSach->getCollection()
            ->pluck('ten_concept')
            ->filter(fn ($v) => !empty($v))
            ->values()
            ->all();

        $conceptIds = $danhSach->getCollection()
            ->pluck('id')
            ->filter(fn ($v) => !empty($v))
            ->values()
            ->all();

        $soLuotSuDungByConcept = collect();
        if (!empty($conceptIds)) {
            // Đếm theo ID concept (mới)
            $countsByTenFromIds = HopDong::query()
                ->join('concept', 'concept.id', '=', 'hop_dong.concept')
                ->select('concept.ten_concept', DB::raw('COUNT(*) as so_luot_su_dung'))
                ->whereIn('concept.id', $conceptIds)
                ->groupBy('concept.ten_concept')
                ->pluck('so_luot_su_dung', 'concept.ten_concept');

            $soLuotSuDungByConcept = $countsByTenFromIds;

            // Nếu dữ liệu cũ còn lưu bằng tên, cộng thêm phần đó
            if (!empty($tenConcepts)) {
                $countsByTenFromOldNames = HopDong::query()
                    ->select('concept', DB::raw('COUNT(*) as so_luot_su_dung'))
                    ->whereIn('concept', $tenConcepts)
                    ->groupBy('concept')
                    ->pluck('so_luot_su_dung', 'concept');

                foreach ($countsByTenFromOldNames as $ten => $cnt) {
                    $soLuotSuDungByConcept[$ten] = (int) ($soLuotSuDungByConcept[$ten] ?? 0) + (int) $cnt;
                }
            }
        }

        return view('admin.concept.concept', compact('danhSach', 'soLuotSuDungByConcept'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'ten_concept' => 'required|string|max:255',
            'hinh_anh' => 'nullable|image|max:10240',
            'trang_thai' => 'required|integer|in:0,1',
        ]);

        $hinhAnhPath = null;
        if ($request->hasFile('hinh_anh')) {
            $hinhAnhPath = $request->file('hinh_anh')->store('concept', 'public');
        }

        Concept::create([
            'ten_concept' => $validated['ten_concept'],
            'hinh_anh' => $hinhAnhPath,
            'trang_thai' => (int) $validated['trang_thai'],
        ]);

        return redirect()
            ->route('admin.concept.concept')
            ->with('success', 'Đã thêm concept thành công.');
    }

    public function update(Request $request, Concept $concept)
    {
        $validated = $request->validate([
            'ten_concept' => 'required|string|max:255',
            'hinh_anh' => 'nullable|image|max:10240',
            'trang_thai' => 'required|integer|in:0,1',
        ]);

        $updateData = [
            'ten_concept' => $validated['ten_concept'],
            'trang_thai' => (int) $validated['trang_thai'],
        ];

        if ($request->hasFile('hinh_anh')) {
            $newPath = $request->file('hinh_anh')->store('concept', 'public');

            if (!empty($concept->hinh_anh) && Storage::disk('public')->exists($concept->hinh_anh)) {
                Storage::disk('public')->delete($concept->hinh_anh);
            }

            $updateData['hinh_anh'] = $newPath;
        }

        $concept->update($updateData);

        return redirect()
            ->route('admin.concept.concept')
            ->with('success', 'Đã cập nhật concept thành công.');
    }

    public function destroy(Concept $concept)
    {
        if (!empty($concept->hinh_anh) && Storage::disk('public')->exists($concept->hinh_anh)) {
            Storage::disk('public')->delete($concept->hinh_anh);
        }

        $concept->delete();

        return redirect()
            ->route('admin.concept.concept')
            ->with('success', 'Đã xóa concept thành công.');
    }
}

