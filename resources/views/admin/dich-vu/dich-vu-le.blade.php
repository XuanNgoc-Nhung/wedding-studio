@extends('admin.layouts.app')

@section('content')
<div class="card">
    <h5 class="card-header">Danh sách dịch vụ lẻ</h5>
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
        <form action="{{ route('admin.dich-vu.dich-vu-le') }}" method="GET" class="mb-4">
            <div class="row g-3 align-items-end">
                <div class="col-md-6 col-lg-4">
                    <label class="form-label" for="search">Tên hoặc mã dịch vụ</label>
                    <input type="text"
                           class="form-control"
                           id="search"
                           name="search"
                           value="{{ request('search') }}"
                           placeholder="Nhập tên hoặc mã dịch vụ...">
                </div>
                <div class="col-auto">
                    <button type="submit" class="btn btn-primary">
                        <i class="fa-solid fa-magnifying-glass me-1"></i> Lọc
                    </button>
                    @if(request('search'))
                    <a href="{{ route('admin.dich-vu.dich-vu-le') }}" class="btn btn-outline-secondary">Bỏ lọc</a>
                    @endif
                </div>
                <div class="col-auto ms-md-auto">
                    <span data-bs-toggle="tooltip" title="Thêm dịch vụ lẻ mới">
                        <button type="button"
                                class="btn btn-primary"
                                data-bs-toggle="modal"
                                data-bs-target="#modalThemDichVuLe">
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
                        <th>Tên dịch vụ</th>
                        <th>Mã dịch vụ</th>
                        <th>Phòng ban</th>
                        <th>Mô tả</th>
                        <th class="text-center" style="width: 100px;">Trạng thái</th>
                        <th>Ghi chú</th>
                        <th class="text-end" style="width: 120px;">Giá dịch vụ</th>
                        <th>Người tạo</th>
                        <th class="text-center" style="width: 100px;">Thao tác</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @forelse($danhSach ?? [] as $index => $item)
                    @php
                        $trangThaiLabel = ($item->trang_thai ?? 0) == \App\Models\DichVuLe::TRANG_THAI_HIEN_THI ? 'Hiển thị' : 'Ẩn';
                        $trangThaiBadge = ($item->trang_thai ?? 0) == \App\Models\DichVuLe::TRANG_THAI_HIEN_THI ? 'bg-label-success' : 'bg-label-secondary';
                    @endphp
                    <tr>
                        <td>{{ ($danhSach->currentPage() - 1) * $danhSach->perPage() + $index + 1 }}</td>
                        <td><span class="fw-medium">{{ $item->ten_dich_vu ?? '—' }}</span></td>
                        <td>{{ $item->ma_dich_vu ?? '—' }}</td>
                        <td>{{ $item->phongBan?->ten_phong_ban ?? '—' }}</td>
                        <td>{{ \Illuminate\Support\Str::limit($item->mo_ta ?? '—', 50) }}</td>
                        <td class="text-center">
                            <span class="badge {{ $trangThaiBadge }}">{{ $trangThaiLabel }}</span>
                        </td>
                        <td>{{ \Illuminate\Support\Str::limit($item->ghi_chu ?? '—', 40) }}</td>
                        <td class="text-end">{{ $item->gia_dich_vu !== null ? number_format($item->gia_dich_vu, 0, ',', '.') . ' đ' : '—' }}</td>
                        <td>{{ $item->nguoiTao?->name ?? '—' }}</td>
                        <td>
                            <div class="dropdown">
                                <button type="button" class="btn btn-sm btn-icon btn-outline-secondary dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                    <i class="fa-solid fa-ellipsis-vertical"></i>
                                </button>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item btn-sua-dich-vu-le"
                                       href="javascript:void(0);"
                                       data-bs-toggle="modal"
                                       data-bs-target="#modalSuaDichVuLe"
                                       data-url="{{ route('admin.dich-vu.update', $item) }}"
                                       data-ten="{{ e($item->ten_dich_vu ?? '') }}"
                                       data-ma="{{ e($item->ma_dich_vu ?? '') }}"
                                       data-mo-ta="{{ e($item->mo_ta ?? '') }}"
                                       data-trang-thai="{{ (int)($item->trang_thai ?? 0) }}"
                                       data-ghi-chu="{{ e($item->ghi_chu ?? '') }}"
                                       data-gia="{{ $item->gia_dich_vu ?? '' }}"
                                       data-phong-ban-id="{{ $item->phong_ban_id ?? '' }}">
                                        <i class="fa-solid fa-pen me-2"></i> Sửa
                                    </a>
                                    <form id="form-xoa-dv-{{ $item->id }}" action="{{ route('admin.dich-vu.destroy', $item) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                    <button type="button" class="dropdown-item text-danger btn-xoa-dich-vu-le" data-form-id="form-xoa-dv-{{ $item->id }}">
                                        <i class="fa-solid fa-trash me-2"></i> Xoá
                                    </button>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="10" class="text-center py-4 text-muted">Chưa có dữ liệu dịch vụ lẻ.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <x-pagination-info :paginator="$danhSach ?? null" label="dịch vụ lẻ" />
    </div>
