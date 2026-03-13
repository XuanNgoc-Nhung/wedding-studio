@extends('admin.layouts.app')

@section('content')
<div class="card">
    <h5 class="card-header">Danh sách sản phẩm trang phục</h5>
    <div class="card-body">
        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Đóng"></button>
        </div>
        @endif
        @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Đóng"></button>
        </div>
        @endif

        {{-- Bộ lọc + Thêm mới --}}
        <form action="{{ route('admin.trang-phuc.san-pham') }}" method="GET" class="mb-4">
            <div class="row g-3 align-items-end">
                <div class="col-md-6 col-lg-4">
                    <label class="form-label" for="search">Tên hoặc mã sản phẩm</label>
                    <input type="text"
                           class="form-control"
                           id="search"
                           name="search"
                           value="{{ request('search') }}"
                           placeholder="Nhập tên hoặc mã sản phẩm...">
                </div>
                <div class="col-auto">
                    <button type="submit" class="btn btn-primary">
                        <i class="fa-solid fa-magnifying-glass me-1"></i> Lọc
                    </button>
                    @if(request('search'))
                    <a href="{{ route('admin.trang-phuc.san-pham') }}" class="btn btn-outline-secondary">Bỏ lọc</a>
                    @endif
                </div>
                <div class="col-auto ms-md-auto">
                    <span data-bs-toggle="tooltip" title="Thêm sản phẩm trang phục mới">
                        <button type="button"
                                class="btn btn-primary"
                                data-bs-toggle="modal"
                                data-bs-target="#modalThemSanPham">
                            <i class="fa-solid fa-plus me-1"></i> Thêm mới
                        </button>
                    </span>
                </div>
            </div>
        </form>

        <div class="table-responsive text-nowrap table-wrapper-bordered">
            <table class="table table-hover table-bordered mb-0">
                <thead class="table-light">
                    <tr>
                        <th style="width: 50px;">STT</th>
                        <th>Tên sản phẩm</th>
                        <th>Mã</th>
                        <th>Slug</th>
                        <th>Nhà cung cấp</th>
                        <th>Mô tả</th>
                        <th class="text-center" style="width: 90px;">Trạng thái</th>
                        <th>Ghi chú</th>
                        <th class="text-end" style="width: 130px;">Giá trị</th>
                        <th class="text-center" style="width: 100px;">Thao tác</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @forelse($danhSach ?? [] as $index => $item)
                    @php
                        $isVisible = (int)($item->trang_thai ?? 0) === 1;
                        $trangThaiLabel = $isVisible ? 'Hiển thị' : 'Ẩn';
                        $trangThaiBadge = $isVisible ? 'bg-label-success' : 'bg-label-secondary';
                    @endphp
                    <tr>
                        <td>{{ ($danhSach->currentPage() - 1) * $danhSach->perPage() + $index + 1 }}</td>
                        <td><span class="fw-medium">{{ $item->ten_san_pham ?? '—' }}</span></td>
                        <td>{{ $item->ma_san_pham ?? '—' }}</td>
                        <td>{{ $item->slug ?? '—' }}</td>
                        <td>{{ $item->nha_cung_cap ?? '—' }}</td>
                        <td>{{ \Illuminate\Support\Str::limit($item->mo_ta ?? '—', 50) }}</td>
                        <td class="text-center">
                            <span class="badge {{ $trangThaiBadge }}">{{ $trangThaiLabel }}</span>
                        </td>
                        <td>{{ \Illuminate\Support\Str::limit($item->ghi_chu ?? '—', 40) }}</td>
                        <td class="text-end">
                            {{ $item->gia_tri !== null ? number_format((float)$item->gia_tri, 0, ',', '.') . ' đ' : '—' }}
                        </td>
                        <td>
                            <div class="dropdown">
                                <button type="button" class="btn btn-sm btn-icon btn-outline-secondary dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                    <i class="fa-solid fa-ellipsis-vertical"></i>
                                </button>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item btn-sua-san-pham"
                                       href="javascript:void(0);"
                                       data-bs-toggle="modal"
                                       data-bs-target="#modalSuaSanPham"
                                       data-url="{{ route('admin.trang-phuc.san-pham.update', $item) }}"
                                       data-ten="{{ e($item->ten_san_pham ?? '') }}"
                                       data-ma="{{ e($item->ma_san_pham ?? '') }}"
                                       data-slug="{{ e($item->slug ?? '') }}"
                                       data-nha-cung-cap="{{ e($item->nha_cung_cap ?? '') }}"
                                       data-mo-ta="{{ e($item->mo_ta ?? '') }}"
                                       data-trang-thai="{{ e($item->trang_thai ?? 1) }}"
                                       data-ghi-chu="{{ e($item->ghi_chu ?? '') }}"
                                       data-gia-tri="{{ $item->gia_tri ?? '' }}">
                                        <i class="fa-solid fa-pen me-2"></i> Sửa
                                    </a>
                                    <form id="form-xoa-sp-{{ $item->id }}" action="{{ route('admin.trang-phuc.san-pham.destroy', $item) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                    <button type="button" class="dropdown-item text-danger btn-xoa-san-pham" data-form-id="form-xoa-sp-{{ $item->id }}">
                                        <i class="fa-solid fa-trash me-2"></i> Xoá
                                    </button>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="10" class="text-center py-4 text-muted">Chưa có dữ liệu sản phẩm trang phục.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <x-pagination-info :paginator="$danhSach ?? null" label="sản phẩm" />
    </div>
