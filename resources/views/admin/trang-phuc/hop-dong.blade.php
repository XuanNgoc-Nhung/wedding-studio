@extends('admin.layouts.app')

@section('content')
<div class="card">
    <h5 class="card-header">Danh sách hợp đồng thuê trang phục</h5>
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
        <form action="{{ route('admin.trang-phuc.hop-dong') }}" method="GET" class="mb-4">
            <div class="row g-3 align-items-end">
                <div class="col-md-6 col-lg-4">
                    <label class="form-label" for="search">Tên khách hàng hoặc SĐT</label>
                    <input type="text"
                           class="form-control"
                           id="search"
                           name="search"
                           value="{{ request('search') }}"
                           placeholder="Nhập tên hoặc số điện thoại...">
                </div>
                <div class="col-auto">
                    <button type="submit" class="btn btn-primary">
                        <i class="fa-solid fa-magnifying-glass me-1"></i> Lọc
                    </button>
                    @if(request('search'))
                    <a href="{{ route('admin.trang-phuc.hop-dong') }}" class="btn btn-outline-secondary">Bỏ lọc</a>
                    @endif
                </div>
                <div class="col-auto ms-md-auto">
                    <span data-bs-toggle="tooltip" title="Thêm hợp đồng mới">
                        <button type="button"
                                class="btn btn-primary"
                                data-bs-toggle="modal"
                                data-bs-target="#modalThemHopDong">
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
                        <th>Tên khách hàng</th>
                        <th>Số điện thoại</th>
                        <th class="text-center" style="width: 90px;">ID sản phẩm</th>
                        <th class="text-center" style="width: 90px;">Số lượng thuê</th>
                        <th class="text-end" style="width: 100px;">Giá thuê</th>
                        <th>Thời gian thuê (bắt đầu)</th>
                        <th>Thời gian dự kiến trả</th>
                        <th>Thời gian trả thực tế</th>
                        <th>Ghi chú</th>
                        <th class="text-center" style="width: 100px;">Trạng thái</th>
                        <th class="text-center" style="width: 110px;">Tình trạng</th>
                        <th>Người cho thuê</th>
                        <th class="text-center" style="width: 100px;">Thao tác</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @forelse($danhSach ?? [] as $index => $item)
                    @php
                        $trangThaiLabels = [
                            'moi' => 'Mới',
                            'dang_thue' => 'Đang thuê',
                            'hoan_thanh' => 'Hoàn thành',
                        ];
                        $trangThaiBadges = [
                            'moi' => 'bg-label-warning',
                            'dang_thue' => 'bg-label-info',
                            'hoan_thanh' => 'bg-label-success',
                        ];
                        $tt = (string)($item->trang_thai ?? 'moi');
                        $trangThaiLabel = $trangThaiLabels[$tt] ?? $tt;
                        $trangThaiBadge = $trangThaiBadges[$tt] ?? 'bg-label-secondary';
                        $duKienTra = $item->ngay_tra_du_kien;
                        $tinhTrangPhu = '—';
                        $tinhTrangBadge = '';
                        if ($tt === 'dang_thue' && $duKienTra) {
                            if ($duKienTra->isToday()) {
                                $tinhTrangPhu = 'Đến lịch trả đồ';
                                $tinhTrangBadge = 'bg-label-warning';
                            } elseif ($duKienTra->format('Y-m-d') < now()->format('Y-m-d')) {
                                $tinhTrangPhu = 'Quá hạn';
                                $tinhTrangBadge = 'bg-label-danger';
                            }
                        }
                    @endphp
                    <tr>
                        <td>{{ ($danhSach->currentPage() - 1) * $danhSach->perPage() + $index + 1 }}</td>
                        <td><span class="fw-medium">{{ $item->ten_khach_hang ?? '—' }}</span></td>
                        <td>{{ $item->sdt_khach_hang ?? '—' }}</td>
                        <td class="text-center">{{ $item->san_pham_id ?? '—' }}</td>
                        <td class="text-center">{{ $item->so_luong ?? 0 }}</td>
                        <td class="text-end">
                            {{ $item->gia_thue !== null ? number_format((float)$item->gia_thue, 0, ',', '.') . ' đ' : '—' }}
                        </td>
                        <td>{{ $item->ngay_thue?->format('d/m/Y') ?? '—' }}</td>
                        <td>{{ $item->ngay_tra_du_kien?->format('d/m/Y') ?? '—' }}</td>
                        <td>{{ $item->ngay_tra_thuc_te?->format('d/m/Y') ?? '—' }}</td>
                        <td>{{ \Illuminate\Support\Str::limit($item->ghi_chu ?? '—', 30) }}</td>
                        <td class="text-center">
                            <span class="badge {{ $trangThaiBadge }}">{{ $trangThaiLabel }}</span>
                        </td>
                        <td class="text-center">
                            @if($tinhTrangBadge)
                            <span class="badge {{ $tinhTrangBadge }}">{{ $tinhTrangPhu }}</span>
                            @else
                            {{ $tinhTrangPhu }}
                            @endif
                        </td>
                        <td>{{ $item->nguoiChoThue?->name ?? '—' }}</td>
                        <td>
                            <div class="dropdown">
                                <button type="button" class="btn btn-sm btn-icon btn-outline-secondary dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                    <i class="fa-solid fa-ellipsis-vertical"></i>
                                </button>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item btn-sua-hop-dong"
                                       href="javascript:void(0);"
                                       data-bs-toggle="modal"
                                       data-bs-target="#modalSuaHopDong"
                                       data-url="{{ route('admin.trang-phuc.update-hop-dong', $item) }}"
                                       data-ten-khach-hang="{{ e($item->ten_khach_hang ?? '') }}"
                                       data-so-dien-thoai="{{ e($item->sdt_khach_hang ?? '') }}"
                                       data-trang-phuc-id="{{ $item->san_pham_id ?? '' }}"
                                       data-so-luong-thue="{{ $item->so_luong ?? 1 }}"
                                       data-gia-thue="{{ $item->gia_thue ?? '' }}"
                                       data-thoi-gian-bat-dau="{{ $item->ngay_thue?->format('Y-m-d') ?? '' }}"
                                       data-thoi-gian-du-kien-tra="{{ $item->ngay_tra_du_kien?->format('Y-m-d') ?? '' }}"
                                       data-thoi-gian-tra-thuc-te="{{ $item->ngay_tra_thuc_te?->format('Y-m-d') ?? '' }}"
                                       data-ghi-chu="{{ e($item->ghi_chu ?? '') }}"
                                       data-trang-thai="{{ $item->trang_thai ?? 'moi' }}">
                                        <i class="fa-solid fa-pen me-2"></i> Sửa
                                    </a>
                                    <form id="form-xoa-hd-{{ $item->id }}" action="{{ route('admin.trang-phuc.destroy-hop-dong', $item) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                    <button type="button" class="dropdown-item text-danger btn-xoa-hop-dong" data-form-id="form-xoa-hd-{{ $item->id }}">
                                        <i class="fa-solid fa-trash me-2"></i> Xoá
                                    </button>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="14" class="text-center py-4 text-muted">Chưa có hợp đồng nào.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <x-pagination-info :paginator="$danhSach ?? null" label="hợp đồng" />
    </div>
