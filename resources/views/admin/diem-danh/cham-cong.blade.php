@extends('admin.layouts.app')

@section('content')
<div class="card">
    <h5 class="card-header">Chấm công theo tháng</h5>
    <div class="card-body">
        <form method="GET" action="{{ route('admin.diem-danh.cham-cong') }}" class="mb-4">
            <div class="row g-3 align-items-end">
                <div class="col-md-3 col-lg-2">
                    <label class="form-label" for="month">Tháng</label>
                    <select class="form-select" id="month" name="month">
                        @for($m = 1; $m <= 12; $m++)
                            <option value="{{ $m }}" @selected((int)($month ?? now()->month) === $m)>
                                Tháng {{ $m }}
                            </option>
                        @endfor
                    </select>
                </div>
                <div class="col-md-3 col-lg-2">
                    <label class="form-label" for="year">Năm</label>
                    <input type="number"
                           class="form-control"
                           id="year"
                           name="year"
                           min="2000"
                           max="2100"
                           value="{{ (int)($year ?? now()->year) }}">
                </div>
                <div class="col-auto">
                    <button type="submit" class="btn btn-primary">
                        <i class="fa-solid fa-filter me-1"></i> Lọc
                    </button>
                    <a href="{{ route('admin.diem-danh.cham-cong') }}" class="btn btn-outline-secondary">Tháng hiện tại</a>
                </div>
                <div class="col-12 col-lg-auto ms-lg-auto">
                    <div class="text-muted">
                        Khoảng: <strong>{{ ($start ?? now()->startOfMonth())->format('d/m/Y') }}</strong>
                        → <strong>{{ ($end ?? now()->endOfMonth())->format('d/m/Y') }}</strong>
                    </div>
                </div>
            </div>
        </form>

        <div class="table-responsive text-nowrap table-wrapper-bordered">
            <table class="table table-bordered table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th style="min-width: 220px;">Nhân viên</th>
                        @foreach(($ngayTrongThang ?? []) as $day)
                            <th class="text-center" style="min-width: 72px;">
                                <div class="fw-semibold">{{ $day->format('d') }}</div>
                                <div class="small text-muted">{{ $day->isoFormat('dd') }}</div>
                            </th>
                        @endforeach
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @forelse(($nhanVien ?? []) as $u)
                        <tr>
                            <td>
                                <div class="fw-medium">{{ $u->name }}</div>
                                <div class="small text-muted">{{ $u->email }}</div>
                            </td>
                            @foreach(($ngayTrongThang ?? []) as $day)
                                @php
                                    $dateKey = $day->toDateString();
                                    $record = $bangChamCong[$dateKey][$u->id] ?? null;
                                    $diemDanh = $record?->diemDanh;
                                @endphp
                                <td class="text-center align-middle">
                                    @if($record && $diemDanh)
                                        <div class="small">
                                            <span class="badge bg-success">Có</span>
                                        </div>
                                        <div class="small text-muted">
                                            {{ $diemDanh->gio_vao ? $diemDanh->gio_vao->format('H:i') : '—' }}
                                            -
                                            {{ $diemDanh->gio_ra ? $diemDanh->gio_ra->format('H:i') : '—' }}
                                        </div>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                            @endforeach
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ 1 + count($ngayTrongThang ?? []) }}" class="text-center py-4 text-muted">
                                Chưa có nhân viên để hiển thị.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
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
.table-wrapper-bordered .table th,
.table-wrapper-bordered .table td {
    border: 1px solid var(--bs-border-color, #dee2e6);
}
</style>
@endpush
@endsection