</div>

{{-- Modal Thêm mới sản phẩm --}}
<div class="modal fade" id="modalThemSanPham" tabindex="-1" aria-labelledby="modalThemSanPhamLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-san-pham">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalThemSanPhamLabel">Thêm sản phẩm trang phục mới</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
            </div>
            <form action="{{ route('admin.trang-phuc.san-pham.store') }}" method="POST" id="formThemSanPham">
                @csrf
                @if($errors->any())
                <div class="modal-body py-0">
                    <div class="alert alert-danger">
                        <ul class="mb-0 list-unstyled">
                            @foreach($errors->all() as $err)
                            <li>{{ $err }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                @endif
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-12 col-sm-6 col-lg-4">
                            <label class="form-label" for="them_ten_san_pham">Tên sản phẩm <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="them_ten_san_pham" name="ten_san_pham" value="{{ old('ten_san_pham') }}" placeholder="Nhập tên sản phẩm" required>
                        </div>
                        <div class="col-12 col-sm-6 col-lg-4">
                            <label class="form-label" for="them_ma_san_pham">Mã sản phẩm <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="them_ma_san_pham" name="ma_san_pham" value="{{ old('ma_san_pham') }}" placeholder="Ví dụ: TP001" required>
                        </div>
                        <div class="col-12 col-sm-6 col-lg-4">
                            <label class="form-label" for="them_slug">Slug</label>
                            <input type="text" class="form-control" id="them_slug" name="slug" value="{{ old('slug') }}" placeholder="Tự tạo từ tên nếu bỏ trống">
                        </div>
                        <div class="col-12 col-sm-6 col-lg-4">
                            <label class="form-label" for="them_nha_cung_cap">Nhà cung cấp</label>
                            <input type="text" class="form-control" id="them_nha_cung_cap" name="nha_cung_cap" value="{{ old('nha_cung_cap') }}" placeholder="Ví dụ: Nhà may A...">
                        </div>
                        <div class="col-12 col-sm-6 col-lg-4">
                            <label class="form-label" for="them_trang_thai">Trạng thái</label>
                            <select class="select2-admin form-select" id="them_trang_thai" name="trang_thai" data-placeholder="Chọn trạng thái">
                                <option value="1" {{ old('trang_thai', '1') == '1' ? 'selected' : '' }}>Hiển thị</option>
                                <option value="0" {{ old('trang_thai') == '0' ? 'selected' : '' }}>Ẩn</option>
                            </select>
                        </div>
                        <div class="col-12 col-sm-6 col-lg-4">
                            <label class="form-label" for="them_gia_tri">Giá trị</label>
                            <input type="number" class="form-control" id="them_gia_tri" name="gia_tri" value="{{ old('gia_tri') }}" placeholder="0" min="0" step="0.01">
                        </div>
                        <div class="col-12">
                            <label class="form-label" for="them_mo_ta">Mô tả</label>
                            <textarea class="form-control" id="them_mo_ta" name="mo_ta" rows="2" placeholder="Mô tả ngắn...">{{ old('mo_ta') }}</textarea>
                        </div>
                        <div class="col-12">
                            <label class="form-label" for="them_ghi_chu">Ghi chú</label>
                            <textarea class="form-control" id="them_ghi_chu" name="ghi_chu" rows="2" placeholder="Ghi chú...">{{ old('ghi_chu') }}</textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fa-solid fa-floppy-disk me-1"></i> Lưu
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Modal Chỉnh sửa sản phẩm --}}
<div class="modal fade" id="modalSuaSanPham" tabindex="-1" aria-labelledby="modalSuaSanPhamLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-san-pham">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalSuaSanPhamLabel">Chỉnh sửa sản phẩm trang phục</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
            </div>
            <form id="formSuaSanPham" method="POST" action="">
                @csrf
                @method('PUT')
                @if($errors->any())
                <div class="modal-body py-0">
                    <div class="alert alert-danger">
                        <ul class="mb-0 list-unstyled">
                            @foreach($errors->all() as $err)
                            <li>{{ $err }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                @endif
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-12 col-sm-6 col-lg-4">
                            <label class="form-label" for="sua_ten_san_pham">Tên sản phẩm <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="sua_ten_san_pham" name="ten_san_pham" placeholder="Nhập tên sản phẩm" required>
                        </div>
                        <div class="col-12 col-sm-6 col-lg-4">
                            <label class="form-label" for="sua_ma_san_pham">Mã sản phẩm <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="sua_ma_san_pham" name="ma_san_pham" placeholder="Ví dụ: TP001" required>
                        </div>
                        <div class="col-12 col-sm-6 col-lg-4">
                            <label class="form-label" for="sua_slug">Slug</label>
                            <input type="text" class="form-control" id="sua_slug" name="slug" placeholder="Tự tạo từ tên nếu bỏ trống">
                        </div>
                        <div class="col-12 col-sm-6 col-lg-4">
                            <label class="form-label" for="sua_nha_cung_cap">Nhà cung cấp</label>
                            <input type="text" class="form-control" id="sua_nha_cung_cap" name="nha_cung_cap" placeholder="Ví dụ: Nhà may A...">
                        </div>
                        <div class="col-12 col-sm-6 col-lg-4">
                            <label class="form-label" for="sua_trang_thai">Trạng thái</label>
                            <select class="select2-admin form-select" id="sua_trang_thai" name="trang_thai" data-placeholder="Chọn trạng thái">
                                <option value="1">Hiển thị</option>
                                <option value="0">Ẩn</option>
                            </select>
                        </div>
                        <div class="col-12 col-sm-6 col-lg-4">
                            <label class="form-label" for="sua_gia_tri">Giá trị</label>
                            <input type="number" class="form-control" id="sua_gia_tri" name="gia_tri" placeholder="0" min="0" step="0.01">
                        </div>
                        <div class="col-12">
                            <label class="form-label" for="sua_mo_ta">Mô tả</label>
                            <textarea class="form-control" id="sua_mo_ta" name="mo_ta" rows="2" placeholder="Mô tả ngắn..."></textarea>
                        </div>
                        <div class="col-12">
                            <label class="form-label" for="sua_ghi_chu">Ghi chú</label>
                            <textarea class="form-control" id="sua_ghi_chu" name="ghi_chu" rows="2" placeholder="Ghi chú..."></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fa-solid fa-floppy-disk me-1"></i> Lưu
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Modal xác nhận xóa sản phẩm --}}
<div class="modal fade" id="modalXacNhanXoaSanPham" tabindex="-1" aria-labelledby="modalXacNhanXoaSanPhamLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-confirm-xoa">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalXacNhanXoaSanPhamLabel">Xác nhận xóa</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
            </div>
            <div class="modal-body">
                Bạn có chắc muốn xóa sản phẩm trang phục này?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Hủy</button>
                <button type="button" class="btn btn-danger" id="btnXacNhanXoaSanPham">
                    <i class="fa-solid fa-trash me-1"></i> Xóa
                </button>
            </div>
        </div>
    </div>
