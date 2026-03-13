@extends('admin.layouts.app')

@section('content')
<div class="card">
    <h5 class="card-header">Kho hàng trang phục</h5>
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
        <form action="{{ route('admin.trang-phuc.kho-hang') }}" method="GET" class="mb-4">
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
                    <a href="{{ route('admin.trang-phuc.kho-hang') }}" class="btn btn-outline-secondary">Bỏ lọc</a>
                    @endif
                </div>
                <div class="col-auto ms-md-auto">
                    <span data-bs-toggle="tooltip" title="Thêm sản phẩm vào kho">
                        <button type="button"
                                class="btn btn-primary"
                                data-bs-toggle="modal"
                                data-bs-target="#modalThemKhoHang"
                                @if(($sanPhamChuaCoTrongKho ?? collect())->isEmpty()) disabled title="Tất cả sản phẩm đã có trong kho" @endif>
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
                        <th>Sản phẩm</th>
                        <th>Mã sản phẩm</th>
                        <th class="text-center" style="width: 140px;">Số lượng</th>
                        <th class="text-end" style="width: 120px;">Giá cho thuê</th>
                        <th>Ghi chú</th>
                        <th class="text-center" style="width: 90px;">Trạng thái</th>
                        <th class="text-center" style="width: 100px;">Thao tác</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @forelse($danhSach ?? [] as $index => $item)
                    @php
                        $tp = $item->trangPhuc;
                        $isActive = (int)($item->trang_thai ?? 0) === 1;
                        $trangThaiLabel = $isActive ? 'Hoạt động' : 'Tạm dừng';
                        $trangThaiBadge = $isActive ? 'bg-label-success' : 'bg-label-secondary';
                    @endphp
                    <tr>
                        <td>{{ ($danhSach->currentPage() - 1) * $danhSach->perPage() + $index + 1 }}</td>
                        <td><span class="fw-medium">{{ $tp->ten_san_pham ?? '—' }}</span></td>
                        <td>{{ $tp->ma_san_pham ?? '—' }}</td>
                        <td class="text-center p-1">
                            <div class="input-group input-group-sm so-luong-input-group">
                                <input type="number"
                                       class="form-control input-so-luong-nhanh text-center"
                                       value="{{ $item->so_luong ?? 0 }}"
                                       min="0"
                                       data-url="{{ route('admin.trang-phuc.update-kho-hang', $item) }}"
                                       data-gia-cho-thue="{{ $item->gia_cho_thue ?? '' }}"
                                       data-ghi-chu="{{ e($item->ghi_chu ?? '') }}"
                                       data-trang-thai="{{ $item->trang_thai ?? 1 }}"
                                       data-initial="{{ $item->so_luong ?? 0 }}"
                                       title="Nhập số lượng rồi bấm Lưu để cập nhật">
                                <button type="button"
                                        class="btn btn-outline-primary btn-save-so-luong"
                                        disabled
                                        aria-disabled="true"
                                        title="Lưu số lượng">
                                    <i class="icon-base ti tabler-device-floppy"></i>
                                </button>
                            </div>
                        </td>
                        <td class="text-end">
                            {{ $item->gia_cho_thue !== null ? number_format((float)$item->gia_cho_thue, 0, ',', '.') . ' đ' : '—' }}
                        </td>
                        <td>{{ \Illuminate\Support\Str::limit($item->ghi_chu ?? '—', 40) }}</td>
                        <td class="text-center">
                            <span class="badge {{ $trangThaiBadge }}">{{ $trangThaiLabel }}</span>
                        </td>
                        <td>
                            <div class="dropdown">
                                <button type="button" class="btn btn-sm btn-icon btn-outline-secondary dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                    <i class="fa-solid fa-ellipsis-vertical"></i>
                                </button>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item btn-sua-kho-hang"
                                       href="javascript:void(0);"
                                       data-bs-toggle="modal"
                                       data-bs-target="#modalSuaKhoHang"
                                       data-url="{{ route('admin.trang-phuc.update-kho-hang', $item) }}"
                                       data-so-luong="{{ $item->so_luong ?? 0 }}"
                                       data-gia-cho-thue="{{ $item->gia_cho_thue ?? '' }}"
                                       data-ghi-chu="{{ e($item->ghi_chu ?? '') }}"
                                       data-trang-thai="{{ $item->trang_thai ?? 1 }}">
                                        <i class="fa-solid fa-pen me-2"></i> Sửa
                                    </a>
                                    <form id="form-xoa-kh-{{ $item->id }}" action="{{ route('admin.trang-phuc.destroy-kho-hang', $item) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                    <button type="button" class="dropdown-item text-danger btn-xoa-kho-hang" data-form-id="form-xoa-kh-{{ $item->id }}">
                                        <i class="fa-solid fa-trash me-2"></i> Xoá
                                    </button>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-4 text-muted">Chưa có sản phẩm nào trong kho.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <x-pagination-info :paginator="$danhSach ?? null" label="mục kho hàng" />
    </div>