</div>

{{-- Modal Thêm mới hợp đồng --}}
<div class="modal fade" id="modalThemHopDong" tabindex="-1" aria-labelledby="modalThemHopDongLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-hop-dong">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalThemHopDongLabel">Thêm hợp đồng thuê trang phục</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
            </div>
            <form action="{{ route('admin.trang-phuc.store-hop-dong') }}" method="POST" id="formThemHopDong">
                @csrf
                <div class="modal-body">
                    @if($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0 list-unstyled">
                            @foreach($errors->all() as $err)
                            <li>{{ $err }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif
                    <div class="row g-3">
                        <div class="col-12 col-md-6">
                            <label class="form-label" for="them_ten_khach_hang">Tên khách hàng <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="them_ten_khach_hang" name="ten_khach_hang" value="{{ old('ten_khach_hang') }}" placeholder="Nhập tên khách hàng" required>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label" for="them_so_dien_thoai">Số điện thoại <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="them_so_dien_thoai" name="so_dien_thoai" value="{{ old('so_dien_thoai') }}" placeholder="0912345678" maxlength="20" required>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label" for="them_trang_phuc_id">Sản phẩm (ID) <span class="text-danger">*</span></label>
                            <select class="select2-admin form-select" id="them_trang_phuc_id" name="trang_phuc_id" required data-placeholder="Chọn sản phẩm">
                                <option value="">-- Chọn sản phẩm --</option>
                                @foreach($danhSachSanPham ?? [] as $sp)
                                <option value="{{ $sp->id }}" data-stock="{{ $stockByProduct[$sp->id] ?? 0 }}" {{ (string)old('trang_phuc_id') === (string)$sp->id ? 'selected' : '' }}>{{ $sp->ten_san_pham }} (code: {{ $sp->ma_san_pham }}) — Trong kho: {{ $stockByProduct[$sp->id] ?? 0 }}</option>
                                @endforeach
                            </select>
                            <div class="form-text" id="them_tong_kho_text">Trong kho còn: <strong id="them_tong_kho">—</strong></div>
                        </div>
                        <div class="col-12 col-md-3">
                            <label class="form-label" for="them_so_luong_thue">Số lượng thuê <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="them_so_luong_thue" name="so_luong_thue" value="{{ old('so_luong_thue', 1) }}" min="1" required>
                        </div>
                        <div class="col-12 col-md-3">
                            <label class="form-label" for="them_gia_thue">Giá thuê <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="them_gia_thue" name="gia_thue" value="{{ old('gia_thue') }}" min="0" step="0.01" required>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label" for="them_thoi_gian_bat_dau">Ngày thuê (bắt đầu) <span class="text-danger">*</span></label>
                            <input type="text" class="flatpickr-date-admin form-control" id="them_thoi_gian_bat_dau" name="thoi_gian_thue_bat_dau" value="{{ old('thoi_gian_thue_bat_dau') }}" placeholder="dd/mm/yyyy" required autocomplete="off">
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label" for="them_thoi_gian_du_kien_tra">Ngày dự kiến trả <span class="text-danger">*</span></label>
                            <input type="text" class="flatpickr-date-admin form-control" id="them_thoi_gian_du_kien_tra" name="thoi_gian_du_kien_tra" value="{{ old('thoi_gian_du_kien_tra') }}" placeholder="dd/mm/yyyy" required autocomplete="off">
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label" for="them_thoi_gian_tra_thuc_te">Ngày trả hàng thực tế</label>
                            <input type="text" class="flatpickr-date-admin form-control" id="them_thoi_gian_tra_thuc_te" name="thoi_gian_tra_hang_thuc_te" value="{{ old('thoi_gian_tra_hang_thuc_te') }}" placeholder="dd/mm/yyyy" autocomplete="off">
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label" for="them_trang_thai">Trạng thái</label>
                            <select class="select2-admin form-select" id="them_trang_thai" name="trang_thai" data-placeholder="Chọn trạng thái">
                                <option value="moi" {{ old('trang_thai', 'moi') == 'moi' ? 'selected' : '' }}>Mới</option>
                                <option value="dang_thue" {{ old('trang_thai') == 'dang_thue' ? 'selected' : '' }}>Đang thuê</option>
                                <option value="hoan_thanh" {{ old('trang_thai') == 'hoan_thanh' ? 'selected' : '' }}>Hoàn thành</option>
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

{{-- Modal Chỉnh sửa hợp đồng --}}
<div class="modal fade" id="modalSuaHopDong" tabindex="-1" aria-labelledby="modalSuaHopDongLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-hop-dong">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalSuaHopDongLabel">Chỉnh sửa hợp đồng</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
            </div>
            <form id="formSuaHopDong" method="POST" action="">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    @if($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0 list-unstyled">
                            @foreach($errors->all() as $err)
                            <li>{{ $err }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif
                    <div class="row g-3">
                        <div class="col-12 col-md-6">
                            <label class="form-label" for="sua_ten_khach_hang">Tên khách hàng <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="sua_ten_khach_hang" name="ten_khach_hang" placeholder="Nhập tên khách hàng" required>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label" for="sua_so_dien_thoai">Số điện thoại</label>
                            <input type="text" class="form-control" id="sua_so_dien_thoai" name="so_dien_thoai" placeholder="0912345678" maxlength="20">
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label" for="sua_trang_phuc_id">Sản phẩm (ID) <span class="text-danger">*</span></label>
                            <select class="select2-admin form-select" id="sua_trang_phuc_id" name="trang_phuc_id" required data-placeholder="Chọn sản phẩm">
                                @foreach($danhSachSanPham ?? [] as $sp)
                                <option value="{{ $sp->id }}" data-stock="{{ $stockByProduct[$sp->id] ?? 0 }}">{{ $sp->ten_san_pham }} (code: {{ $sp->ma_san_pham }}) — Trong kho: {{ $stockByProduct[$sp->id] ?? 0 }}</option>
                                @endforeach
                            </select>
                            <div class="form-text" id="sua_tong_kho_text">Trong kho còn: <strong id="sua_tong_kho">—</strong></div>
                        </div>
                        <div class="col-12 col-md-3">
                            <label class="form-label" for="sua_so_luong_thue">Số lượng thuê <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="sua_so_luong_thue" name="so_luong_thue" min="1" required>
                        </div>
                        <div class="col-12 col-md-3">
                            <label class="form-label" for="sua_gia_thue">Giá thuê <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="sua_gia_thue" name="gia_thue" min="0" step="0.01" required>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label" for="sua_thoi_gian_bat_dau">Ngày thuê (bắt đầu) <span class="text-danger">*</span></label>
                            <input type="text" class="flatpickr-date-admin form-control" id="sua_thoi_gian_bat_dau" name="thoi_gian_thue_bat_dau" placeholder="dd/mm/yyyy" required autocomplete="off">
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label" for="sua_thoi_gian_du_kien_tra">Ngày dự kiến trả <span class="text-danger">*</span></label>
                            <input type="text" class="flatpickr-date-admin form-control" id="sua_thoi_gian_du_kien_tra" name="thoi_gian_du_kien_tra" placeholder="dd/mm/yyyy" required autocomplete="off">
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label" for="sua_thoi_gian_tra_thuc_te">Ngày trả hàng thực tế</label>
                            <input type="text" class="flatpickr-date-admin form-control" id="sua_thoi_gian_tra_thuc_te" name="thoi_gian_tra_hang_thuc_te" placeholder="dd/mm/yyyy" autocomplete="off">
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label" for="sua_trang_thai">Trạng thái</label>
                            <select class="select2-admin form-select" id="sua_trang_thai" name="trang_thai" data-placeholder="Chọn trạng thái">
                                <option value="moi">Mới</option>
                                <option value="dang_thue">Đang thuê</option>
                                <option value="hoan_thanh">Hoàn thành</option>
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
<div class="modal fade" id="modalXacNhanXoaHopDong" tabindex="-1" aria-labelledby="modalXacNhanXoaHopDongLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-confirm-xoa">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalXacNhanXoaHopDongLabel">Xác nhận xóa</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
            </div>
            <div class="modal-body">
                Bạn có chắc muốn xóa hợp đồng này?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Hủy</button>
                <button type="button" class="btn btn-danger" id="btnXacNhanXoaHopDong">
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
    min-width: 1400px;
}
.table-wrapper-bordered .table th,
.table-wrapper-bordered .table td {
    border: 1px solid var(--bs-border-color, #dee2e6);
}
#modalThemHopDong .modal-hop-dong,
#modalSuaHopDong .modal-hop-dong {
    max-width: 90vw;
    width: 60%;
}
#modalXacNhanXoaHopDong .modal-confirm-xoa {
    max-width: 90vw;
    width: 400px;
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (el) { return new bootstrap.Tooltip(el); });

    @if($errors->any())
    var modalThem = document.getElementById('modalThemHopDong');
    if (modalThem) {
        var m = new bootstrap.Modal(modalThem);
        m.show();
    }
    @endif

    // Cập nhật "Trong kho còn" và max số lượng thuê khi chọn sản phẩm
    function updateThemTongKho() {
        var sel = document.getElementById('them_trang_phuc_id');
        var span = document.getElementById('them_tong_kho');
        var num = document.getElementById('them_so_luong_thue');
        if (!sel || !span) return;
        var opt = sel.options[sel.selectedIndex];
        var stock = opt && opt.value ? parseInt(opt.getAttribute('data-stock'), 10) : 0;
        if (isNaN(stock)) stock = 0;
        span.textContent = stock;
        if (num) {
            num.setAttribute('max', stock > 0 ? stock : '');
            if (num.value && parseInt(num.value, 10) > stock) num.value = Math.max(1, stock);
        }
    }
    function updateSuaTongKho() {
        var sel = document.getElementById('sua_trang_phuc_id');
        var span = document.getElementById('sua_tong_kho');
        var num = document.getElementById('sua_so_luong_thue');
        if (!sel || !span) return;
        var opt = sel.options[sel.selectedIndex];
        var stock = opt && opt.value ? parseInt(opt.getAttribute('data-stock'), 10) : 0;
        if (isNaN(stock)) stock = 0;
        span.textContent = stock;
        if (num) num.setAttribute('max', stock > 0 ? stock : '');
    }
    var themTrangPhucId = document.getElementById('them_trang_phuc_id');
    var suaTrangPhucId = document.getElementById('sua_trang_phuc_id');
    if (themTrangPhucId) {
        themTrangPhucId.addEventListener('change', updateThemTongKho);
        updateThemTongKho();
    }
    if (suaTrangPhucId) {
        suaTrangPhucId.addEventListener('change', updateSuaTongKho);
    }

    // Modal Sửa: gán data vào form
    var modalSua = document.getElementById('modalSuaHopDong');
    var formSua = document.getElementById('formSuaHopDong');
    if (modalSua && formSua) {
        modalSua.addEventListener('show.bs.modal', function(e) {
            var btn = e.relatedTarget;
            if (!btn || !btn.classList.contains('btn-sua-hop-dong')) return;
            var url = btn.getAttribute('data-url');
            if (url) formSua.action = url;
            document.getElementById('sua_ten_khach_hang').value = btn.getAttribute('data-ten-khach-hang') || '';
            document.getElementById('sua_so_dien_thoai').value = btn.getAttribute('data-so-dien-thoai') || '';
            document.getElementById('sua_trang_phuc_id').value = btn.getAttribute('data-trang-phuc-id') || '';
            document.getElementById('sua_so_luong_thue').value = btn.getAttribute('data-so-luong-thue') || '1';
            document.getElementById('sua_gia_thue').value = btn.getAttribute('data-gia-thue') || '';
            if (window.setAdminDateInput) { setAdminDateInput('sua_thoi_gian_bat_dau', btn.getAttribute('data-thoi-gian-bat-dau') || ''); setAdminDateInput('sua_thoi_gian_du_kien_tra', btn.getAttribute('data-thoi-gian-du-kien-tra') || ''); setAdminDateInput('sua_thoi_gian_tra_thuc_te', btn.getAttribute('data-thoi-gian-tra-thuc-te') || ''); } else { document.getElementById('sua_thoi_gian_bat_dau').value = btn.getAttribute('data-thoi-gian-bat-dau') || ''; document.getElementById('sua_thoi_gian_du_kien_tra').value = btn.getAttribute('data-thoi-gian-du-kien-tra') || ''; document.getElementById('sua_thoi_gian_tra_thuc_te').value = btn.getAttribute('data-thoi-gian-tra-thuc-te') || ''; }
            document.getElementById('sua_ghi_chu').value = btn.getAttribute('data-ghi-chu') || '';
            document.getElementById('sua_trang_thai').value = btn.getAttribute('data-trang-thai') || '0';
            updateSuaTongKho();
        });
    }

    // Xóa: modal xác nhận
    var modalXoa = document.getElementById('modalXacNhanXoaHopDong');
    var btnXacNhanXoa = document.getElementById('btnXacNhanXoaHopDong');
    var formIdCanXoa = null;
    if (modalXoa && btnXacNhanXoa) {
        modalXoa.addEventListener('hidden.bs.modal', function() {
            document.querySelectorAll('.modal-backdrop').forEach(function(el) { el.remove(); });
            document.body.classList.remove('modal-open');
            document.body.style.removeProperty('overflow');
            document.body.style.removeProperty('padding-right');
        });
        document.querySelectorAll('.btn-xoa-hop-dong').forEach(function(btn) {
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
