@extends('admin.layouts.app')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/vendor/libs/bootstrap-daterangepicker/bootstrap-daterangepicker.css') }}" />
@endpush

@section('content')
<div class="card">
    <h5 class="card-header">Danh sách công nợ</h5>
    <div class="card-body">
        @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <ul class="mb-0 ps-3">
                @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Đóng"></button>
        </div>
        @endif

        <form action="{{ route('admin.tai-chinh.cong-no') }}" method="GET" class="mb-4">
            <div class="row g-3 align-items-end">
                <div class="col-md-4 col-lg-4">
                    <label class="form-label" for="cong_no_date_range">Khoảng ngày</label>
                    <input type="text"
                           class="form-control"
                           id="cong_no_date_range"
                           placeholder="dd/mm/yyyy - dd/mm/yyyy"
                           autocomplete="off"
                           value="">
                    <input type="hidden" name="tu_ngay" id="cong_no_tu_ngay" value="{{ request('tu_ngay') }}">
                    <input type="hidden" name="den_ngay" id="cong_no_den_ngay" value="{{ request('den_ngay') }}">
                </div>
                <div class="col-md-4 col-lg-3">
                    <label class="form-label" for="trang_thai_tt">Trạng thái thanh toán</label>
                    <select class="form-select" id="trang_thai_tt" name="trang_thai_tt">
                        <option value="">— Tất cả —</option>
                        <option value="chua" {{ request('trang_thai_tt') === 'chua' ? 'selected' : '' }}>Chưa thanh toán</option>
                        <option value="da" {{ request('trang_thai_tt') === 'da' ? 'selected' : '' }}>Đã thanh toán</option>
                    </select>
                </div>
                <div class="col-auto">
                    <button type="submit" class="btn btn-primary">
                        <i class="fa-solid fa-filter me-1"></i> Lọc
                    </button>
                    @if(request('tu_ngay') || request('den_ngay') || request('trang_thai_tt'))
                    <a href="{{ route('admin.tai-chinh.cong-no') }}" class="btn btn-outline-secondary">Bỏ lọc</a>
                    @endif
                </div>
            </div>
            <p class="text-muted small mt-2 mb-0">
                Lọc theo ngày tạo hợp đồng. <strong>Chưa thanh toán / Đã thanh toán</strong> dựa trên số tiền còn phải thu
                (tổng tiền trừ giảm giá trừ các lần thanh toán 1–3).
            </p>
        </form>

        <div class="table-responsive text-nowrap table-wrapper-bordered">
            <table class="table table-hover table-bordered mb-0">
                <thead class="table-light">
                    <tr>
                        <th style="width: 50px;">STT</th>
                        <th class="text-center" style="width: 110px;">Mã hợp đồng</th>
                        <th>Khách hàng (Cô dâu — Chú rể)</th>
                        <th class="text-center" style="min-width: 118px;">Ngày bắt đầu HĐ</th>
                        <th class="text-center" style="min-width: 118px;">Ngày kết thúc HĐ</th>
                        <th class="text-end" style="min-width: 120px;">Tổng tiền HĐ</th>
                        <th class="text-end" style="min-width: 120px;">Đã cọc</th>
                        <th class="text-end" style="min-width: 140px;">Thanh toán bổ sung</th>
                        <th class="text-end" style="min-width: 120px;">Còn lại</th>
                        <th class="text-center" style="width: 150px;">Trạng thái</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($danhSach as $idx => $item)
                    @php
                        $kh = $item->khachHang;
                        $tenKh = '—';
                        if ($kh) {
                            $cd = $kh->ho_ten_co_dau ?? '';
                            $cr = $kh->ho_ten_chu_re ?? '';
                            $tenKh = trim($cd . ($cd !== '' && $cr !== '' ? ' — ' : '') . $cr);
                            $tenKh = $tenKh !== '' ? $tenKh : '—';
                        }
                        $tongTien = (float) ($item->tong_tien ?? 0);
                        $giamGia = (float) ($item->so_tien_giam_gia ?? 0);
                        $tt1 = (float) ($item->thanh_toan_lan_1 ?? 0);
                        $tt2 = (float) ($item->thanh_toan_lan_2 ?? 0);
                        $tt3 = (float) ($item->thanh_toan_lan_3 ?? 0);
                        $daCoc = $tt1;
                        $thanhToanBoSung = $tt2 + $tt3;
                        $conLaiThuc = $tongTien - $giamGia - $tt1 - $tt2 - $tt3;
                        $conLai = max(0, $conLaiThuc);
                        $daHet = $conLaiThuc <= 0;
                    @endphp
                    <tr>
                        <td>{{ $danhSach->firstItem() + $idx }}</td>
                        <td class="text-center fw-semibold">{{ $item->id }}</td>
                        <td>{{ $tenKh }}</td>
                        <td class="text-center">{{ $item->created_at?->format('d/m/Y') ?? '—' }}</td>
                        <td class="text-center">{{ $item->ngay_hen_tra_hang ? $item->ngay_hen_tra_hang->format('d/m/Y') : '—' }}</td>
                        <td class="text-end">{{ number_format($tongTien, 0, ',', '.') }} đ</td>
                        <td class="text-end">{{ number_format($daCoc, 0, ',', '.') }} đ</td>
                        <td class="text-end">{{ number_format($thanhToanBoSung, 0, ',', '.') }} đ</td>
                        <td class="text-end fw-medium {{ $daHet ? 'text-success' : 'text-danger' }}">
                            {{ number_format($conLai, 0, ',', '.') }} đ
                        </td>
                        <td class="text-center">
                            @if($daHet)
                            <span class="badge bg-label-success">Đã thanh toán</span>
                            @else
                            <span class="badge bg-label-warning">Chưa thanh toán</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="10" class="text-center text-muted py-4">Không có hợp đồng phù hợp bộ lọc.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($danhSach->hasPages())
        <div class="mt-3 d-flex justify-content-center">
            {{ $danhSach->links() }}
        </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('assets/vendor/libs/moment/moment.js') }}"></script>