</div>

{{-- Toast thông báo --}}
<div class="toast-container position-fixed top-0 end-0 p-3 kho-hang-toast-container">
    <div id="toastKhoHang"
         class="toast align-items-center text-bg-success border-0"
         role="alert"
         aria-live="assertive"
         aria-atomic="true">
        <div class="d-flex">
            <div class="toast-body" id="toastKhoHangBody">—</div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Đóng"></button>
        </div>
    </div>
</div>

{{-- Modal Thêm mới vào kho --}}
<div class="modal fade" id="modalThemKhoHang" tabindex="-1" aria-labelledby="modalThemKhoHangLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-kho-hang">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalThemKhoHangLabel">Thêm sản phẩm vào kho</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
            </div>
            <form action="{{ route('admin.trang-phuc.store-kho-hang') }}" method="POST" id="formThemKhoHang">
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
                        <div class="col-12">
                            <label class="form-label" for="them_trang_phuc_id">Chọn sản phẩm <span class="text-danger">*</span></label>
                            <select class="select2-admin form-select" id="them_trang_phuc_id" name="trang_phuc_id" required data-placeholder="Chọn sản phẩm">
                                <option value="">-- Chọn sản phẩm --</option>
                                @foreach($sanPhamChuaCoTrongKho ?? [] as $sp)
                                <option value="{{ $sp->id }}" {{ (string)old('trang_phuc_id') === (string)$sp->id ? 'selected' : '' }}>
                                    {{ $sp->ten_san_pham }} ({{ $sp->ma_san_pham }})
                                </option>
                                @endforeach
                            </select>
                            <small class="text-muted">Chỉ hiển thị sản phẩm chưa có trong kho. Nếu sản phẩm đã có trong kho, vui lòng dùng chức năng Sửa để cập nhật số lượng.</small>
                        </div>
                        <div class="col-12 col-md-4">
                            <label class="form-label" for="them_so_luong">Số lượng <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="them_so_luong" name="so_luong" value="{{ old('so_luong', 0) }}" placeholder="0" min="0" required>
                        </div>
                        <div class="col-12 col-md-4">
                            <label class="form-label" for="them_gia_cho_thue">Giá cho thuê</label>
                            <input type="number" class="form-control" id="them_gia_cho_thue" name="gia_cho_thue" value="{{ old('gia_cho_thue') }}" placeholder="0" min="0" step="0.01">
                        </div>
                        <div class="col-12 col-md-4">
                            <label class="form-label" for="them_trang_thai">Trạng thái</label>
                            <select class="select2-admin form-select" id="them_trang_thai" name="trang_thai" data-placeholder="Chọn trạng thái">
                                <option value="1" {{ old('trang_thai', '1') == '1' ? 'selected' : '' }}>Hoạt động</option>
                                <option value="0" {{ old('trang_thai') == '0' ? 'selected' : '' }}>Tạm dừng</option>
                            </select>
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