</div>

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" crossorigin="anonymous" />
<style>
.table-wrapper-bordered {
    border: 1px solid var(--bs-border-color, #dee2e6);
    border-radius: 0.375rem;
    overflow-x: auto;
    overflow-y: visible;
    -webkit-overflow-scrolling: touch;
}
.table-wrapper-bordered .table {
    border-collapse: collapse;
    min-width: 1200px;
}
.table-wrapper-bordered .table th,
.table-wrapper-bordered .table td {
    border: 1px solid var(--bs-border-color, #dee2e6);
}
#modalThemSanPham .modal-san-pham,
#modalSuaSanPham .modal-san-pham {
    max-width: 90vw;
    width: 900px;
}
#modalXacNhanXoaSanPham .modal-confirm-xoa {
    max-width: 90vw;
    width: 400px;
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    var slugify = function(str) {
        if (!str) return '';
        var s = String(str).trim().toLowerCase();
        try {
            s = s.normalize('NFD').replace(/[\u0300-\u036f]/g, '');
        } catch (e) {}
        s = s.replace(/đ/g, 'd').replace(/Đ/g, 'd');
        s = s.replace(/[^a-z0-9]+/g, '-').replace(/-+/g, '-').replace(/^-|-$/g, '');
        return s;
    };

    var bindAutoSlug = function(nameInput, slugInput) {
        if (!nameInput || !slugInput) return;

        var markManual = function() {
            slugInput.dataset.manuallyEdited = '1';
        };

        var isManual = function() {
            return slugInput.dataset.manuallyEdited === '1';
        };

        nameInput.addEventListener('input', function() {
            if (isManual()) return;
            slugInput.value = slugify(nameInput.value);
        });

        slugInput.addEventListener('input', function() {
            if (slugInput.value && slugInput.value.trim() !== '') {
                markManual();
            } else {
                slugInput.dataset.manuallyEdited = '0';
            }
        });
    };

    // Tooltip
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (el) { return new bootstrap.Tooltip(el); });

    // Mở modal Thêm khi có lỗi validation (sau redirect)
    @if($errors->any())
    var modalThem = document.getElementById('modalThemSanPham');
    if (modalThem) {
        var m = new bootstrap.Modal(modalThem);
        m.show();
    }
    @endif

    // Auto slug: Thêm mới
    var themTen = document.getElementById('them_ten_san_pham');
    var themSlug = document.getElementById('them_slug');
    if (themSlug) {
        themSlug.dataset.manuallyEdited = (themSlug.value && themSlug.value.trim() !== '') ? '1' : '0';
    }
    bindAutoSlug(themTen, themSlug);

    // Modal Sửa: gán data vào form
    var modalSua = document.getElementById('modalSuaSanPham');
    var formSua = document.getElementById('formSuaSanPham');
    if (modalSua && formSua) {
        modalSua.addEventListener('show.bs.modal', function(e) {
            var btn = e.relatedTarget;
            if (!btn || !btn.classList.contains('btn-sua-san-pham')) return;
            var url = btn.getAttribute('data-url');
            if (url) formSua.action = url;
            document.getElementById('sua_ten_san_pham').value = btn.getAttribute('data-ten') || '';
            document.getElementById('sua_ma_san_pham').value = btn.getAttribute('data-ma') || '';
            var suaSlugEl = document.getElementById('sua_slug');
            if (suaSlugEl) {
                suaSlugEl.value = btn.getAttribute('data-slug') || '';
                // Modal sửa: luôn bật auto slug theo tên khi vừa mở modal
                suaSlugEl.dataset.manuallyEdited = '0';
            }
            document.getElementById('sua_nha_cung_cap').value = btn.getAttribute('data-nha-cung-cap') || '';
            document.getElementById('sua_mo_ta').value = btn.getAttribute('data-mo-ta') || '';
            document.getElementById('sua_trang_thai').value = btn.getAttribute('data-trang-thai') || '1';
            document.getElementById('sua_ghi_chu').value = btn.getAttribute('data-ghi-chu') || '';
            document.getElementById('sua_gia_tri').value = btn.getAttribute('data-gia-tri') || '';
        });
    }

    // Auto slug: Sửa
    bindAutoSlug(document.getElementById('sua_ten_san_pham'), document.getElementById('sua_slug'));

    // Xóa: mở modal xác nhận, sau đó submit form
    var modalXoa = document.getElementById('modalXacNhanXoaSanPham');
    var btnXacNhanXoa = document.getElementById('btnXacNhanXoaSanPham');
    var formIdCanXoa = null;
    if (modalXoa && btnXacNhanXoa) {
        modalXoa.addEventListener('hidden.bs.modal', function() {
            document.querySelectorAll('.modal-backdrop').forEach(function(el) { el.remove(); });
            document.body.classList.remove('modal-open');
            document.body.style.removeProperty('overflow');
            document.body.style.removeProperty('padding-right');
        });
        document.querySelectorAll('.btn-xoa-san-pham').forEach(function(btn) {
            btn.addEventListener('click', function() {
                formIdCanXoa = this.getAttribute('data-form-id');
                if (!formIdCanXoa) return;
                var modal = bootstrap.Modal.getOrCreateInstance(modalXoa);
                modal.show();
            });
        });
        btnXacNhanXoa.addEventListener('click', function() {
            if (formIdCanXoa) {
                var form = document.getElementById(formIdCanXoa);
                if (form) form.submit();
            }
            var inst = bootstrap.Modal.getInstance(modalXoa);
            if (inst) inst.hide();
            formIdCanXoa = null;
        });
    }
});
</script>
@endpush
@endsection