<script src="{{ asset('assets/vendor/libs/bootstrap-daterangepicker/bootstrap-daterangepicker.js') }}"></script>
<script>
(function () {
    if (window.__congNoDateRangeInit) return;
    window.__congNoDateRangeInit = true;

    var $ = window.jQuery;
    if (!$ || !$.fn.daterangepicker || typeof moment === 'undefined') return;

    var $inp = $('#cong_no_date_range');
    var $tu = $('#cong_no_tu_ngay');
    var $den = $('#cong_no_den_ngay');
    if (!$inp.length) return;

    var tu = ($tu.val() || '').trim();
    var den = ($den.val() || '').trim();
    var mTu = tu ? moment(tu, 'YYYY-MM-DD', true) : null;
    var mDen = den ? moment(den, 'YYYY-MM-DD', true) : null;

    var opts = {
        opens: 'right',
        autoUpdateInput: false,
        locale: {
            format: 'DD/MM/YYYY',
            separator: ' - ',
            applyLabel: 'Áp dụng',
            cancelLabel: 'Hủy',
            fromLabel: 'Từ',
            toLabel: 'Đến',
            customRangeLabel: 'Tùy chọn',
            firstDay: 1
        }
    };

    if (mTu && mTu.isValid() && mDen && mDen.isValid()) {
        opts.startDate = mTu;
        opts.endDate = mDen;
    } else if (mTu && mTu.isValid()) {
        opts.startDate = mTu;
        opts.endDate = mTu.clone();
    } else if (mDen && mDen.isValid()) {
        opts.startDate = mDen.clone();
        opts.endDate = mDen.clone();
    }

    $inp.daterangepicker(opts);

    function syncLabel() {
        var t = ($tu.val() || '').trim();
        var d = ($den.val() || '').trim();
        var a = t ? moment(t, 'YYYY-MM-DD', true) : null;
        var b = d ? moment(d, 'YYYY-MM-DD', true) : null;
        if (a && a.isValid() && b && b.isValid()) {
            $inp.val(a.format('DD/MM/YYYY') + ' - ' + b.format('DD/MM/YYYY'));
        } else if (a && a.isValid()) {
            $inp.val(a.format('DD/MM/YYYY') + ' - ' + a.format('DD/MM/YYYY'));
        } else if (b && b.isValid()) {
            $inp.val(b.format('DD/MM/YYYY') + ' - ' + b.format('DD/MM/YYYY'));
        } else {
            $inp.val('');
        }
    }

    syncLabel();

    $inp.on('apply.daterangepicker', function (ev, picker) {
        $tu.val(picker.startDate.format('YYYY-MM-DD'));
        $den.val(picker.endDate.format('YYYY-MM-DD'));
        syncLabel();
    });

    $inp.on('cancel.daterangepicker', function () {
        $tu.val('');
        $den.val('');
        $inp.val('');
    });
})();
</script>
@endpush
