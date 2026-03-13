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
                        <th>Người tạo</th>
                        <th>Khách hàng</th>
                        <th>Địa điểm</th>
                        <th>Ngày chụp</th>
                        <th>Ngày hẹn trả hàng</th>
                        <th>Trang phục</th>
                        <th>Ghi chú</th>
                        <th class="text-end">Tổng tiền</th>
                        <th class="text-end">Thanh toán lần 1</th>
                        <th class="text-end">Thanh toán lần 2</th>
                        <th class="text-end">Thanh toán lần 3</th>
                        <th>Trạng thái chụp</th>
                        <th>Trạng thái edit</th>
                        <th>Trạng thái HĐ</th>
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
                        <td>{{ $item->nguoiTao?->name ?? '—' }}</td>
                        <td><span class="fw-medium">{{ $tenKhachHang }}</span></td>
                        <td>{{ $item->dia_diem ? str($item->dia_diem)->limit(25) : '—' }}</td>
                        <td>{{ $item->ngay_chup ? $item->ngay_chup->format('d/m/Y') : '—' }}</td>
                        <td>{{ $item->ngay_hen_tra_hang ? $item->ngay_hen_tra_hang->format('d/m/Y') : '—' }}</td>
                        <td>{{ $item->trang_phuc ? str($item->trang_phuc)->limit(30) : '—' }}</td>
                        <td>{{ $item->ghi_chu_chup ? str($item->ghi_chu_chup)->limit(40) : '—' }}</td>
                        <td class="text-end">{{ $item->tong_tien !== null ? number_format((float)$item->tong_tien, 0, ',', '.') . ' đ' : '—' }}</td>
                        <td class="text-end">{{ $item->thanh_toan_lan_1 !== null ? number_format((float)$item->thanh_toan_lan_1, 0, ',', '.') . ' đ' : '—' }}</td>
                        <td class="text-end">{{ $item->thanh_toan_lan_2 !== null ? number_format((float)$item->thanh_toan_lan_2, 0, ',', '.') . ' đ' : '—' }}</td>
                        <td class="text-end">{{ $item->thanh_toan_lan_3 !== null ? number_format((float)$item->thanh_toan_lan_3, 0, ',', '.') . ' đ' : '—' }}</td>
                        <td>{{ $item->trang_thai_chup ?? '—' }}</td>
                        <td>{{ $item->trang_thai_edit ?? '—' }}</td>
                        <td>{{ $item->trang_thai_hop_dong ?? '—' }}</td>
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
                                       data-dich-vu-url="{{ route('admin.khach-hang.hop-dong.dich-vu', $item) }}"
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
                        <td colspan="16" class="text-center py-4 text-muted">Chưa có hợp đồng nào.</td>
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
                            <select class="select2-admin form-select" id="them_khach_hang_id" name="khach_hang_id" required data-placeholder="Chọn khách hàng">
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
                            <input type="text" class="flatpickr-date-admin form-control" id="them_ngay_chup" name="ngay_chup" value="{{ old('ngay_chup') }}" placeholder="dd/mm/yyyy" autocomplete="off">
                        </div>
                        <div class="col-12 col-sm-6 col-lg-3">
                            <label class="form-label" for="them_ngay_hen_tra_hang">Ngày hẹn trả hàng</label>
                            <input type="text" class="flatpickr-date-admin form-control" id="them_ngay_hen_tra_hang" name="ngay_hen_tra_hang" value="{{ old('ngay_hen_tra_hang') }}" placeholder="dd/mm/yyyy" autocomplete="off">
                        </div>
                        {{-- Tabs: Nhóm dịch vụ & Dịch vụ lẻ (Filled Pills) --}}
                        <div class="col-12 mt-2">
                            <label class="form-label d-block">Tham khảo dịch vụ</label>
                            <div class="nav-align-top">
                                <ul class="nav nav-pills mb-4 nav-fill" id="tabDichVuThemHopDong" role="tablist">
                                    <li class="nav-item mb-1 mb-sm-0" role="presentation">
                                        <button type="button" class="nav-link active" id="tab-nhom-dich-vu-btn" role="tab" data-bs-toggle="tab" data-bs-target="#tab-nhom-dich-vu" aria-controls="tab-nhom-dich-vu" aria-selected="true">Nhóm dịch vụ</button>
                                    </li>
                                    <li class="nav-item mb-1 mb-sm-0" role="presentation">
                                        <button type="button" class="nav-link" id="tab-dich-vu-le-btn" role="tab" data-bs-toggle="tab" data-bs-target="#tab-dich-vu-le" aria-controls="tab-dich-vu-le" aria-selected="false">Dịch vụ lẻ</button>
                                    </li>
                                </ul>
                                <div class="tab-content p-3 bg-light rounded" id="tabDichVuThemHopDongContent">
                                <div class="tab-pane fade show active" id="tab-nhom-dich-vu" role="tabpanel">
                                    <div class="table-responsive" style="max-height: 220px; overflow-y: auto;">
                                        <table class="table table-sm table-hover table-bordered mb-0" id="tableNhomDichVuThem">
                                            <thead class="table-light sticky-top">
                                                <tr>
                                                    <th style="width: 40px;" class="text-center">Chọn</th>
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
                                                @php
                                                    $dvlList = $ndv->dichVuLe->map(fn($d) => [
                                                        'id' => $d->id,
                                                        'ten_dich_vu' => $d->ten_dich_vu ?? '—',
                                                        'ma_dich_vu' => $d->ma_dich_vu ?? '—',
                                                        'gia_goc' => $d->gia_dich_vu !== null ? (float)$d->gia_dich_vu : 0,
                                                        'gia_dich_vu' => $d->gia_dich_vu !== null ? number_format((float)$d->gia_dich_vu, 0, ',', '.') . ' đ' : '—',
                                                        'ghi_chu' => $d->ghi_chu ? \Illuminate\Support\Str::limit($d->ghi_chu, 40) : '—',
                                                        'so_luong' => $d->pivot->so_luong ?? 1,
                                                    ])->values()->toArray();
                                                @endphp
                                                <tr data-nhom-id="{{ $ndv->id }}"
                                                    data-ten-nhom="{{ e($ndv->ten_nhom ?? '') }}"
                                                    data-dich-vu-le='{{ json_encode($dvlList, JSON_HEX_APOS | JSON_HEX_QUOT | JSON_UNESCAPED_UNICODE) }}'>
                                                    <td class="text-center">
                                                        <input type="checkbox" class="form-check-input cb-nhom-dich-vu" value="{{ $ndv->id }}" aria-label="Chọn nhóm {{ $ndv->ten_nhom }}">
                                                    </td>
                                                    <td>{{ $idx + 1 }}</td>
                                                    <td>{{ $ndv->ten_nhom ?? '—' }}</td>
                                                    <td>{{ $ndv->ma_nhom ?? '—' }}</td>
                                                    <td class="text-end">{{ $ndv->gia_tien !== null ? number_format((float)$ndv->gia_tien, 0, ',', '.') . ' đ' : '—' }}</td>
                                                    <td class="text-end">{{ $ndv->gia_goc !== null ? number_format((float)$ndv->gia_goc, 0, ',', '.') . ' đ' : '—' }}</td>
                                                    <td>{{ $ndv->the ?? '—' }}</td>
                                                    <td>{{ $ndv->ghi_chu ? str($ndv->ghi_chu)->limit(40) : '—' }}</td>
                                                </tr>
                                                @empty
                                                <tr><td colspan="8" class="text-center text-muted py-3">Chưa có nhóm dịch vụ.</td></tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="tab-dich-vu-le" role="tabpanel">
                                    <div class="table-responsive" style="max-height: 220px; overflow-y: auto;">
                                        <table class="table table-sm table-hover table-bordered mb-0" id="tableDichVuLeThem">
                                            <thead class="table-light sticky-top">
                                                <tr>
                                                    <th style="width: 40px;" class="text-center">Chọn</th>
                                                    <th style="width: 50px;">STT</th>
                                                    <th>Tên dịch vụ</th>
                                                    <th>Mã</th>
                                                    <th class="text-end">Giá dịch vụ</th>
                                                    <th>Ghi chú</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($danhSachDichVuLe ?? [] as $idx => $dvl)
                                                @php
                                                    $dvlItem = [
                                                        'id' => $dvl->id,
                                                        'ten_dich_vu' => $dvl->ten_dich_vu ?? '—',
                                                        'ma_dich_vu' => $dvl->ma_dich_vu ?? '—',
                                                        'gia_goc' => $dvl->gia_dich_vu !== null ? (float)$dvl->gia_dich_vu : 0,
                                                        'gia_dich_vu' => $dvl->gia_dich_vu !== null ? number_format((float)$dvl->gia_dich_vu, 0, ',', '.') . ' đ' : '—',
                                                        'ghi_chu' => $dvl->ghi_chu ? \Illuminate\Support\Str::limit($dvl->ghi_chu, 40) : '—',
                                                        'so_luong' => 1,
                                                    ];
                                                @endphp
                                                <tr data-dich-vu-le='{{ json_encode($dvlItem, JSON_HEX_APOS | JSON_HEX_QUOT | JSON_UNESCAPED_UNICODE) }}'>
                                                    <td class="text-center">
                                                        <input type="checkbox" class="form-check-input cb-dich-vu-le" value="{{ $dvl->id }}" aria-label="Chọn dịch vụ {{ $dvl->ten_dich_vu }}">
                                                    </td>
                                                    <td>{{ $idx + 1 }}</td>
                                                    <td>{{ $dvl->ten_dich_vu ?? '—' }}</td>
                                                    <td>{{ $dvl->ma_dich_vu ?? '—' }}</td>
                                                    <td class="text-end">{{ $dvl->gia_dich_vu !== null ? number_format((float)$dvl->gia_dich_vu, 0, ',', '.') . ' đ' : '—' }}</td>
                                                    <td>{{ $dvl->ghi_chu ? str($dvl->ghi_chu)->limit(40) : '—' }}</td>
                                                </tr>
                                                @empty
                                                <tr><td colspan="6" class="text-center text-muted py-3">Chưa có dịch vụ lẻ.</td></tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                </div>
                            </div>
                        </div>
                        {{-- Bảng riêng: Dịch vụ lẻ của nhóm đã chọn (tách khỏi tabs, trên Trang phục) --}}
                        <div class="col-12 d-none" id="boxDichVuLeTheoNhom">
                            <label class="form-label fw-medium">Dịch vụ lẻ của nhóm đã chọn</label>
                            <div class="table-responsive border rounded" style="max-height: 200px; overflow-y: auto;">
                                        <table class="table table-sm table-bordered mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th style="width: 40px;" class="text-center">Chọn</th>
                                            <th>Nhóm dịch vụ</th>
                                            <th style="width: 50px;">STT</th>
                                            <th>Tên dịch vụ</th>
                                            <th>Mã</th>
                                            <th class="text-end">Giá gốc</th>
                                            <th class="text-end">Giá thực</th>
                                            <th>Ghi chú</th>
                                        </tr>
                                    </thead>
                                    <tbody id="tbodyDichVuLeTheoNhom">
                                    </tbody>
                                    <tfoot id="tfootDichVuLeTheoNhom" class="table-light">
                                        <tr>
                                            <td colspan="5" class="text-end fw-medium">Tổng</td>
                                            <td class="text-end fw-medium" id="tdTongGiaGoc">0 đ</td>
                                            <td class="text-end fw-medium" id="tdTongGiaThuc">0 đ</td>
                                            <td></td>
                                        </tr>
                                    </tfoot>
                                </table>
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
                    <a href="{{ route('admin.khach-hang.danh-sach') }}" target="_blank" rel="noopener noreferrer" class="btn btn-outline-primary">
                         Thêm khách hàng
                    </a>
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fa-solid fa-floppy-disk me-1"></i> Lưu
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Modal Chỉnh sửa hợp đồng (cấu trúc giống Thêm mới, khách hàng disabled) --}}
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
                        {{-- Hàng 1: Khách hàng (disabled) + Địa điểm + Ngày chụp + Ngày hẹn trả hàng --}}
                        <div class="col-12 col-sm-6 col-lg-3">
                            <label class="form-label" for="sua_khach_hang_id">Khách hàng <span class="text-danger">*</span></label>
                            <input type="hidden" name="khach_hang_id" id="sua_khach_hang_id_hidden">
                            <select class="select2-admin form-select" id="sua_khach_hang_id" disabled aria-label="Khách hàng (không thể thay đổi)" data-placeholder="Chọn khách hàng">
                                <option value="">-- Chọn khách hàng --</option>
                                @foreach($danhSachKhachHang ?? [] as $kh)
                                <option value="{{ $kh->id }}">{{ $kh->ho_ten_chu_re ?? '' }} / {{ $kh->ho_ten_co_dau ?? '' }} ({{ $kh->email_hoac_sdt_chu_re ?? $kh->email_hoac_sdt_co_dau ?? '—' }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12 col-sm-6 col-lg-3">
                            <label class="form-label" for="sua_dia_diem">Địa điểm</label>
                            <input type="text" class="form-control" id="sua_dia_diem" name="dia_diem" placeholder="Địa điểm chụp">
                        </div>
                        <div class="col-12 col-sm-6 col-lg-3">
                            <label class="form-label" for="sua_ngay_chup">Ngày chụp</label>
                            <input type="text" class="flatpickr-date-admin form-control" id="sua_ngay_chup" name="ngay_chup" placeholder="dd/mm/yyyy" autocomplete="off">
                        </div>
                        <div class="col-12 col-sm-6 col-lg-3">
                            <label class="form-label" for="sua_ngay_hen_tra_hang">Ngày hẹn trả hàng</label>
                            <input type="text" class="flatpickr-date-admin form-control" id="sua_ngay_hen_tra_hang" name="ngay_hen_tra_hang" placeholder="dd/mm/yyyy" autocomplete="off">
                        </div>
                        {{-- Tabs: Nhóm dịch vụ & Dịch vụ lẻ (Sua) --}}
                        <div class="col-12 mt-2">
                            <label class="form-label d-block">Tham khảo dịch vụ</label>
                            <div class="nav-align-top">
                                <ul class="nav nav-pills mb-4 nav-fill" id="tabDichVuSuaHopDong" role="tablist">
                                    <li class="nav-item mb-1 mb-sm-0" role="presentation">
                                        <button type="button" class="nav-link active" id="tab-nhom-dich-vu-sua-btn" role="tab" data-bs-toggle="tab" data-bs-target="#tab-nhom-dich-vu-sua" aria-controls="tab-nhom-dich-vu-sua" aria-selected="true">Nhóm dịch vụ</button>
                                    </li>
                                    <li class="nav-item mb-1 mb-sm-0" role="presentation">
                                        <button type="button" class="nav-link" id="tab-dich-vu-le-sua-btn" role="tab" data-bs-toggle="tab" data-bs-target="#tab-dich-vu-le-sua" aria-controls="tab-dich-vu-le-sua" aria-selected="false">Dịch vụ lẻ</button>
                                    </li>
                                </ul>
                                <div class="tab-content p-3 bg-light rounded" id="tabDichVuSuaHopDongContent">
                                <div class="tab-pane fade show active" id="tab-nhom-dich-vu-sua" role="tabpanel">
                                    <div class="table-responsive" style="max-height: 220px; overflow-y: auto;">
                                        <table class="table table-sm table-hover table-bordered mb-0" id="tableNhomDichVuSua">
                                            <thead class="table-light sticky-top">
                                                <tr>
                                                    <th style="width: 40px;" class="text-center">Chọn</th>
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
                                                @php
                                                    $dvlList = $ndv->dichVuLe->map(fn($d) => [
                                                        'id' => $d->id,
                                                        'ten_dich_vu' => $d->ten_dich_vu ?? '—',
                                                        'ma_dich_vu' => $d->ma_dich_vu ?? '—',
                                                        'gia_goc' => $d->gia_dich_vu !== null ? (float)$d->gia_dich_vu : 0,
                                                        'gia_dich_vu' => $d->gia_dich_vu !== null ? number_format((float)$d->gia_dich_vu, 0, ',', '.') . ' đ' : '—',
                                                        'ghi_chu' => $d->ghi_chu ? \Illuminate\Support\Str::limit($d->ghi_chu, 40) : '—',
                                                        'so_luong' => $d->pivot->so_luong ?? 1,
                                                    ])->values()->toArray();
                                                @endphp
                                                <tr data-nhom-id="{{ $ndv->id }}"
                                                    data-ten-nhom="{{ e($ndv->ten_nhom ?? '') }}"
                                                    data-dich-vu-le='{{ json_encode($dvlList, JSON_HEX_APOS | JSON_HEX_QUOT | JSON_UNESCAPED_UNICODE) }}'>
                                                    <td class="text-center">
                                                        <input type="checkbox" class="form-check-input cb-nhom-dich-vu-sua" value="{{ $ndv->id }}" aria-label="Chọn nhóm {{ $ndv->ten_nhom }}">
                                                    </td>
                                                    <td>{{ $idx + 1 }}</td>
                                                    <td>{{ $ndv->ten_nhom ?? '—' }}</td>
                                                    <td>{{ $ndv->ma_nhom ?? '—' }}</td>
                                                    <td class="text-end">{{ $ndv->gia_tien !== null ? number_format((float)$ndv->gia_tien, 0, ',', '.') . ' đ' : '—' }}</td>
                                                    <td class="text-end">{{ $ndv->gia_goc !== null ? number_format((float)$ndv->gia_goc, 0, ',', '.') . ' đ' : '—' }}</td>
                                                    <td>{{ $ndv->the ?? '—' }}</td>
                                                    <td>{{ $ndv->ghi_chu ? str($ndv->ghi_chu)->limit(40) : '—' }}</td>
                                                </tr>
                                                @empty
                                                <tr><td colspan="8" class="text-center text-muted py-3">Chưa có nhóm dịch vụ.</td></tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="tab-dich-vu-le-sua" role="tabpanel">
                                    <div class="table-responsive" style="max-height: 220px; overflow-y: auto;">
                                        <table class="table table-sm table-hover table-bordered mb-0" id="tableDichVuLeSua">
                                            <thead class="table-light sticky-top">
                                                <tr>
                                                    <th style="width: 40px;" class="text-center">Chọn</th>
                                                    <th style="width: 50px;">STT</th>
                                                    <th>Tên dịch vụ</th>
                                                    <th>Mã</th>
                                                    <th class="text-end">Giá dịch vụ</th>
                                                    <th>Ghi chú</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($danhSachDichVuLe ?? [] as $idx => $dvl)
                                                @php
                                                    $dvlItem = [
                                                        'id' => $dvl->id,
                                                        'ten_dich_vu' => $dvl->ten_dich_vu ?? '—',
                                                        'ma_dich_vu' => $dvl->ma_dich_vu ?? '—',
                                                        'gia_goc' => $dvl->gia_dich_vu !== null ? (float)$dvl->gia_dich_vu : 0,
                                                        'gia_dich_vu' => $dvl->gia_dich_vu !== null ? number_format((float)$dvl->gia_dich_vu, 0, ',', '.') . ' đ' : '—',
                                                        'ghi_chu' => $dvl->ghi_chu ? \Illuminate\Support\Str::limit($dvl->ghi_chu, 40) : '—',
                                                        'so_luong' => 1,
                                                    ];
                                                @endphp
                                                <tr data-dich-vu-le='{{ json_encode($dvlItem, JSON_HEX_APOS | JSON_HEX_QUOT | JSON_UNESCAPED_UNICODE) }}'>
                                                    <td class="text-center">
                                                        <input type="checkbox" class="form-check-input cb-dich-vu-le-sua" value="{{ $dvl->id }}" aria-label="Chọn dịch vụ {{ $dvl->ten_dich_vu }}">
                                                    </td>
                                                    <td>{{ $idx + 1 }}</td>
                                                    <td>{{ $dvl->ten_dich_vu ?? '—' }}</td>
                                                    <td>{{ $dvl->ma_dich_vu ?? '—' }}</td>
                                                    <td class="text-end">{{ $dvl->gia_dich_vu !== null ? number_format((float)$dvl->gia_dich_vu, 0, ',', '.') . ' đ' : '—' }}</td>
                                                    <td>{{ $dvl->ghi_chu ? str($dvl->ghi_chu)->limit(40) : '—' }}</td>
                                                </tr>
                                                @empty
                                                <tr><td colspan="6" class="text-center text-muted py-3">Chưa có dịch vụ lẻ.</td></tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                </div>
                            </div>
                        </div>
                        {{-- Bảng: Dịch vụ lẻ của nhóm đã chọn (Sua) --}}
                        <div class="col-12 d-none" id="boxDichVuLeTheoNhomSua">
                            <label class="form-label fw-medium">Dịch vụ lẻ của nhóm đã chọn</label>
                            <div class="table-responsive border rounded" style="max-height: 200px; overflow-y: auto;">
                                <table class="table table-sm table-bordered mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th style="width: 40px;" class="text-center">Chọn</th>
                                            <th>Nhóm dịch vụ</th>
                                            <th style="width: 50px;">STT</th>
                                            <th>Tên dịch vụ</th>
                                            <th>Mã</th>
                                            <th class="text-end">Giá gốc</th>
                                            <th class="text-end">Giá thực</th>
                                            <th>Ghi chú</th>
                                        </tr>
                                    </thead>
                                    <tbody id="tbodyDichVuLeTheoNhomSua"></tbody>
                                    <tfoot id="tfootDichVuLeTheoNhomSua" class="table-light">
                                        <tr>
                                            <td colspan="5" class="text-end fw-medium">Tổng</td>
                                            <td class="text-end fw-medium" id="tdTongGiaGocSua">0 đ</td>
                                            <td class="text-end fw-medium" id="tdTongGiaThucSua">0 đ</td>
                                            <td></td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
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
                        <div class="col-12 col-sm-6 col-lg-3">
                            <label class="form-label" for="sua_tong_tien">Tổng tiền</label>
                            <input type="number" class="form-control" id="sua_tong_tien" name="tong_tien" min="0" step="0.01">
                        </div>
                        <div class="col-12 col-sm-6 col-lg-3">
                            <label class="form-label" for="sua_thanh_toan_lan_1">Thanh toán lần 1</label>
                            <input type="number" class="form-control" id="sua_thanh_toan_lan_1" name="thanh_toan_lan_1" min="0" step="0.01">
                        </div>
                        <div class="col-12 col-sm-6 col-lg-3">
                            <label class="form-label" for="sua_thanh_toan_lan_2">Thanh toán lần 2</label>
                            <input type="number" class="form-control" id="sua_thanh_toan_lan_2" name="thanh_toan_lan_2" min="0" step="0.01">
                        </div>
                        <div class="col-12 col-sm-6 col-lg-3">
                            <label class="form-label" for="sua_thanh_toan_lan_3">Thanh toán lần 3</label>
                            <input type="number" class="form-control" id="sua_thanh_toan_lan_3" name="thanh_toan_lan_3" min="0" step="0.01">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <a href="{{ route('admin.khach-hang.danh-sach') }}" target="_blank" rel="noopener noreferrer" class="btn btn-outline-primary">
                         Thêm khách hàng
                    </a>
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
                <p class="mb-0">Bạn có chắc muốn xóa hợp đồng này?</p>
                <p class="text-warning mb-0 mt-2 small">
                    <i class="fa-solid fa-triangle-exclamation me-1"></i>
                    Toàn bộ bản ghi dịch vụ trong hợp đồng (bảng dịch vụ trong hợp đồng) cũng sẽ bị xóa theo. Thao tác không thể hoàn tác.
                </p>
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
#modalThemHopDong .modal-dialog.modal-hop-dong,
#modalSuaHopDong .modal-dialog.modal-hop-dong {
    max-width: 95vw;
    width: 1200px;
    max-height: calc(100vh - 2rem);
}
@media (min-width: 992px) {
    #modalThemHopDong .modal-dialog.modal-hop-dong,
    #modalSuaHopDong .modal-dialog.modal-hop-dong {
        max-width: 1200px;
    }
}
/* Modal thêm/sửa hợp đồng: chỉ phần nội dung form cuộn, footer luôn hiển thị */
#modalThemHopDong .modal-content,
#modalSuaHopDong .modal-content {
    max-height: calc(100vh - 2rem);
    display: flex;
    flex-direction: column;
}
#modalThemHopDong .modal-content .modal-header,
#modalSuaHopDong .modal-content .modal-header,
#modalThemHopDong .modal-content .modal-footer,
#modalSuaHopDong .modal-content .modal-footer {
    flex-shrink: 0;
}
#modalThemHopDong .modal-content form,
#modalSuaHopDong .modal-content form {
    display: flex;
    flex-direction: column;
    flex: 1 1 auto;
    min-height: 0;
}
#modalThemHopDong .modal-content form .modal-body,
#modalSuaHopDong .modal-content form .modal-body {
    flex: 1 1 auto;
    min-height: 0;
    overflow-y: auto;
}
#modalThemHopDong .modal-content form .modal-body.py-0,
#modalSuaHopDong .modal-content form .modal-body.py-0 {
    flex: 0 0 auto;
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

    // Modal Sửa: gán data vào form (cấu trúc giống Thêm mới, khách hàng disabled)
    var modalSua = document.getElementById('modalSuaHopDong');
    var formSua = document.getElementById('formSuaHopDong');
    if (modalSua && formSua) {
        modalSua.addEventListener('show.bs.modal', function(e) {
            var btn = e.relatedTarget;
            if (!btn || !btn.classList.contains('btn-sua-hop-dong')) return;
            var url = btn.getAttribute('data-url');
            if (url) formSua.action = url;
            var khachHangId = btn.getAttribute('data-khach-hang-id') || '';
            document.getElementById('sua_khach_hang_id').value = khachHangId;
            document.getElementById('sua_khach_hang_id_hidden').value = khachHangId;
            document.getElementById('sua_dia_diem').value = btn.getAttribute('data-dia-diem') || '';
            if (window.setAdminDateInput) { setAdminDateInput('sua_ngay_chup', btn.getAttribute('data-ngay-chup') || ''); setAdminDateInput('sua_ngay_hen_tra_hang', btn.getAttribute('data-ngay-hen-tra-hang') || ''); } else { document.getElementById('sua_ngay_chup').value = btn.getAttribute('data-ngay-chup') || ''; document.getElementById('sua_ngay_hen_tra_hang').value = btn.getAttribute('data-ngay-hen-tra-hang') || ''; }
            document.getElementById('sua_trang_phuc').value = btn.getAttribute('data-trang-phuc') || '';
            document.getElementById('sua_concept').value = btn.getAttribute('data-concept') || '';
            document.getElementById('sua_ghi_chu_chup').value = btn.getAttribute('data-ghi-chu-chup') || '';
            document.getElementById('sua_tong_tien').value = btn.getAttribute('data-tong-tien') || '';
            document.getElementById('sua_thanh_toan_lan_1').value = btn.getAttribute('data-thanh-toan-lan-1') || '';
            document.getElementById('sua_thanh_toan_lan_2').value = btn.getAttribute('data-thanh-toan-lan-2') || '';
            document.getElementById('sua_thanh_toan_lan_3').value = btn.getAttribute('data-thanh-toan-lan-3') || '';
            // Reset tab dịch vụ (Sua)
            var tableNhomSua = document.getElementById('tableNhomDichVuSua');
            var tableDichVuLeSua = document.getElementById('tableDichVuLeSua');
            var boxDichVuLeSua = document.getElementById('boxDichVuLeTheoNhomSua');
            var tbodyDichVuLeSua = document.getElementById('tbodyDichVuLeTheoNhomSua');
            if (tableNhomSua) tableNhomSua.querySelectorAll('.cb-nhom-dich-vu-sua').forEach(function(cb) { cb.checked = false; });
            if (tableDichVuLeSua) tableDichVuLeSua.querySelectorAll('.cb-dich-vu-le-sua').forEach(function(cb) { cb.checked = false; });
            if (boxDichVuLeSua) boxDichVuLeSua.classList.add('d-none');
            if (tbodyDichVuLeSua) tbodyDichVuLeSua.innerHTML = '';

            // Load dịch vụ đã lưu của hợp đồng từ dich_vu_trong_hop_dong và đổ vào bảng (Sua)
            var dichVuUrl = btn.getAttribute('data-dich-vu-url');
            if (dichVuUrl && tbodyDichVuLeSua && boxDichVuLeSua) {
                fetch(dichVuUrl, { headers: { 'Accept': 'application/json' } })
                    .then(function(res) { return res.json(); })
                    .then(function(json) {
                        var data = (json && json.data) ? json.data : [];
                        tbodyDichVuLeSua.innerHTML = '';
                        if (!Array.isArray(data) || data.length === 0) {
                            boxDichVuLeSua.classList.add('d-none');
                            return;
                        }
                        boxDichVuLeSua.classList.remove('d-none');
                        data.forEach(function(row, idx) {
                            var stt = idx + 1;
                            var giaGoc = row.gia_goc != null ? Number(row.gia_goc) : 0;
                            var giaThuc = row.gia_thuc != null ? Number(row.gia_thuc) : giaGoc;
                            var tr = document.createElement('tr');
                            tr.setAttribute('data-dich-vu-le-id', row.id_dich_vu);
                            tr.setAttribute('data-gia-goc', String(giaGoc));
                            tr.innerHTML =
                                '<td class="text-center"><input type="checkbox" class="form-check-input cb-dich-vu-le-hop-dong" checked value="' + escapeHtml(String(row.id_dich_vu)) + '" aria-label="Chọn lưu dịch vụ"></td>' +
                                '<td>' + escapeHtml('—') + '</td>' +
                                '<td>' + stt + '</td>' +
                                '<td>' + escapeHtml(row.ten_dich_vu) + '</td>' +
                                '<td>' + escapeHtml(row.ma_dich_vu) + '</td>' +
                                '<td class="text-end">' + escapeHtml(formatMoney(giaGoc) + ' đ') + '</td>' +
                                '<td class="text-end"><input type="number" class="form-control form-control-sm input-gia-thuc" min="0" step="10" value="' + escapeHtml(String(giaThuc)) + '" style="width: 100px; display: inline-block;" placeholder="Tròn chục" inputmode="numeric"></td>' +
                                '<td>' + escapeHtml(row.ghi_chu) + '</td>';
                            tbodyDichVuLeSua.appendChild(tr);
                        });
                        updateTongDichVuLeTheoNhomSua();
                    })
                    .catch(function() {
                        // Nếu lỗi thì không chặn mở modal, chỉ không hiển thị dịch vụ
                        boxDichVuLeSua.classList.add('d-none');
                        tbodyDichVuLeSua.innerHTML = '';
                    });
            }
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

    // Tab Nhóm dịch vụ + Tab Dịch vụ lẻ: gộp vào bảng "Dịch vụ lẻ của nhóm đã chọn"
    var tableNhom = document.getElementById('tableNhomDichVuThem');
    var tableDichVuLe = document.getElementById('tableDichVuLeThem');
    var boxDichVuLe = document.getElementById('boxDichVuLeTheoNhom');
    var tbodyDichVuLe = document.getElementById('tbodyDichVuLeTheoNhom');

    function renderDichVuLeTheoNhom() {
        if (!tbodyDichVuLe || !boxDichVuLe) return;
        var checkedNhomRows = [];
        if (tableNhom) {
            tableNhom.querySelectorAll('tbody tr[data-nhom-id]').forEach(function(row) {
                if (row.querySelector('.cb-nhom-dich-vu') && row.querySelector('.cb-nhom-dich-vu').checked) checkedNhomRows.push(row);
            });
        }
        var checkedDichVuLeRows = [];
        if (tableDichVuLe) {
            tableDichVuLe.querySelectorAll('tbody tr[data-dich-vu-le]').forEach(function(row) {
                if (row.querySelector('.cb-dich-vu-le') && row.querySelector('.cb-dich-vu-le').checked) checkedDichVuLeRows.push(row);
            });
        }
        var allItems = [];
        checkedNhomRows.forEach(function(row) {
            var tenNhom = row.getAttribute('data-ten-nhom') || '—';
            var json = row.getAttribute('data-dich-vu-le');
            var list = [];
            try { list = json ? JSON.parse(json) : []; } catch (e) {}
            list.forEach(function(dvl) {
                allItems.push({ tenNhom: tenNhom, dvl: dvl });
            });
        });
        checkedDichVuLeRows.forEach(function(row) {
            var json = row.getAttribute('data-dich-vu-le');
            var dvl = null;
            try { dvl = json ? JSON.parse(json) : null; } catch (e) {}
            if (dvl) allItems.push({ tenNhom: '—', dvl: dvl });
        });
        var seenMa = {};
        var uniqueItems = [];
        allItems.forEach(function(item) {
            var ma = (item.dvl.ma_dich_vu != null && item.dvl.ma_dich_vu !== '') ? String(item.dvl.ma_dich_vu).trim() : null;
            var key = ma !== null ? ma : '__empty_' + (item.dvl.id != null ? item.dvl.id : uniqueItems.length);
            if (seenMa[key]) return;
            seenMa[key] = true;
            uniqueItems.push(item);
        });
        tbodyDichVuLe.innerHTML = '';
        if (uniqueItems.length === 0) {
            if (checkedNhomRows.length > 0) {
                boxDichVuLe.classList.remove('d-none');
                var tr = document.createElement('tr');
                tr.innerHTML = '<td colspan="8" class="text-muted">Nhóm đã chọn chưa có dịch vụ lẻ.</td>';
                tbodyDichVuLe.appendChild(tr);
                updateTongDichVuLeTheoNhom();
            } else {
                boxDichVuLe.classList.add('d-none');
            }
            return;
        }
        boxDichVuLe.classList.remove('d-none');
        uniqueItems.forEach(function(item, idx) {
            var tenNhom = item.tenNhom;
            var dvl = item.dvl;
            var stt = idx + 1;
            var giaGoc = dvl.gia_goc != null ? Number(dvl.gia_goc) : 0;
            var giaThucTronChuc = Math.round(giaGoc / 10) * 10;
            var giaGocDisplay = dvl.gia_dich_vu != null && dvl.gia_dich_vu !== '' ? dvl.gia_dich_vu : '—';
            var tr = document.createElement('tr');
            tr.setAttribute('data-dich-vu-le-id', dvl.id);
            tr.setAttribute('data-gia-goc', String(giaGoc));
            tr.innerHTML =
                '<td class="text-center"><input type="checkbox" class="form-check-input cb-dich-vu-le-hop-dong" checked value="' + escapeHtml(String(dvl.id)) + '" aria-label="Chọn lưu dịch vụ"></td>' +
                '<td>' + escapeHtml(tenNhom) + '</td>' +
                '<td>' + stt + '</td>' +
                '<td>' + escapeHtml(dvl.ten_dich_vu) + '</td>' +
                '<td>' + escapeHtml(dvl.ma_dich_vu) + '</td>' +
                '<td class="text-end">' + escapeHtml(giaGocDisplay) + '</td>' +
                '<td class="text-end"><input type="number" class="form-control form-control-sm input-gia-thuc" min="0" step="10" value="' + escapeHtml(String(giaThucTronChuc)) + '" style="width: 100px; display: inline-block;" placeholder="Tròn chục" inputmode="numeric"></td>' +
                '<td>' + escapeHtml(dvl.ghi_chu) + '</td>';
            tbodyDichVuLe.appendChild(tr);
        });
        updateTongDichVuLeTheoNhom();
    }

    function escapeHtml(s) {
        if (s == null || s === '') return '—';
        var div = document.createElement('div');
        div.textContent = s;
        return div.innerHTML;
    }

    function formatMoney(n) {
        var num = parseFloat(n);
        if (isNaN(num)) return '0';
        return Math.round(num).toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
    }

    function updateTongDichVuLeTheoNhom() {
        var tfoot = document.getElementById('tfootDichVuLeTheoNhom');
        var tdTongGiaGoc = document.getElementById('tdTongGiaGoc');
        var tdTongGiaThuc = document.getElementById('tdTongGiaThuc');
        if (!tbodyDichVuLe || !tdTongGiaGoc || !tdTongGiaThuc) return;
        var rows = tbodyDichVuLe.querySelectorAll('tr[data-dich-vu-le-id]');
        var tongGoc = 0, tongThuc = 0;
        rows.forEach(function(tr) {
            tongGoc += parseFloat(tr.getAttribute('data-gia-goc')) || 0;
            var inp = tr.querySelector('.input-gia-thuc');
            tongThuc += inp ? (parseFloat(inp.value) || 0) : 0;
        });
        tdTongGiaGoc.textContent = formatMoney(tongGoc) + ' đ';
        tdTongGiaThuc.textContent = formatMoney(tongThuc) + ' đ';
    }

    // Chỉ cho phép chọn 1 nhóm: khi chọn nhóm khác thì bỏ chọn nhóm hiện tại
    if (tableNhom) {
        tableNhom.addEventListener('change', function(e) {
            if (!e.target.classList.contains('cb-nhom-dich-vu')) return;
            if (e.target.checked) {
                tableNhom.querySelectorAll('.cb-nhom-dich-vu').forEach(function(cb) {
                    if (cb !== e.target) cb.checked = false;
                });
            }
            renderDichVuLeTheoNhom();
        });
    }

    // Tab Dịch vụ lẻ: tích chọn → thêm vào bảng "Dịch vụ lẻ của nhóm đã chọn" (có thể chọn nhiều)
    if (tableDichVuLe) {
        tableDichVuLe.addEventListener('change', function(e) {
            if (e.target.classList.contains('cb-dich-vu-le')) renderDichVuLeTheoNhom();
        });
    }

    // Giá thực: chỉ nhập số tròn chục, không thập phân (10, 240, 2450...). Khi blur làm tròn về bội 10.
    function roundToTen(n) {
        var num = parseFloat(n);
        if (isNaN(num) || num < 0) return 0;
        return Math.round(num / 10) * 10;
    }
    if (boxDichVuLe) {
        boxDichVuLe.addEventListener('input', function(e) {
            if (!e.target.classList.contains('input-gia-thuc')) return;
            var inp = e.target;
            if (inp.value.indexOf('.') !== -1) {
                inp.value = roundToTen(inp.value);
            }
            updateTongDichVuLeTheoNhom();
        });
        boxDichVuLe.addEventListener('change', function(e) {
            if (e.target.classList.contains('input-gia-thuc')) {
                var inp = e.target;
                inp.value = roundToTen(inp.value);
                updateTongDichVuLeTheoNhom();
            }
        });
    }

    // Reset khi mở lại modal Thêm mới (bỏ chọn và ẩn box)
    var modalThemHopDong = document.getElementById('modalThemHopDong');
    if (modalThemHopDong) {
        modalThemHopDong.addEventListener('show.bs.modal', function() {
            if (tableNhom) tableNhom.querySelectorAll('.cb-nhom-dich-vu').forEach(function(cb) { cb.checked = false; });
            if (tableDichVuLe) tableDichVuLe.querySelectorAll('.cb-dich-vu-le').forEach(function(cb) { cb.checked = false; });
            if (boxDichVuLe) { boxDichVuLe.classList.add('d-none'); }
            if (tbodyDichVuLe) tbodyDichVuLe.innerHTML = '';
        });
    }

    // Submit form Thêm HĐ: chỉ gửi dịch vụ lẻ được tích chọn (kèm giá gốc, giá thực)
    var formThemHopDong = document.getElementById('formThemHopDong');
    if (formThemHopDong && tbodyDichVuLe) {
        formThemHopDong.addEventListener('submit', function() {
            document.querySelectorAll('#formThemHopDong input[name^="dich_vu_le_hop_dong"]').forEach(function(el) { el.remove(); });
            var index = 0;
            tbodyDichVuLe.querySelectorAll('tr[data-dich-vu-le-id]').forEach(function(tr) {
                var cb = tr.querySelector('.cb-dich-vu-le-hop-dong');
                if (!cb || !cb.checked) return;
                var dichVuLeId = tr.getAttribute('data-dich-vu-le-id');
                var giaGoc = tr.getAttribute('data-gia-goc') || '0';
                var inputGiaThuc = tr.querySelector('.input-gia-thuc');
                var giaThuc = inputGiaThuc ? inputGiaThuc.value : giaGoc;
                var prefix = 'dich_vu_le_hop_dong[' + index + ']';
                [ { n: prefix + '[dich_vu_le_id]', v: dichVuLeId }, { n: prefix + '[gia_goc]', v: String(giaGoc) }, { n: prefix + '[gia_thuc]', v: String(giaThuc) } ].forEach(function(o) {
                    var inp = document.createElement('input');
                    inp.type = 'hidden';
                    inp.name = o.n;
                    inp.value = o.v;
                    formThemHopDong.appendChild(inp);
                });
                index++;
            });
        });
    }

    // --- Modal Sửa: tab Nhóm dịch vụ + Dịch vụ lẻ → bảng "Dịch vụ lẻ của nhóm đã chọn" (logic giống Thêm) ---
    var tableNhomSua = document.getElementById('tableNhomDichVuSua');
    var tableDichVuLeSua = document.getElementById('tableDichVuLeSua');
    var boxDichVuLeSua = document.getElementById('boxDichVuLeTheoNhomSua');
    var tbodyDichVuLeSua = document.getElementById('tbodyDichVuLeTheoNhomSua');

    function renderDichVuLeTheoNhomSua() {
        if (!tbodyDichVuLeSua || !boxDichVuLeSua) return;
        var checkedNhomRows = [];
        if (tableNhomSua) {
            tableNhomSua.querySelectorAll('tbody tr[data-nhom-id]').forEach(function(row) {
                if (row.querySelector('.cb-nhom-dich-vu-sua') && row.querySelector('.cb-nhom-dich-vu-sua').checked) checkedNhomRows.push(row);
            });
        }
        var checkedDichVuLeRows = [];
        if (tableDichVuLeSua) {
            tableDichVuLeSua.querySelectorAll('tbody tr[data-dich-vu-le]').forEach(function(row) {
                if (row.querySelector('.cb-dich-vu-le-sua') && row.querySelector('.cb-dich-vu-le-sua').checked) checkedDichVuLeRows.push(row);
            });
        }
        var allItems = [];
        checkedNhomRows.forEach(function(row) {
            var tenNhom = row.getAttribute('data-ten-nhom') || '—';
            var json = row.getAttribute('data-dich-vu-le');
            var list = [];
            try { list = json ? JSON.parse(json) : []; } catch (e) {}
            list.forEach(function(dvl) {
                allItems.push({ tenNhom: tenNhom, dvl: dvl });
            });
        });
        checkedDichVuLeRows.forEach(function(row) {
            var json = row.getAttribute('data-dich-vu-le');
            var dvl = null;
            try { dvl = json ? JSON.parse(json) : null; } catch (e) {}
            if (dvl) allItems.push({ tenNhom: '—', dvl: dvl });
        });
        var seenMa = {};
        var uniqueItems = [];
        allItems.forEach(function(item) {
            var ma = (item.dvl.ma_dich_vu != null && item.dvl.ma_dich_vu !== '') ? String(item.dvl.ma_dich_vu).trim() : null;
            var key = ma !== null ? ma : '__empty_' + (item.dvl.id != null ? item.dvl.id : uniqueItems.length);
            if (seenMa[key]) return;
            seenMa[key] = true;
            uniqueItems.push(item);
        });
        tbodyDichVuLeSua.innerHTML = '';
        if (uniqueItems.length === 0) {
            if (checkedNhomRows.length > 0) {
                boxDichVuLeSua.classList.remove('d-none');
                var tr = document.createElement('tr');
                tr.innerHTML = '<td colspan="8" class="text-muted">Nhóm đã chọn chưa có dịch vụ lẻ.</td>';
                tbodyDichVuLeSua.appendChild(tr);
                updateTongDichVuLeTheoNhomSua();
            } else {
                boxDichVuLeSua.classList.add('d-none');
            }
            return;
        }
        boxDichVuLeSua.classList.remove('d-none');
        uniqueItems.forEach(function(item, idx) {
            var tenNhom = item.tenNhom;
            var dvl = item.dvl;
            var stt = idx + 1;
            var giaGoc = dvl.gia_goc != null ? Number(dvl.gia_goc) : 0;
            var giaThucTronChuc = Math.round(giaGoc / 10) * 10;
            var giaGocDisplay = dvl.gia_dich_vu != null && dvl.gia_dich_vu !== '' ? dvl.gia_dich_vu : '—';
            var tr = document.createElement('tr');
            tr.setAttribute('data-dich-vu-le-id', dvl.id);
            tr.setAttribute('data-gia-goc', String(giaGoc));
            tr.innerHTML =
                '<td class="text-center"><input type="checkbox" class="form-check-input cb-dich-vu-le-hop-dong" checked value="' + escapeHtml(String(dvl.id)) + '" aria-label="Chọn lưu dịch vụ"></td>' +
                '<td>' + escapeHtml(tenNhom) + '</td>' +
                '<td>' + stt + '</td>' +
                '<td>' + escapeHtml(dvl.ten_dich_vu) + '</td>' +
                '<td>' + escapeHtml(dvl.ma_dich_vu) + '</td>' +
                '<td class="text-end">' + escapeHtml(giaGocDisplay) + '</td>' +
                '<td class="text-end"><input type="number" class="form-control form-control-sm input-gia-thuc" min="0" step="10" value="' + escapeHtml(String(giaThucTronChuc)) + '" style="width: 100px; display: inline-block;" placeholder="Tròn chục" inputmode="numeric"></td>' +
                '<td>' + escapeHtml(dvl.ghi_chu) + '</td>';
            tbodyDichVuLeSua.appendChild(tr);
        });
        updateTongDichVuLeTheoNhomSua();
    }

    function updateTongDichVuLeTheoNhomSua() {
        var tdTongGiaGocSua = document.getElementById('tdTongGiaGocSua');
        var tdTongGiaThucSua = document.getElementById('tdTongGiaThucSua');
        if (!tbodyDichVuLeSua || !tdTongGiaGocSua || !tdTongGiaThucSua) return;
        var rows = tbodyDichVuLeSua.querySelectorAll('tr[data-dich-vu-le-id]');
        var tongGoc = 0, tongThuc = 0;
        rows.forEach(function(tr) {
            tongGoc += parseFloat(tr.getAttribute('data-gia-goc')) || 0;
            var inp = tr.querySelector('.input-gia-thuc');
            tongThuc += inp ? (parseFloat(inp.value) || 0) : 0;
        });
        tdTongGiaGocSua.textContent = formatMoney(tongGoc) + ' đ';
        tdTongGiaThucSua.textContent = formatMoney(tongThuc) + ' đ';
    }

    if (tableNhomSua) {
        tableNhomSua.addEventListener('change', function(e) {
            if (!e.target.classList.contains('cb-nhom-dich-vu-sua')) return;
            if (e.target.checked) {
                tableNhomSua.querySelectorAll('.cb-nhom-dich-vu-sua').forEach(function(cb) {
                    if (cb !== e.target) cb.checked = false;
                });
            }
            renderDichVuLeTheoNhomSua();
        });
    }
    if (tableDichVuLeSua) {
        tableDichVuLeSua.addEventListener('change', function(e) {
            if (e.target.classList.contains('cb-dich-vu-le-sua')) renderDichVuLeTheoNhomSua();
        });
    }
    if (boxDichVuLeSua) {
        boxDichVuLeSua.addEventListener('input', function(e) {
            if (!e.target.classList.contains('input-gia-thuc')) return;
            var inp = e.target;
            if (inp.value.indexOf('.') !== -1) inp.value = roundToTen(inp.value);
            updateTongDichVuLeTheoNhomSua();
        });
        boxDichVuLeSua.addEventListener('change', function(e) {
            if (e.target.classList.contains('input-gia-thuc')) {
                e.target.value = roundToTen(e.target.value);
                updateTongDichVuLeTheoNhomSua();
            }
        });
    }

    // Submit form Sửa HĐ: gửi dịch vụ lẻ được tích chọn (giống Thêm)
    if (formSua && tbodyDichVuLeSua) {
        formSua.addEventListener('submit', function() {
            document.querySelectorAll('#formSuaHopDong input[name^="dich_vu_le_hop_dong"]').forEach(function(el) { el.remove(); });
            var index = 0;
            tbodyDichVuLeSua.querySelectorAll('tr[data-dich-vu-le-id]').forEach(function(tr) {
                var cb = tr.querySelector('.cb-dich-vu-le-hop-dong');
                if (!cb || !cb.checked) return;
                var dichVuLeId = tr.getAttribute('data-dich-vu-le-id');
                var giaGoc = tr.getAttribute('data-gia-goc') || '0';
                var inputGiaThuc = tr.querySelector('.input-gia-thuc');
                var giaThuc = inputGiaThuc ? inputGiaThuc.value : giaGoc;
                var prefix = 'dich_vu_le_hop_dong[' + index + ']';
                [ { n: prefix + '[dich_vu_le_id]', v: dichVuLeId }, { n: prefix + '[gia_goc]', v: String(giaGoc) }, { n: prefix + '[gia_thuc]', v: String(giaThuc) } ].forEach(function(o) {
                    var inp = document.createElement('input');
                    inp.type = 'hidden';
                    inp.name = o.n;
                    inp.value = o.v;
                    formSua.appendChild(inp);
                });
                index++;
            });
        });
    }
});
</script>
@endpush
@endsection