</div>

{{-- Modal Thêm mới dịch vụ lẻ --}}
<div class="modal fade" id="modalThemDichVuLe" tabindex="-1" aria-labelledby="modalThemDichVuLeLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable modal-them-dich-vu-le">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalThemDichVuLeLabel">Thêm dịch vụ lẻ mới</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
            </div>
            <form action="{{ route('admin.dich-vu.store') }}" method="POST" id="formThemDichVuLe">
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
                        {{-- Hàng 1: 3 input (1 cột mobile → 2 cột tablet → 3 cột desktop) --}}
                        <div class="col-12 col-sm-6 col-lg-4">
                            <label class="form-label" for="them_ten_dich_vu">Tên dịch vụ <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="them_ten_dich_vu" name="ten_dich_vu" value="{{ old('ten_dich_vu') }}" placeholder="Nhập tên dịch vụ" required>
                        </div>
                        <div class="col-12 col-sm-6 col-lg-4">
                            <label class="form-label" for="them_ma_dich_vu">Mã dịch vụ <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="them_ma_dich_vu" name="ma_dich_vu" value="{{ old('ma_dich_vu') }}" placeholder="Ví dụ: DV001" required>
                        </div>
                        <div class="col-12 col-sm-6 col-lg-4">
                            <label class="form-label" for="them_phong_ban_id">Phòng ban phụ trách <span class="text-danger">*</span></label>
                            <select class="select2-admin form-select" id="them_phong_ban_id" name="phong_ban_id" required data-placeholder="Chọn phòng ban">
                                <option value="">— Chọn phòng ban —</option>
                                @foreach($phongBans ?? [] as $pb)
                                <option value="{{ $pb->id }}" {{ old('phong_ban_id') == $pb->id ? 'selected' : '' }}>{{ $pb->ten_phong_ban }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label" for="them_mo_ta">Mô tả</label>
                            <textarea class="form-control" id="them_mo_ta" name="mo_ta" rows="2" placeholder="Mô tả ngắn...">{{ old('mo_ta') }}</textarea>
                        </div>
                        {{-- Hàng 2: Trạng thái + Giá --}}
                        <div class="col-12 col-sm-6 col-lg-4">
                            <label class="form-label" for="them_trang_thai">Trạng thái</label>
                            <select class="select2-admin form-select" id="them_trang_thai" name="trang_thai" data-placeholder="Chọn trạng thái">
                                <option value="{{ \App\Models\DichVuLe::TRANG_THAI_HIEN_THI }}" {{ old('trang_thai', \App\Models\DichVuLe::TRANG_THAI_HIEN_THI) == \App\Models\DichVuLe::TRANG_THAI_HIEN_THI ? 'selected' : '' }}>Hiển thị</option>
                                <option value="{{ \App\Models\DichVuLe::TRANG_THAI_AN }}" {{ old('trang_thai') == \App\Models\DichVuLe::TRANG_THAI_AN ? 'selected' : '' }}>Ẩn</option>
                            </select>
                        </div>
                        <div class="col-12 col-sm-6 col-lg-4">
                            <label class="form-label" for="them_gia_dich_vu">Giá dịch vụ <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="them_gia_dich_vu" name="gia_dich_vu" value="{{ old('gia_dich_vu') }}" placeholder="0" min="0" step="1000" required>
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

{{-- Modal Chỉnh sửa dịch vụ lẻ --}}
<div class="modal fade" id="modalSuaDichVuLe" tabindex="-1" aria-labelledby="modalSuaDichVuLeLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-them-dich-vu-le">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalSuaDichVuLeLabel">Chỉnh sửa dịch vụ lẻ</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
            </div>
            <form id="formSuaDichVuLe" method="POST" action="">
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
                        {{-- Hàng 1: 3 input (1 cột mobile → 2 cột tablet → 3 cột desktop) --}}
                        <div class="col-12 col-sm-6 col-lg-4">
                            <label class="form-label" for="sua_ten_dich_vu">Tên dịch vụ <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="sua_ten_dich_vu" name="ten_dich_vu" placeholder="Nhập tên dịch vụ" required>
                        </div>
                        <div class="col-12 col-sm-6 col-lg-4">
                            <label class="form-label" for="sua_ma_dich_vu">Mã dịch vụ <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="sua_ma_dich_vu" name="ma_dich_vu" placeholder="Ví dụ: DV001" required>
                        </div>
                        <div class="col-12 col-sm-6 col-lg-4">
                            <label class="form-label" for="sua_phong_ban_id">Phòng ban phụ trách <span class="text-danger">*</span></label>
                            <select class="select2-admin form-select" id="sua_phong_ban_id" name="phong_ban_id" required data-placeholder="Chọn phòng ban">
                                <option value="">— Chọn phòng ban —</option>
                                @foreach($phongBans ?? [] as $pb)
                                <option value="{{ $pb->id }}">{{ $pb->ten_phong_ban }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label" for="sua_mo_ta">Mô tả</label>
                            <textarea class="form-control" id="sua_mo_ta" name="mo_ta" rows="2" placeholder="Mô tả ngắn..."></textarea>
                        </div>
                        {{-- Hàng 2: Trạng thái + Giá (cùng quy tắc responsive) --}}
                        <div class="col-12 col-sm-6 col-lg-4">
                            <label class="form-label" for="sua_trang_thai">Trạng thái</label>
                            <select class="select2-admin form-select" id="sua_trang_thai" name="trang_thai" data-placeholder="Chọn trạng thái">
                                <option value="{{ \App\Models\DichVuLe::TRANG_THAI_HIEN_THI }}">Hiển thị</option>
                                <option value="{{ \App\Models\DichVuLe::TRANG_THAI_AN }}">Ẩn</option>
                            </select>
                        </div>
                        <div class="col-12 col-sm-6 col-lg-4">
                            <label class="form-label" for="sua_gia_dich_vu">Giá dịch vụ <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="sua_gia_dich_vu" name="gia_dich_vu" placeholder="0" min="0" step="1000" required>
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

{{-- Modal xác nhận xóa dịch vụ lẻ --}}
<div class="modal fade" id="modalXacNhanXoaDichVuLe" tabindex="-1" aria-labelledby="modalXacNhanXoaDichVuLeLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-confirm-xoa">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalXacNhanXoaDichVuLeLabel">Xác nhận xóa</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
            </div>
            <div class="modal-body">
                Bạn có chắc muốn xóa dịch vụ lẻ này?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Hủy</button>
                <button type="button" class="btn btn-danger" id="btnXacNhanXoaDichVuLe">
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
    min-width: 900px;
}
.table-wrapper-bordered .table th,
.table-wrapper-bordered .table td {
    border: 1px solid var(--bs-border-color, #dee2e6);
}
#modalThemDichVuLe .modal-them-dich-vu-le,
#modalSuaDichVuLe .modal-them-dich-vu-le {
    max-width: 90vw;
    width: 50%;
}
#modalXacNhanXoaDichVuLe .modal-confirm-xoa {
    max-width: 90vw;
    width: 400px;
}
@media (min-width: 576px) {
    #modalThemDichVuLe .modal-them-dich-vu-le,
    #modalSuaDichVuLe .modal-them-dich-vu-le {
        /* max-width: 50%; */
    }
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (el) { return new bootstrap.Tooltip(el); });

    // Frontend validation: thông báo tiếng Việt cho trường bắt buộc
    var messages = {
        them_ma_dich_vu: 'Vui lòng nhập mã dịch vụ.',
        them_phong_ban_id: 'Vui lòng chọn phòng ban phụ trách.',
        them_gia_dich_vu: 'Vui lòng nhập giá dịch vụ.',
        sua_ma_dich_vu: 'Vui lòng nhập mã dịch vụ.',
        sua_phong_ban_id: 'Vui lòng chọn phòng ban phụ trách.',
        sua_gia_dich_vu: 'Vui lòng nhập giá dịch vụ.'
    };
    ['them_ma_dich_vu', 'them_phong_ban_id', 'them_gia_dich_vu', 'sua_ma_dich_vu', 'sua_phong_ban_id', 'sua_gia_dich_vu'].forEach(function(id) {
        var el = document.getElementById(id);
        if (!el) return;
        el.addEventListener('invalid', function() {
            if (this.validity.valueMissing) this.setCustomValidity(messages[id] || 'Vui lòng điền trường này.');
            else if (this.validity.rangeUnderflow && (id === 'them_gia_dich_vu' || id === 'sua_gia_dich_vu')) this.setCustomValidity('Giá dịch vụ không được âm.');
            else this.setCustomValidity('');
        });
        el.addEventListener('input', function() { this.setCustomValidity(''); });
        el.addEventListener('change', function() { this.setCustomValidity(''); });
    });

    @if($errors->any())
    var modalThem = document.getElementById('modalThemDichVuLe');
    if (modalThem) {
        var m = new bootstrap.Modal(modalThem);
        m.show();
    }
    @endif

    // Modal Sửa: gán data vào form
    var modalSua = document.getElementById('modalSuaDichVuLe');
    var formSua = document.getElementById('formSuaDichVuLe');
    if (modalSua && formSua) {
        modalSua.addEventListener('show.bs.modal', function(e) {
            var btn = e.relatedTarget;
            if (!btn || !btn.classList.contains('btn-sua-dich-vu-le')) return;
            var url = btn.getAttribute('data-url');
            if (url) formSua.action = url;
            document.getElementById('sua_ten_dich_vu').value = btn.getAttribute('data-ten') || '';
            document.getElementById('sua_ma_dich_vu').value = btn.getAttribute('data-ma') || '';
            document.getElementById('sua_mo_ta').value = btn.getAttribute('data-mo-ta') || '';
            document.getElementById('sua_trang_thai').value = btn.getAttribute('data-trang-thai') || '{{ \App\Models\DichVuLe::TRANG_THAI_HIEN_THI }}';
            document.getElementById('sua_ghi_chu').value = btn.getAttribute('data-ghi-chu') || '';
            document.getElementById('sua_gia_dich_vu').value = btn.getAttribute('data-gia') || '';
            document.getElementById('sua_phong_ban_id').value = btn.getAttribute('data-phong-ban-id') || '';
        });
    }

    // Xóa: mở modal Bootstrap xác nhận, sau đó submit form
    var modalXoaDv = document.getElementById('modalXacNhanXoaDichVuLe');
    var btnXacNhanXoaDv = document.getElementById('btnXacNhanXoaDichVuLe');
    var formIdCanXoa = null;
    if (modalXoaDv && btnXacNhanXoaDv) {
        modalXoaDv.addEventListener('hidden.bs.modal', function() {
            document.querySelectorAll('.modal-backdrop').forEach(function(el) { el.remove(); });
            document.body.classList.remove('modal-open');
            document.body.style.removeProperty('overflow');
            document.body.style.removeProperty('padding-right');
        });
        document.querySelectorAll('.btn-xoa-dich-vu-le').forEach(function(btn) {
            btn.addEventListener('click', function() {
                formIdCanXoa = this.getAttribute('data-form-id');
                if (!formIdCanXoa) return;
                var modal = bootstrap.Modal.getOrCreateInstance(modalXoaDv);
                modal.show();
            });
        });
        btnXacNhanXoaDv.addEventListener('click', function() {
            if (formIdCanXoa) {
                var form = document.getElementById(formIdCanXoa);
                if (form) form.submit();
            }
            var inst = bootstrap.Modal.getInstance(modalXoaDv);
            if (inst) inst.hide();
            formIdCanXoa = null;
        });
    }
});
</script>
@endpush
@endsection
