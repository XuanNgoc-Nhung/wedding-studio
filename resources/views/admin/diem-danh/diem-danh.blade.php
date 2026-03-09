@extends('admin.layouts.app')

@section('content')
<div class="card">
    <h5 class="card-header">Danh sách điểm danh</h5>
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
        {{-- Bộ lọc + Chấm công --}}
        <form action="{{ route('admin.diem-danh.diem-danh') }}" method="GET" class="mb-4">
            <div class="row g-3 align-items-end">
                <div class="col-md-4 col-lg-3">
                    <label class="form-label" for="search">Họ tên</label>
                    <input type="text"
                           class="form-control"
                           id="search"
                           name="search"
                           value="{{ request('search') }}"
                           placeholder="Nhập họ tên...">
                </div>
                <div class="col-md-3 col-lg-2">
                    <label class="form-label" for="tu_ngay">Từ ngày</label>
                    <input type="date" class="form-control" id="tu_ngay" name="tu_ngay" value="{{ request('tu_ngay') }}">
                </div>
                <div class="col-md-3 col-lg-2">
                    <label class="form-label" for="den_ngay">Đến ngày</label>
                    <input type="date" class="form-control" id="den_ngay" name="den_ngay" value="{{ request('den_ngay') }}">
                </div>
                <div class="col-auto">
                    <button type="submit" class="btn btn-primary">
                        <i class="fa-solid fa-magnifying-glass me-1"></i> Lọc
                    </button>
                    @if(request('search') || request('tu_ngay') || request('den_ngay'))
                    <a href="{{ route('admin.diem-danh.diem-danh') }}" class="btn btn-outline-secondary">Bỏ lọc</a>
                    @endif
                </div>
                <div class="col-auto ms-md-auto d-flex flex-wrap gap-2 align-items-center">
                    @if($canCheckIn ?? false)
                        <a href="{{ route('admin.diem-danh.check-in') }}" class="btn btn-success">
                            <i class="fa-solid fa-sign-in-alt me-1"></i> Check in
                        </a>
                    @elseif($canCheckOut ?? false)
                        <a href="{{ route('admin.diem-danh.check-out') }}" class="btn btn-warning text-dark">
                            <i class="fa-solid fa-sign-out-alt me-1"></i> Check out
                        </a>
                    @endif
                    <a href="{{ route('admin.diem-danh.cham-cong') }}" class="btn btn-primary">
                        <i class="fa-solid fa-clock me-1"></i> Chấm công
                    </a>
                </div>
            </div>
        </form>

        <div class="table-responsive text-nowrap table-wrapper-bordered">
            <table class="table table-hover table-bordered mb-0">
                <thead class="table-light">
                    <tr>
                        <th style="width: 50px;">STT</th>
                        <th>Họ tên</th>
                        <th>Giờ vào</th>
                        <th>Giờ ra</th>
                        <th class="text-center">Đi muộn</th>
                        <th class="text-center">Hợp lệ</th>
                        <th>Lý do</th>
                        <th class="text-center">Nghỉ phép</th>
                        <th>Loại phép</th>
                        <th>Ghi chú</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @forelse($danhSach ?? [] as $index => $item)
                    <tr>
                        <td>{{ ($danhSach->currentPage() - 1) * $danhSach->perPage() + $index + 1 }}</td>
                        <td><span class="fw-medium">{{ $item->user?->name ?? '—' }}</span></td>
                        <td>{{ $item->gio_vao ? $item->gio_vao->format('d/m/Y H:i') : '—' }}</td>
                        <td>{{ $item->gio_ra ? $item->gio_ra->format('d/m/Y H:i') : '—' }}</td>
                        <td class="text-center">
                            @if($item->di_muon)
                                <span class="badge bg-warning">Có</span>
                            @else
                                <span class="text-muted">Không</span>
                            @endif
                        </td>
                        <td class="text-center">
                            @if($item->hop_le)
                                <span class="badge bg-success">Có</span>
                            @else
                                <span class="text-muted">Không</span>
                            @endif
                        </td>
                        <td>{{ $item->ly_do ?? '—' }}</td>
                        <td class="text-center">
                            @if($item->nghi_phep)
                                <span class="badge bg-info">Có</span>
                            @else
                                <span class="text-muted">Không</span>
                            @endif
                        </td>
                        <td>{{ $item->loai_phep ?? '—' }}</td>
                        <td>{{ Str::limit($item->ghi_chu, 50) ?: '—' }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="10" class="text-center py-4 text-muted">Chưa có dữ liệu điểm danh.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <x-pagination-info :paginator="$danhSach ?? null" label="bản ghi điểm danh" />
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
</style>
@endpush
@endsection
