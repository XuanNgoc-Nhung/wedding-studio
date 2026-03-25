@extends('admin.layouts.app')

@section('title', 'Tổng quan | Wedding Studio')

@section('content')
<h4 class="py-3 mb-2">Tổng quan</h4>
<p class="text-muted mb-4">Thống kê nhanh 6 tháng gần nhất (giá trị HĐ &amp; phiếu thu) và trạng thái hợp đồng.</p>

<div class="row g-4 mb-4">
    <div class="col-sm-6 col-xl-3">
        <div class="card h-100 shadow-none border">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <span class="d-block text-muted mb-1">Nhân viên</span>
                        <h3 class="mb-0">{{ number_format($tongNhanVien) }}</h3>
                        <a href="{{ route('admin.nhan-su.danh-sach') }}" class="small stretched-link">Danh sách nhân sự</a>
                    </div>
                    <span class="badge rounded-pill bg-label-primary p-2">
                        <i class="fa-solid fa-users fa-lg"></i>
                    </span>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card h-100 shadow-none border">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <span class="d-block text-muted mb-1">Khách hàng</span>
                        <h3 class="mb-0">{{ number_format($tongKhachHang) }}</h3>
                        <a href="{{ route('admin.khach-hang.danh-sach') }}" class="small stretched-link">Danh sách khách hàng</a>
                    </div>
                    <span class="badge rounded-pill bg-label-success p-2">
                        <i class="fa-solid fa-user-group fa-lg"></i>
                    </span>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card h-100 shadow-none border">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <span class="d-block text-muted mb-1">Hợp đồng</span>
                        <h3 class="mb-0">{{ number_format($tongHopDong) }}</h3>
                        <a href="{{ route('admin.khach-hang.hop-dong') }}" class="small stretched-link">Quản lý hợp đồng</a>
                    </div>
                    <span class="badge rounded-pill bg-label-info p-2">
                        <i class="fa-solid fa-file-contract fa-lg"></i>
                    </span>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card h-100 shadow-none border">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <span class="d-block text-muted mb-1">Công nợ phải thu</span>
                        <h3 class="mb-0 text-danger">{{ number_format($tongCongNo, 0, ',', '.') }} ₫</h3>
                        <span class="small text-muted">{{ number_format($soHopDongConNo) }} HĐ còn dư nợ</span><br>
                        <a href="{{ route('admin.tai-chinh.cong-no') }}" class="small stretched-link">Chi tiết công nợ</a>
                    </div>
                    <span class="badge rounded-pill bg-label-warning p-2">
                        <i class="fa-solid fa-file-invoice-dollar fa-lg"></i>
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-lg-4">
        <div class="card h-100 shadow-none border">
            <h5 class="card-header">Tài chính hợp đồng</h5>
            <div class="card-body">
                <ul class="list-unstyled mb-0 small">
                    <li class="d-flex justify-content-between py-2 border-bottom">
                        <span class="text-muted">Tổng giá trị sau giảm giá</span>
                        <span class="fw-semibold">{{ number_format($tongGiaTriHopDong, 0, ',', '.') }} ₫</span>
                    </li>
                    <li class="d-flex justify-content-between py-2 border-bottom">
                        <span class="text-muted">Đã thu (3 lần TT trên HĐ)</span>
                        <span class="fw-semibold text-success">{{ number_format($tongDaThuTuHopDong, 0, ',', '.') }} ₫</span>
                    </li>
                    <li class="d-flex justify-content-between py-2">
                        <span class="text-muted">Còn phải thu (≥ 0)</span>
                        <span class="fw-semibold text-danger">{{ number_format($tongCongNo, 0, ',', '.') }} ₫</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <div class="col-lg-8">
        <div class="card h-100 shadow-none border">
            <h5 class="card-header">Giá trị HĐ mới &amp; phiếu thu theo tháng (6 tháng gần nhất)</h5>
            <div class="card-body">
                <p class="text-muted small mb-3">
                    <strong>Giá trị HĐ mới</strong>: tổng (tổng tiền − giảm giá) của hợp đồng theo <strong>tháng tạo</strong>.
                    <strong>Phiếu thu</strong>: phiếu thu đã duyệt / hoàn thành, gom theo tháng của ngày duyệt (hoặc ngày tạo nếu chưa có).
                </p>
                <div id="chartDoanhThuThang" style="min-height: 320px;"></div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-xl-5">
        <div class="card h-100 shadow-none border">
            <h5 class="card-header">Hợp đồng theo trạng thái</h5>
            <div class="card-body">
                @if($tongHopDong === 0)
                <p class="text-muted mb-0">Chưa có dữ liệu hợp đồng.</p>
                @else
                <div id="chartTrangThaiHopDong" style="min-height: 300px;"></div>
                <div class="table-responsive text-nowrap mt-3">
                    <table class="table table-sm table-bordered mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Trạng thái HĐ</th>
                                <th class="text-end" style="width: 100px;">Số lượng</th>
                                <th class="text-end" style="width: 100px;">Tỷ lệ</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($hopDongTheoTrangThai as $row)
                            <tr>
                                <td>{{ $row['trang_thai'] }}</td>
                                <td class="text-end">{{ number_format($row['so_luong']) }}</td>
                                <td class="text-end">
                                    {{ $tongHopDong > 0 ? number_format($row['so_luong'] / $tongHopDong * 100, 1) : '0' }}%
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @endif
            </div>
        </div>
    </div>
    <div class="col-xl-7">
        <div class="card h-100 shadow-none border">
            <h5 class="card-header">Bảng theo tháng (6 tháng gần nhất)</h5>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover table-bordered mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Tháng</th>
                                <th class="text-end">Giá trị HĐ mới</th>
                                <th class="text-end">Phiếu thu đã duyệt</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($bangDoanhThuThang as $r)
                            <tr>
                                <td>{{ $r['label'] }}</td>
                                <td class="text-end">{{ number_format($r['gia_tri_hd_moi'], 0, ',', '.') }} ₫</td>
                                <td class="text-end">{{ number_format($r['phieu_thu_duyet'], 0, ',', '.') }} ₫</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