{{-- Modal Chỉnh sửa kho hàng --}}
<div class="modal fade" id="modalSuaKhoHang" tabindex="-1" aria-labelledby="modalSuaKhoHangLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-kho-hang">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalSuaKhoHangLabel">Cập nhật số lượng & thông tin kho</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
            </div>
            <form id="formSuaKhoHang" method="POST" action="">
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
                        <div class="col-12 col-md-4">
                            <label class="form-label" for="sua_so_luong">Số lượng <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="sua_so_luong" name="so_luong" placeholder="0" min="0" required>
                        </div>
                        <div class="col-12 col-md-4">
                            <label class="form-label" for="sua_gia_cho_thue">Giá cho thuê</label>
                            <input type="number" class="form-control" id="sua_gia_cho_thue" name="gia_cho_thue" placeholder="0" min="0" step="0.01">
                        </div>
                        <div class="col-12 col-md-4">
                            <label class="form-label" for="sua_trang_thai">Trạng thái</label>
                            <select class="select2-admin form-select" id="sua_trang_thai" name="trang_thai" data-placeholder="Chọn trạng thái">
                                <option value="1">Hoạt động</option>
                                <option value="0">Tạm dừng</option>
                            </select>
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

{{-- Modal xác nhận xóa --}}
<div class="modal fade" id="modalXacNhanXoaKhoHang" tabindex="-1" aria-labelledby="modalXacNhanXoaKhoHangLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-confirm-xoa">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalXacNhanXoaKhoHangLabel">Xác nhận xóa</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
            </div>
            <div class="modal-body">
                Bạn có chắc muốn xóa sản phẩm này khỏi kho hàng?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Hủy</button>
                <button type="button" class="btn btn-danger" id="btnXacNhanXoaKhoHang">
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
.input-so-luong-nhanh {
    width: 70px;
    display: inline-block;
}
.so-luong-input-group {
    width: 120px;
    margin: 0 auto;
}
.btn-save-so-luong {
    line-height: 1;
}
.kho-hang-toast-container {
    z-index: 2000;
}
#modalThemKhoHang .modal-kho-hang {
    max-width: 90vw;
    width: 720px;
}
#modalSuaKhoHang .modal-kho-hang {
    max-width: 90vw;
    width: 720px;
}
#modalXacNhanXoaKhoHang .modal-confirm-xoa {
    max-width: 90vw;
    width: 400px;
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Tooltip
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (el) { return new bootstrap.Tooltip(el); });

    // Toast helper
    var toastEl = document.getElementById('toastKhoHang');
    var toastBodyEl = document.getElementById('toastKhoHangBody');
    var toastInstance = toastEl ? bootstrap.Toast.getOrCreateInstance(toastEl, { delay: 2500 }) : null;
    function showToast(message, variant) {
        if (!toastEl || !toastBodyEl || !toastInstance) {
            // fallback
            alert(message);
            return;
        }
        variant = variant || 'success';
        toastEl.classList.remove('text-bg-success', 'text-bg-danger', 'text-bg-warning', 'text-bg-info', 'text-bg-secondary');
        toastEl.classList.add('text-bg-' + variant);
        toastBodyEl.textContent = message || '';
        toastInstance.show();
    }

    // Mở modal Thêm khi có lỗi validation (sau redirect)
    @if($errors->any())
    var modalThem = document.getElementById('modalThemKhoHang');
    if (modalThem) {
        var m = new bootstrap.Modal(modalThem);
        m.show();
    }
    @endif

    // Modal Sửa: gán data vào form
    var modalSua = document.getElementById('modalSuaKhoHang');
    var formSua = document.getElementById('formSuaKhoHang');
    if (modalSua && formSua) {
        modalSua.addEventListener('show.bs.modal', function(e) {
            var btn = e.relatedTarget;
            if (!btn || !btn.classList.contains('btn-sua-kho-hang')) return;
            var url = btn.getAttribute('data-url');
            if (url) formSua.action = url;
            document.getElementById('sua_so_luong').value = btn.getAttribute('data-so-luong') || '0';
            document.getElementById('sua_gia_cho_thue').value = btn.getAttribute('data-gia-cho-thue') || '';
            document.getElementById('sua_ghi_chu').value = btn.getAttribute('data-ghi-chu') || '';
            document.getElementById('sua_trang_thai').value = btn.getAttribute('data-trang-thai') || '1';
        });
    }

    // Xóa: mở modal xác nhận, sau đó submit form
    var modalXoa = document.getElementById('modalXacNhanXoaKhoHang');
    var btnXacNhanXoa = document.getElementById('btnXacNhanXoaKhoHang');
    var formIdCanXoa = null;
    if (modalXoa && btnXacNhanXoa) {
        modalXoa.addEventListener('hidden.bs.modal', function() {
            document.querySelectorAll('.modal-backdrop').forEach(function(el) { el.remove(); });
            document.body.classList.remove('modal-open');
            document.body.style.removeProperty('overflow');
            document.body.style.removeProperty('padding-right');
        });
        document.querySelectorAll('.btn-xoa-kho-hang').forEach(function(btn) {
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

    // Chỉnh sửa nhanh số lượng: chỉ lưu khi bấm nút "Lưu"
    var csrfToken = document.querySelector('meta[name="csrf-token"]') && document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        || document.querySelector('input[name="_token"]') && document.querySelector('input[name="_token"]').value
        || '{{ csrf_token() }}';
    function setSaveEnabled(btn, enabled) {
        if (!btn) return;
        btn.disabled = !enabled;
        btn.setAttribute('aria-disabled', enabled ? 'false' : 'true');
    }
    function setSaveButtonState(input) {
        var cell = input && input.closest('td');
        var btn = cell && cell.querySelector('.btn-save-so-luong');
        if (!btn || !input) return;
        var value = parseInt(input.value, 10);
        if (isNaN(value) || value < 0) value = 0;
        var initial = parseInt(input.getAttribute('data-initial'), 10);
        setSaveEnabled(btn, initial !== value);
    }
    function saveSoLuongNhanh(input, btn) {
        if (!input || !input.classList.contains('input-so-luong-nhanh')) return;
        var value = parseInt(input.value, 10);
        if (isNaN(value) || value < 0) value = 0;
        var initial = parseInt(input.getAttribute('data-initial'), 10);
        if (initial === value) return;
        var url = input.getAttribute('data-url');
        if (!url || !csrfToken) return;
        setSaveEnabled(btn, false);
        input.disabled = true;
        var body = new URLSearchParams({
            _token: csrfToken,
            _method: 'PUT',
            so_luong: value,
            gia_cho_thue: input.getAttribute('data-gia-cho-thue') || '0',
            ghi_chu: input.getAttribute('data-ghi-chu') || '',
            trang_thai: input.getAttribute('data-trang-thai') || '1'
        });
        fetch(url, {
            method: 'POST',
            headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json', 'Content-Type': 'application/x-www-form-urlencoded' },
            body: body
        }).then(function(r) {
            if (r.ok) {
                input.setAttribute('data-initial', value);
                input.value = value;
                var row = input.closest('tr');
                if (row) {
                    var btnSua = row.querySelector('.btn-sua-kho-hang');
                    if (btnSua) btnSua.setAttribute('data-so-luong', value);
                }
                setSaveButtonState(input);
                showToast('Đã cập nhật số lượng thành công.', 'success');
            } else {
                input.value = input.getAttribute('data-initial');
                if (r.status === 422) r.json().then(function(d) { showToast(d.message || (d.errors && Object.values(d.errors).flat().join('\n')) || 'Có lỗi xảy ra.', 'danger'); });
                else showToast('Có lỗi xảy ra. Vui lòng thử lại.', 'danger');
                setSaveButtonState(input);
            }
        }).catch(function() {
            input.value = input.getAttribute('data-initial');
            showToast('Không thể kết nối. Vui lòng thử lại.', 'danger');
            setSaveButtonState(input);
        }).finally(function() {
            input.disabled = false;
            setSaveButtonState(input);
        });
    }

    // Enable nút Lưu khi input thay đổi
    document.addEventListener('input', function(e) {
        if (e.target && e.target.classList.contains('input-so-luong-nhanh')) {
            setSaveButtonState(e.target);
        }
    });
    document.addEventListener('change', function(e) {
        if (e.target && e.target.classList.contains('input-so-luong-nhanh')) {
            setSaveButtonState(e.target);
        }
    });

    // Click nút Lưu để cập nhật
    document.addEventListener('click', function(e) {
        var btn = e.target && (e.target.classList.contains('btn-save-so-luong') ? e.target : e.target.closest && e.target.closest('.btn-save-so-luong'));
        if (!btn) return;
        if (btn.disabled) return;
        var cell = btn.closest('td');
        var input = cell && cell.querySelector('.input-so-luong-nhanh');
        if (!input) return;
        saveSoLuongNhanh(input, btn);
    });
});
</script>
@endpush
@endsection
