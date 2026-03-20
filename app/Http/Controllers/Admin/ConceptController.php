<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Concept;
use Illuminate\Http\Request;
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

        return view('admin.concept.concept', compact('danhSach'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'ten_concept' => 'required|string|max:255',
            'hinh_anh' => 'nullable|image|mimes:jpeg,png,gif,webp|max:5120',
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
            'hinh_anh' => 'nullable|image|mimes:jpeg,png,gif,webp|max:5120',
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