(function () {
    var labels = @json($thangNhanLabels);
    var giaTriHd = @json($seriesGiaTriHopDong);
    var phieuThu = @json($seriesPhieuThu);

    var fmt = function (val) {
        if (typeof val !== 'number') return val;
        return new Intl.NumberFormat('vi-VN').format(Math.round(val)) + ' ₫';
    };

    if (document.getElementById('chartDoanhThuThang') && typeof ApexCharts !== 'undefined') {
        new ApexCharts(document.querySelector('#chartDoanhThuThang'), {
            chart: { type: 'area', height: 320, toolbar: { show: false }, fontFamily: 'inherit' },
            stroke: { curve: 'smooth', width: [3, 3] },
            fill: {
                type: 'gradient',
                gradient: { shadeIntensity: 0.4, opacityFrom: 0.35, opacityTo: 0.05 }
            },
            dataLabels: { enabled: false },
            colors: ['#7367f0', '#28c76f'],
            series: [
                { name: 'Giá trị HĐ mới', data: giaTriHd },
                { name: 'Phiếu thu đã duyệt', data: phieuThu }
            ],
            xaxis: { categories: labels, labels: { rotate: -45, rotateAlways: labels.length > 7 } },
            yaxis: {
                labels: {
                    formatter: function (v) { return new Intl.NumberFormat('vi-VN', { notation: 'compact' }).format(v); }
                }
            },
            tooltip: { y: { formatter: fmt } },
            legend: { position: 'top', horizontalAlign: 'left' },
            grid: { borderColor: '#e6e6e8', strokeDashArray: 4 }
        }).render();
    }

    @if($tongHopDong > 0)
    var ttLabels = @json($hopDongTheoTrangThai->pluck('trang_thai')->values());
    var ttSeries = @json($hopDongTheoTrangThai->pluck('so_luong')->values());
    if (document.getElementById('chartTrangThaiHopDong') && typeof ApexCharts !== 'undefined') {
        new ApexCharts(document.querySelector('#chartTrangThaiHopDong'), {
            chart: { type: 'donut', height: 300, fontFamily: 'inherit' },
            labels: ttLabels,
            series: ttSeries,
            legend: { position: 'bottom' },
            plotOptions: {
                pie: {
                    donut: {
                        labels: {
                            show: true,
                            total: {
                                show: true,
                                label: 'Tổng HĐ',
                                formatter: function () {
                                    return new Intl.NumberFormat('vi-VN').format({{ (int) $tongHopDong }});
                                }
                            }
                        }
                    }
                }
            },
            colors: ['#28c76f', '#ff9f43'],
            dataLabels: { enabled: false },
            tooltip: { y: { formatter: function (v) { return v + ' hợp đồng'; } } }
        }).render();
    }
    @endif
})();
</script>
@endpush
