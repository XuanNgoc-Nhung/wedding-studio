@extends('admin.layouts.app')

@section('content')
<div class="card">
    <h5 class="card-header">Lịch làm việc</h5>
    <div class="card-body">
        @php
            $hopDongTheoNgay = ($hopDongTrongTuan ?? collect())
                ->groupBy(fn ($hd) => optional($hd->ngay_chup)->format('Y-m-d'));
        @endphp

        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
            <div class="text-muted">
                Tuần: <strong>{{ $batDauTuan->format('d/m/Y') }}</strong> → <strong>{{ $ketThucTuan->format('d/m/Y') }}</strong>
            </div>
            <div class="small text-muted">
                Lọc: hợp đồng có ngày chụp trong tuần và bạn là thợ chụp/make/edit
            </div>
        </div>

        @if(empty($nhanVienId))
            <div class="alert alert-warning mb-0">
                Tài khoản của bạn chưa có hồ sơ nhân viên nên chưa thể lọc theo thợ chụp/make/edit.
            </div>
        @else
            <div class="row g-3">
                @foreach(($dsNgayTrongTuan ?? collect()) as $ngay)
                    @php
                        $key = $ngay->format('Y-m-d');
                        $ds = $hopDongTheoNgay->get($key, collect());
                    @endphp
                    <div class="col-12 col-md-6 col-xl-4">
                        <div class="border rounded p-3 h-100">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <div>
                                    <div class="fw-semibold">{{ $ngay->translatedFormat('l') }}</div>
                                    <div class="text-muted small">{{ $ngay->format('d/m/Y') }}</div>
                                </div>
                                <span class="badge bg-secondary">{{ $ds->count() }}</span>
                            </div>

                            @if($ds->isEmpty())
                                <div class="text-muted small">Không có hợp đồng.</div>
                            @else
                                <div class="list-group list-group-flush">
                                    @foreach($ds as $hd)
                                        @php
                                            $vaiTro = [];
                                            if ((int) $hd->tho_chup_id === (int) $nhanVienId) $vaiTro[] = 'Chụp';
                                            if ((int) $hd->tho_make_id === (int) $nhanVienId) $vaiTro[] = 'Make';
                                            if ((int) $hd->tho_edit_id === (int) $nhanVienId) $vaiTro[] = 'Edit';
                                        @endphp
                                        <div class="list-group-item px-0">
                                            <div class="d-flex justify-content-between align-items-start gap-2">
                                                <div class="flex-grow-1">
                                                    <div class="fw-semibold">
                                                        {{ $hd->khachHang?->ten_khach_hang ?? ('HĐ #' . $hd->id) }}
                                                    </div>
                                                    <div class="small text-muted">
                                                        {{ $hd->dia_diem ?: '—' }}
                                                    </div>
                                                </div>
                                                <div class="text-end">
                                                    @foreach($vaiTro as $t)
                                                        <span class="badge bg-primary">{{ $t }}</span>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
@endsection