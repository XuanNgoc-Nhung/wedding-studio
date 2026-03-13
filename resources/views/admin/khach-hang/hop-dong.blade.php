@extends('admin.layouts.app')

@section('content')
<div class="card">
    <h5 class="card-header">Danh sách hợp đồng</h5>
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
        <form action="{{ route('admin.khach-hang.hop-dong') }}" method="GET" class="mb-4">
            <div class="row g-3 align-items-end">
                <div class="col-md-6 col-lg-4">
                    <label class="form-label" for="search">Tên khách hàng hoặc Email/SĐT</label>
                    <input type="text"
                           class="form-control"
                           id="search"
                           name="search"
                           value="{{ request('search') }}"
                           placeholder="Nhập để tìm kiếm...">
                </div>
                <div class="col-auto">
                    <button type="submit" class="btn btn-primary">
                        <i class="fa-solid fa-magnifying-glass me-1"></i> Lọc
                    </button>
                    @if(request('search'))
                    <a href="{{ route('admin.khach-hang.hop-dong') }}" class="btn btn-outline-secondary">Bỏ lọc</a>
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
                        <th>Khách hàng</th>
                        <th>Địa điểm</th>
                        <th>Ngày chụp</th>
                        <th>Thợ chụp</th>
                        <th>Thợ make</th>
                        <th>Thợ edit</th>
                        <th class="text-end">Tổng tiền</th>
                        <th>Trạng thái chụp</th>
                        <th>Trạng thái HĐ</th>
                        <th>Trạng thái edit</th>
                        <th class="text-center" style="width: 100px;">Thao tác</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @forelse($danhSach ?? [] as $index => $item)
                    @php
                        $tenKhachHang = '—';
                        if ($item->khachHang) {
                            $parts = array_filter([$item->khachHang->ho_ten_chu_re ?? '', $item->khachHang->ho_ten_co_dau ?? '']);
                            $tenKhachHang = implode(' / ', $parts) ?: '—';
                        }
                    @endphp
                    <tr>
                        <td>{{ ($danhSach->currentPage() - 1) * $danhSach->perPage() + $index + 1 }}</td>
                        <td><span class="fw-medium">{{ $tenKhachHang }}</span></td>
                        <td>{{ $item->dia_diem ? str($item->dia_diem)->limit(25) : '—' }}</td>
                        <td>{{ $item->ngay_chup ? $item->ngay_chup->format('d/m/Y') : '—' }}</td>
                        <td>{{ $item->thoChup?->user?->name ?? '—' }}</td>
                        <td>{{ $item->thoMake?->user?->name ?? '—' }}</td>
                        <td>{{ $item->thoEdit?->user?->name ?? '—' }}</td>
                        <td class="text-end">{{ $item->tong_tien !== null ? number_format((float)$item->tong_tien, 0, ',', '.') . ' đ' : '—' }}</td>
                        <td>{{ $item->trang_thai_chup ?? '—' }}</td>
                        <td>{{ $item->trang_thai_hop_dong ?? '—' }}</td>
                        <td>{{ $item->trang_thai_edit ?? '—' }}</td>
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
                                       data-url="{{ route('admin.khach-hang.update-hop-dong', $item) }}"
                                       data-khach-hang-id="{{ $item->khach_hang_id ?? '' }}"
                                       data-tho-chup-id="{{ $item->tho_chup_id ?? '' }}"
                                       data-tho-make-id="{{ $item->tho_make_id ?? '' }}"
                                       data-tho-edit-id="{{ $item->tho_edit_id ?? '' }}"
                                       data-dia-diem="{{ e($item->dia_diem ?? '') }}"
                                       data-ngay-chup="{{ $item->ngay_chup?->format('Y-m-d') ?? '' }}"
                                       data-trang-phuc="{{ e($item->trang_phuc ?? '') }}"
                                       data-concept="{{ e($item->concept ?? '') }}"
                                       data-ghi-chu-chup="{{ e($item->ghi_chu_chup ?? '') }}"
                                       data-trang-thai-chup="{{ e($item->trang_thai_chup ?? '') }}"
                                       data-tong-tien="{{ $item->tong_tien !== null ? $item->tong_tien : '' }}"
                                       data-thanh-toan-lan-1="{{ $item->thanh_toan_lan_1 !== null ? $item->thanh_toan_lan_1 : '' }}"
                                       data-thanh-toan-lan-2="{{ $item->thanh_toan_lan_2 !== null ? $item->thanh_toan_lan_2 : '' }}"
                                       data-thanh-toan-lan-3="{{ $item->thanh_toan_lan_3 !== null ? $item->thanh_toan_lan_3 : '' }}"
                                       data-trang-thai-hop-dong="{{ e($item->trang_thai_hop_dong ?? '') }}"
                                       data-trang-thai-edit="{{ e($item->trang_thai_edit ?? '') }}"
                                       data-link-file-demo="{{ e($item->link_file_demo ?? '') }}"
                                       data-link-file-in="{{ e($item->link_file_in ?? '') }}"
                                       data-ngay-tra-link-in="{{ $item->ngay_tra_link_in?->format('Y-m-d') ?? '' }}"
                                       data-ngay-hen-tra-hang="{{ $item->ngay_hen_tra_hang?->format('Y-m-d') ?? '' }}">
                                        <i class="fa-solid fa-pen me-2"></i> Sửa
                                    </a>
                                    <form id="form-xoa-hd-{{ $item->id }}" action="{{ route('admin.khach-hang.destroy-hop-dong', $item) }}" method="POST" class="d-inline">
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
                        <td colspan="12" class="text-center py-4 text-muted">Chưa có hợp đồng nào.</td>
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
                <h5 class="modal-title" id="modalThemHopDongLabel">Thêm hợp đồng mới</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
            </div>
            <form action="{{ route('admin.khach-hang.store-hop-dong') }}" method="POST" id="formThemHopDong">
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
                        {{-- Hàng 1: 4 cột (lg), 2 cột (md), 1 cột (xs) --}}
                        <div class="col-12 col-sm-6 col-lg-3">
                            <label class="form-label" for="them_khach_hang_id">Khách hàng <span class="text-danger">*</span></label>
                            <select class="form-select" id="them_khach_hang_id" name="khach_hang_id" required>
                                <option value="">-- Chọn khách hàng --</option>
                                @foreach($danhSachKhachHang ?? [] as $kh)
                                <option value="{{ $kh->id }}" {{ (string)old('khach_hang_id') === (string)$kh->id ? 'selected' : '' }}>
                                    {{ $kh->ho_ten_chu_re ?? '' }} / {{ $kh->ho_ten_co_dau ?? '' }} ({{ $kh->email_hoac_sdt_chu_re ?? $kh->email_hoac_sdt_co_dau ?? '—' }})
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12 col-sm-6 col-lg-3">
                            <label class="form-label" for="them_dia_diem">Địa điểm</label>
                            <input type="text" class="form-control" id="them_dia_diem" name="dia_diem" value="{{ old('dia_diem') }}" placeholder="Địa điểm chụp">
                        </div>
                        <div class="col-12 col-sm-6 col-lg-3">
                            <label class="form-label" for="them_ngay_chup">Ngày chụp</label>
                            <input type="date" class="form-control" id="them_ngay_chup" name="ngay_chup" value="{{ old('ngay_chup') }}">
                        </div>
                        <div class="col-12 col-sm-6 col-lg-3">
                            <label class="form-label" for="them_ngay_hen_tra_hang">Ngày hẹn trả hàng</label>
                            <input type="date" class="form-control" id="them_ngay_hen_tra_hang" name="ngay_hen_tra_hang" value="{{ old('ngay_hen_tra_hang') }}">
                        </div>
                        {{-- Tabs: Nhóm dịch vụ & Dịch vụ lẻ --}}
                        <div class="col-12 mt-2">
                            <label class="form-label d-block">Tham khảo dịch vụ</label>
                            <ul class="nav nav-tabs mb-2" id="tabDichVuThemHopDong" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link active" id="tab-nhom-dich-vu-btn" data-bs-toggle="tab" data-bs-target="#tab-nhom-dich-vu" type="button" role="tab">Nhóm dịch vụ</button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="tab-dich-vu-le-btn" data-bs-toggle="tab" data-bs-target="#tab-dich-vu-le" type="button" role="tab">Dịch vụ lẻ</button>
                                </li>
                            </ul>
                            <div class="tab-content border border-top-0 rounded-bottom p-3 bg-light" id="tabDichVuThemHopDongContent">
                                <div class="tab-pane fade show active" id="tab-nhom-dich-vu" role="tabpanel">
                                    <div class="table-responsive" style="max-height: 220px; overflow-y: auto;">
                                        <table class="table table-sm table-hover table-bordered mb-0">
                                            <thead class="table-light sticky-top">
                                                <tr>
                                                    <th style="width: 50px;">STT</th>
                                                    <th>Tên nhóm</th>
                                                    <th>Mã</th>
                                                    <th class="text-end">Giá tiền</th>
                                                    <th class="text-end">Giá gốc</th>
                                                    <th>Thẻ</th>
                                                    <th>Ghi chú</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($danhSachNhomDichVu ?? [] as $idx => $ndv)
                                                <tr>
                                                    <td>{{ $idx + 1 }}</td>
                                                    <td>{{ $ndv->ten_nhom ?? '—' }}</td>
                                                    <td>{{ $ndv->ma_nhom ?? '—' }}</td>
                                                    <td class="text-end">{{ $ndv->gia_tien !== null ? number_format((float)$ndv->gia_tien, 0, ',', '.') . ' đ' : '—' }}</td>
                                                    <td class="text-end">{{ $ndv->gia_goc !== null ? number_format((float)$ndv->gia_goc, 0, ',', '.') . ' đ' : '—' }}</td>
                                                    <td>{{ $ndv->the ?? '—' }}</td>
                                                    <td>{{ $ndv->ghi_chu ? str($ndv->ghi_chu)->limit(40) : '—' }}</td>
                                                </tr>
                                                @empty
                                                <tr><td colspan="7" class="text-center text-muted py-3">Chưa có nhóm dịch vụ.</td></tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="tab-dich-vu-le" role="tabpanel">
                                    <div class="table-responsive" style="max-height: 220px; overflow-y: auto;">
                                        <table class="table table-sm table-hover table-bordered mb-0">
                                            <thead class="table-light sticky-top">
                                                <tr>
                                                    <th style="width: 50px;">STT</th>
                                                    <th>Tên dịch vụ</th>
                                                    <th>Mã</th>
                                                    <th class="text-end">Giá dịch vụ</th>
                                                    <th>Ghi chú</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($danhSachDichVuLe ?? [] as $idx => $dvl)
                                                <tr>
                                                    <td>{{ $idx + 1 }}</td>
                                                    <td>{{ $dvl->ten_dich_vu ?? '—' }}</td>
                                                    <td>{{ $dvl->ma_dich_vu ?? '—' }}</td>
                                                    <td class="text-end">{{ $dvl->gia_dich_vu !== null ? number_format((float)$dvl->gia_dich_vu, 0, ',', '.') . ' đ' : '—' }}</td>
                                                    <td>{{ $dvl->ghi_chu ? str($dvl->ghi_chu)->limit(40) : '—' }}</td>
                                                </tr>
                                                @empty
                                                <tr><td colspan="5" class="text-center text-muted py-3">Chưa có dịch vụ lẻ.</td></tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <label class="form-label" for="them_trang_phuc">Trang phục</label>
                            <textarea class="form-control" id="them_trang_phuc" name="trang_phuc" rows="2" placeholder="Mô tả trang phục">{{ old('trang_phuc') }}</textarea>
                        </div>
                        <div class="col-12">
                            <label class="form-label" for="them_concept">Concept</label>
                            <textarea class="form-control" id="them_concept" name="concept" rows="2" placeholder="Concept chụp">{{ old('concept') }}</textarea>
                        </div>
                        <div class="col-12">
                            <label class="form-label" for="them_ghi_chu_chup">Ghi chú chụp</label>
                            <textarea class="form-control" id="them_ghi_chu_chup" name="ghi_chu_chup" rows="2" placeholder="Ghi chú">{{ old('ghi_chu_chup') }}</textarea>
                        </div>
                        {{-- Tổng tiền + thanh toán: 4 cột (lg), 2 (md), 1 (xs) --}}
                        <div class="col-12 col-sm-6 col-lg-3">
                            <label class="form-label" for="them_tong_tien">Tổng tiền</label>
                            <input type="number" class="form-control" id="them_tong_tien" name="tong_tien" value="{{ old('tong_tien') }}" min="0" step="0.01">
                        </div>
                        <div class="col-12 col-sm-6 col-lg-3">
                            <label class="form-label" for="them_thanh_toan_lan_1">Thanh toán lần 1</label>
                            <input type="number" class="form-control" id="them_thanh_toan_lan_1" name="thanh_toan_lan_1" value="{{ old('thanh_toan_lan_1') }}" min="0" step="0.01">
                        </div>
                        <div class="col-12 col-sm-6 col-lg-3">
                            <label class="form-label" for="them_thanh_toan_lan_2">Thanh toán lần 2</label>
                            <input type="number" class="form-control" id="them_thanh_toan_lan_2" name="thanh_toan_lan_2" value="{{ old('thanh_toan_lan_2') }}" min="0" step="0.01">
                        </div>
                        <div class="col-12 col-sm-6 col-lg-3">
                            <label class="form-label" for="them_thanh_toan_lan_3">Thanh toán lần 3</label>
                            <input type="number" class="form-control" id="them_thanh_toan_lan_3" name="thanh_toan_lan_3" value="{{ old('thanh_toan_lan_3') }}" min="0" step="0.01">
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
                        <div class="col-12 col-md-6">
                            <label class="form-label" for="sua_khach_hang_id">Khách hàng <span class="text-danger">*</span></label>
                            <select class="form-select" id="sua_khach_hang_id" name="khach_hang_id" required>
                                <option value="">-- Chọn khách hàng --</option>
                                @foreach($danhSachKhachHang ?? [] as $kh)
                                <option value="{{ $kh->id }}">{{ $kh->ho_ten_chu_re ?? '' }} / {{ $kh->ho_ten_co_dau ?? '' }} ({{ $kh->email_hoac_sdt_chu_re ?? $kh->email_hoac_sdt_co_dau ?? '—' }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label" for="sua_dia_diem">Địa điểm</label>
                            <input type="text" class="form-control" id="sua_dia_diem" name="dia_diem" placeholder="Địa điểm chụp">
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label" for="sua_ngay_chup">Ngày chụp</label>
                            <input type="date" class="form-control" id="sua_ngay_chup" name="ngay_chup">
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label" for="sua_trang_thai_chup">Trạng thái chụp</label>
                            <input type="text" class="form-control" id="sua_trang_thai_chup" name="trang_thai_chup" placeholder="VD: Đã chụp, Chờ chụp...">
                        </div>
                        <div class="col-12 col-md-4">
                            <label class="form-label" for="sua_tho_chup_id">Thợ chụp</label>
                            <select class="form-select" id="sua_tho_chup_id" name="tho_chup_id">
                                <option value="">-- Chọn --</option>
                                @foreach($danhSachNhanVien ?? [] as $nv)
                                <option value="{{ $nv->id }}">{{ $nv->user?->name ?? 'NV #' . $nv->id }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12 col-md-4">
                            <label class="form-label" for="sua_tho_make_id">Thợ make</label>
                            <select class="form-select" id="sua_tho_make_id" name="tho_make_id">
                                <option value="">-- Chọn --</option>
                                @foreach($danhSachNhanVien ?? [] as $nv)
                                <option value="{{ $nv->id }}">{{ $nv->user?->name ?? 'NV #' . $nv->id }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12 col-md-4">
                            <label class="form-label" for="sua_tho_edit_id">Thợ edit</label>
                            <select class="form-select" id="sua_tho_edit_id" name="tho_edit_id">
                                <option value="">-- Chọn --</option>
                                @foreach($danhSachNhanVien ?? [] as $nv)
                                <option value="{{ $nv->id }}">{{ $nv->user?->name ?? 'NV #' . $nv->id }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label" for="sua_trang_phuc">Trang phục</label>
                            <textarea class="form-control" id="sua_trang_phuc" name="trang_phuc" rows="2" placeholder="Mô tả trang phục"></textarea>
                        </div>
                        <div class="col-12">
                            <label class="form-label" for="sua_concept">Concept</label>
                            <textarea class="form-control" id="sua_concept" name="concept" rows="2" placeholder="Concept chụp"></textarea>
                        </div>
                        <div class="col-12">
                            <label class="form-label" for="sua_ghi_chu_chup">Ghi chú chụp</label>
                            <textarea class="form-control" id="sua_ghi_chu_chup" name="ghi_chu_chup" rows="2" placeholder="Ghi chú"></textarea>
                        </div>
                        <div class="col-12 col-md-4">
                            <label class="form-label" for="sua_tong_tien">Tổng tiền</label>
                            <input type="number" class="form-control" id="sua_tong_tien" name="tong_tien" min="0" step="0.01">
                        </div>
                        <div class="col-12 col-md-4">
                            <label class="form-label" for="sua_thanh_toan_lan_1">Thanh toán lần 1</label>
                            <input type="number" class="form-control" id="sua_thanh_toan_lan_1" name="thanh_toan_lan_1" min="0" step="0.01">
                        </div>
                        <div class="col-12 col-md-4">
                            <label class="form-label" for="sua_thanh_toan_lan_2">Thanh toán lần 2</label>
                            <input type="number" class="form-control" id="sua_thanh_toan_lan_2" name="thanh_toan_lan_2" min="0" step="0.01">
                        </div>
                        <div class="col-12 col-md-4">
                            <label class="form-label" for="sua_thanh_toan_lan_3">Thanh toán lần 3</label>
                            <input type="number" class="form-control" id="sua_thanh_toan_lan_3" name="thanh_toan_lan_3" min="0" step="0.01">
                        </div>
                        <div class="col-12 col-md-4">
                            <label class="form-label" for="sua_trang_thai_hop_dong">Trạng thái hợp đồng</label>
                            <input type="text" class="form-control" id="sua_trang_thai_hop_dong" name="trang_thai_hop_dong" placeholder="VD: Mới, Đã thanh toán...">
                        </div>
                        <div class="col-12 col-md-4">
                            <label class="form-label" for="sua_trang_thai_edit">Trạng thái edit</label>
                            <input type="text" class="form-control" id="sua_trang_thai_edit" name="trang_thai_edit" placeholder="VD: Đang edit, Đã xong...">
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label" for="sua_link_file_demo">Link file demo</label>
                            <input type="text" class="form-control" id="sua_link_file_demo" name="link_file_demo" placeholder="URL">
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label" for="sua_link_file_in">Link file in</label>
                            <input type="text" class="form-control" id="sua_link_file_in" name="link_file_in" placeholder="URL">
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label" for="sua_ngay_tra_link_in">Ngày trả link in</label>
                            <input type="date" class="form-control" id="sua_ngay_tra_link_in" name="ngay_tra_link_in">
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label" for="sua_ngay_hen_tra_hang">Ngày hẹn trả hàng</label>
                            <input type="date" class="form-control" id="sua_ngay_hen_tra_hang" name="ngay_hen_tra_hang">
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

{{-- Modal xác nhận xóa hợp đồng --}}
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
#modalThemHopDong .modal-dialog.modal-hop-dong {
    max-width: 95vw;
    width: 1200px;
}
#modalSuaHopDong .modal-dialog.modal-hop-dong {
    max-width: 90vw;
    width: 900px;
}
@media (min-width: 992px) {
    #modalThemHopDong .modal-dialog.modal-hop-dong {
        max-width: 1200px;
    }
    #modalSuaHopDong .modal-dialog.modal-hop-dong {
        max-width: 900px;
    }
}
#modalXacNhanXoaHopDong .modal-confirm-xoa {
    max-width: 90vw;
    width: 400px;
}
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

    // Modal Sửa: gán data vào form
    var modalSua = document.getElementById('modalSuaHopDong');
    var formSua = document.getElementById('formSuaHopDong');
    if (modalSua && formSua) {
        modalSua.addEventListener('show.bs.modal', function(e) {
            var btn = e.relatedTarget;
            if (!btn || !btn.classList.contains('btn-sua-hop-dong')) return;
            var url = btn.getAttribute('data-url');
            if (url) formSua.action = url;
            document.getElementById('sua_khach_hang_id').value = btn.getAttribute('data-khach-hang-id') || '';
            document.getElementById('sua_tho_chup_id').value = btn.getAttribute('data-tho-chup-id') || '';
            document.getElementById('sua_tho_make_id').value = btn.getAttribute('data-tho-make-id') || '';
            document.getElementById('sua_tho_edit_id').value = btn.getAttribute('data-tho-edit-id') || '';
            document.getElementById('sua_dia_diem').value = btn.getAttribute('data-dia-diem') || '';
            document.getElementById('sua_ngay_chup').value = btn.getAttribute('data-ngay-chup') || '';
            document.getElementById('sua_trang_phuc').value = btn.getAttribute('data-trang-phuc') || '';
            document.getElementById('sua_concept').value = btn.getAttribute('data-concept') || '';
            document.getElementById('sua_ghi_chu_chup').value = btn.getAttribute('data-ghi-chu-chup') || '';
            document.getElementById('sua_trang_thai_chup').value = btn.getAttribute('data-trang-thai-chup') || '';
            document.getElementById('sua_tong_tien').value = btn.getAttribute('data-tong-tien') || '';
            document.getElementById('sua_thanh_toan_lan_1').value = btn.getAttribute('data-thanh-toan-lan-1') || '';
            document.getElementById('sua_thanh_toan_lan_2').value = btn.getAttribute('data-thanh-toan-lan-2') || '';
            document.getElementById('sua_thanh_toan_lan_3').value = btn.getAttribute('data-thanh-toan-lan-3') || '';
            document.getElementById('sua_trang_thai_hop_dong').value = btn.getAttribute('data-trang-thai-hop-dong') || '';
            document.getElementById('sua_trang_thai_edit').value = btn.getAttribute('data-trang-thai-edit') || '';
            document.getElementById('sua_link_file_demo').value = btn.getAttribute('data-link-file-demo') || '';
            document.getElementById('sua_link_file_in').value = btn.getAttribute('data-link-file-in') || '';
            document.getElementById('sua_ngay_tra_link_in').value = btn.getAttribute('data-ngay-tra-link-in') || '';
            document.getElementById('sua_ngay_hen_tra_hang').value = btn.getAttribute('data-ngay-hen-tra-hang') || '';
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
