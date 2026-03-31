@extends('admin.layouts.app')

@section('content')
<div class="card">
    <h5 class="card-header">Công việc của tôi</h5>
    <div class="card-body">
        @php
            $trangPhucMap = ($danhSachTrangPhuc ?? collect())->keyBy('id');
            $badgeClassByRole = [
                'Chụp' => 'bg-primary',
                'Make' => 'bg-warning',
                'Edit' => 'bg-success',
            ];
        @endphp

        <form action="{{ route('admin.nhan-su.cong-viec-cua-toi') }}" method="GET" class="mb-4">
            <div class="row g-3 align-items-end">
                <div class="col-md-6 col-lg-5">
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
                        <a href="{{ route('admin.nhan-su.cong-viec-cua-toi') }}" class="btn btn-outline-secondary">Bỏ lọc</a>
                    @endif
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
                        <th style="width: 130px;">Trạng thái chụp</th>
                        <th>Trang phục</th>
                        <th>Ghi chú</th>
                        <th style="width: 150px;">Ngày hẹn trả demo</th>
                        <th>Trạng thái edit</th>
                        <th>Trạng thái HĐ</th>
                        <th>Vai trò của tôi</th>
                        <th style="width: 110px;">File chụp</th>
                        <th style="width: 140px;">File hoàn thành</th>
                        <th style="width: 160px;">Hành động</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @forelse($danhSach ?? [] as $index => $item)
                        @php
                            $tenKhachHang = '—';
                            if ($item->khachHang) {
                                $parts = array_filter([
                                    $item->khachHang->ho_ten_chu_re ?? '',
                                    $item->khachHang->ho_ten_co_dau ?? '',
                                ]);
                                $tenKhachHang = implode(' / ', $parts) ?: '—';
                            }

                            // Trang phục: lưu id dạng chuỗi phân tách dấu phẩy.
                            $rawTrangPhuc = (string) ($item->trang_phuc ?? '');
                            $trangPhucIds = array_filter(array_map('trim', explode(',', $rawTrangPhuc)));
                            $trangPhucNames = collect($trangPhucIds)
                                ->map(fn($id) => $trangPhucMap[(int) $id]->ten_san_pham ?? null)
                                ->filter()
                                ->values();

                            if ($isAdmin) {
                                $roles = [];
                                if ($item->tho_chup_id) $roles[] = 'Chụp';
                                if ($item->tho_make_id) $roles[] = 'Make';
                                if ($item->tho_edit_id) $roles[] = 'Edit';
                            } else {
                                $roles = [];
                                if ((int) ($item->tho_chup_id ?? 0) === (int) $nhanVienId) $roles[] = 'Chụp';
                                if ((int) ($item->tho_make_id ?? 0) === (int) $nhanVienId) $roles[] = 'Make';
                                if ((int) ($item->tho_edit_id ?? 0) === (int) $nhanVienId) $roles[] = 'Edit';
                            }

                            $homNay = now()->startOfDay();
                            $ngayChup = $item->ngay_chup ? $item->ngay_chup->copy()->startOfDay() : null;
                            $coFileDemo = !empty($item->link_file_demo);

                            if ($coFileDemo) {
                                $tinhTrangChup = 'Đã chụp';
                                $tinhTrangChupClass = 'bg-success';
                            } elseif ($ngayChup && $ngayChup->greaterThan($homNay)) {
                                $tinhTrangChup = 'Đợi chụp';
                                $tinhTrangChupClass = 'bg-secondary';
                            } elseif ($ngayChup && $ngayChup->equalTo($homNay)) {
                                $tinhTrangChup = 'Cần chụp';
                                $tinhTrangChupClass = 'bg-warning';
                            } elseif ($ngayChup && $ngayChup->lessThan($homNay)) {
                                $tinhTrangChup = 'Trễ chụp';
                                $tinhTrangChupClass = 'bg-danger';
                            } else {
                                $tinhTrangChup = 'Cần chụp';
                                $tinhTrangChupClass = 'bg-warning';
                            }

                            $rawNgayTraLinkIn = $item->ngay_tra_link_in ?? null;
                            if ($rawNgayTraLinkIn instanceof \Carbon\CarbonInterface) {
                                $ngayTraLinkIn = $rawNgayTraLinkIn->copy()->startOfDay();
                            } elseif (!empty($rawNgayTraLinkIn)) {
                                $ngayTraLinkIn = \Carbon\Carbon::parse($rawNgayTraLinkIn)->startOfDay();
                            } else {
                                $ngayTraLinkIn = null;
                            }

                            $coFileIn = !empty($item->link_file_in);
                            if ($coFileIn) {
                                $tinhTrangEdit = 'Đã edit';
                                $tinhTrangEditClass = 'bg-success';
                            } elseif ($ngayTraLinkIn && $ngayTraLinkIn->greaterThan($homNay)) {
                                $tinhTrangEdit = 'Đợi edit';
                                $tinhTrangEditClass = 'bg-secondary';
                            } elseif ($ngayTraLinkIn && $ngayTraLinkIn->equalTo($homNay)) {
                                $tinhTrangEdit = 'Cần edit';
                                $tinhTrangEditClass = 'bg-warning';
                            } elseif ($ngayTraLinkIn && $ngayTraLinkIn->lessThan($homNay)) {
                                $tinhTrangEdit = 'Trễ edit';
                                $tinhTrangEditClass = 'bg-danger';
                            } else {
                                $tinhTrangEdit = 'Cần edit';
                                $tinhTrangEditClass = 'bg-warning';
                            }
                        @endphp
                        <tr>
                            <td>{{ ($danhSach->currentPage() - 1) * $danhSach->perPage() + $index + 1 }}</td>
                            <td><span class="fw-medium">{{ $tenKhachHang }}</span></td>
                            <td>{{ $item->dia_diem ? str($item->dia_diem)->limit(25) : '—' }}</td>
                            <td>{{ $item->ngay_chup ? $item->ngay_chup->format('d/m/Y H:i') : '—' }}</td>
                            <td>
                                <span class="badge {{ $tinhTrangChupClass ?? 'bg-secondary' }}">{{ $tinhTrangChup ?? '—' }}</span>
                            </td>
                            <td>
                                {{ $trangPhucNames->isNotEmpty()
                                    ? str($trangPhucNames->implode(', '))->limit(30)
                                    : ($item->trang_phuc ? str($item->trang_phuc)->limit(30) : '—') }}
                            </td>
                            <td>{{ $item->ghi_chu_chup ? str($item->ghi_chu_chup)->limit(40) : '—' }}</td>
                            <td>
                                @if(!empty($item->ngay_tra_link_in))
                                    {{ $item->ngay_tra_link_in instanceof \Carbon\CarbonInterface
                                        ? $item->ngay_tra_link_in->format('d/m/Y')
                                        : \Carbon\Carbon::parse($item->ngay_tra_link_in)->format('d/m/Y') }}
                                @else
                                    —
                                @endif
                            </td>
                            <td>
                                <span class="badge {{ $tinhTrangEditClass ?? 'bg-secondary' }}">{{ $tinhTrangEdit ?? '—' }}</span>
                            </td>
                            <td>{{ $item->trang_thai_hop_dong ?? '—' }}</td>
                            <td>
                                <div class="d-flex gap-1 flex-wrap">
                                    @if(!empty($roles))
                                        @foreach($roles as $role)
                                            <span class="badge {{ $badgeClassByRole[$role] ?? 'bg-secondary' }}">{{ $role }}</span>
                                        @endforeach
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </div>
                            </td>
                            <td>
                                @if(!empty($item->link_file_demo))
                                    <a class="btn btn-sm btn-outline-info"
                                       href="{{ $item->link_file_demo }}"
                                       target="_blank"
                                       rel="noopener noreferrer">
                                        Xem
                                    </a>
                                @endif
                            </td>
                            <td>
                                @if(!empty($item->link_file_in))
                                    <a class="btn btn-sm btn-outline-info"
                                       href="{{ $item->link_file_in }}"
                                       target="_blank"
                                       rel="noopener noreferrer">
                                        In
                                    </a>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex gap-2 flex-wrap">
                                    <button type="button"
                                            class="btn btn-sm btn-outline-dark btn-in-brief"
                                            data-bs-toggle="modal"
                                            data-bs-target="#modalInBrief"
                                            data-khach-hang="{{ e($tenKhachHang) }}"
                                            data-sdt="{{ e(($item->khachHang->email_hoac_sdt_chu_re ?? '') ?: ($item->khachHang->email_hoac_sdt_co_dau ?? '')) }}"
                                            data-ngay-chup="{{ $item->ngay_chup ? $item->ngay_chup->format('d/m/Y H:i') : '—' }}"
                                            data-nguoi-chup="{{ $item->nguoiChupUser?->name ?? ($item->thoChup?->user?->name ?? '—') }}"
                                            data-dia-diem="{{ e($item->dia_diem ?? '—') }}"
                                            data-concept="{{ e($item->concept ?? '—') }}"
                                            data-trang-phuc="{{ e($trangPhucNames->isNotEmpty() ? $trangPhucNames->implode(', ') : ($item->trang_phuc ?? '—')) }}"
                                            data-ghi-chu="{{ e($item->ghi_chu_chup ?? '—') }}">
                                        In brief
                                    </button>

                                    @if(in_array('Chụp', $roles ?? [], true))
                                        <button type="button"
                                                class="btn btn-sm btn-outline-primary btn-cap-nhat-link"
                                                data-bs-toggle="modal"
                                                data-bs-target="#modalCapNhatLink"
                                                data-url="{{ route('admin.nhan-su.cong-viec-cua-toi.cap-nhat-link', $item) }}"
                                                data-type="demo"
                                                data-title="Cập nhật link file chụp"
                                                data-label="Link file chụp"
                                                data-current="{{ e($item->link_file_demo ?? '') }}">
                                            Up file chụp
                                        </button>
                                    @endif

                                    @if(in_array('Edit', $roles ?? [], true))
                                        <button type="button"
                                                class="btn btn-sm btn-outline-success btn-cap-nhat-link"
                                                data-bs-toggle="modal"
                                                data-bs-target="#modalCapNhatLink"
                                                data-url="{{ route('admin.nhan-su.cong-viec-cua-toi.cap-nhat-link', $item) }}"
                                                data-type="in"
                                                data-title="Cập nhật link file edit"
                                                data-label="Link file edit"
                                                data-current="{{ e($item->link_file_in ?? '') }}">
                                            Up file edit
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="15" class="text-center py-4 text-muted">Chưa có hợp đồng nào.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <x-pagination-info :paginator="$danhSach ?? null" label="hợp đồng" />
    </div>
</div>

{{-- Modal cập nhật link file (nhập link, không upload từ thiết bị) --}}
<div class="modal fade" id="modalCapNhatLink" tabindex="-1" aria-labelledby="modalCapNhatLinkLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalCapNhatLinkLabel">Cập nhật link</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
            </div>
            <form method="POST" id="formCapNhatLink">
                @csrf
                @method('PUT')
                <input type="hidden" name="type" id="capNhatLinkType" value="">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label" for="capNhatLinkInput" id="capNhatLinkLabel">Link</label>
                        <input type="text"
                               class="form-control"
                               id="capNhatLinkInput"
                               name="link"
                               placeholder="Nhập link file..."
                               autocomplete="off">
                        <div class="form-text">Nhập link file (Google Drive, Dropbox, ...). Không chọn file từ thiết bị.</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-primary">Cập nhật</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Modal in brief --}}
<div class="modal fade" id="modalInBrief" tabindex="-1" aria-labelledby="modalInBriefLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalInBriefLabel">Brief lịch chụp</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
            </div>
            <div class="modal-body">
                <div id="briefCaptureArea" class="p-3" style="background: #fff;">
                    <div class="table-responsive">
                        <table class="table table-bordered mb-0">
                            <tbody>
                                <tr>
                                    <th style="width: 170px;">Khách hàng</th>
                                    <td id="briefKhachHang">—</td>
                                </tr>
                                <tr>
                                    <th>Email/SĐT</th>
                                    <td id="briefSdt">—</td>
                                </tr>
                                <tr>
                                    <th style="width: 170px;">Ngày chụp</th>
                                    <td id="briefNgayChup">—</td>
                                </tr>
                                <tr>
                                    <th>Người chụp</th>
                                    <td id="briefNguoiChup">—</td>
                                </tr>
                                <tr>
                                    <th>Địa điểm</th>
                                    <td id="briefDiaDiem">—</td>
                                </tr>
                                <tr>
                                    <th>Concept</th>
                                    <td id="briefConcept">—</td>
                                </tr>
                                <tr>
                                    <th>Trang phục</th>
                                    <td id="briefTrangPhuc">—</td>
                                </tr>
                                <tr>
                                    <th>Ghi chú</th>
                                    <td id="briefGhiChu" style="white-space: pre-wrap;">—</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="btnTaiBriefPng">Tải xuống</button>
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Đóng</button>
            </div>
        </div>
    </div>
</div>

<script>
    (function () {
        var modal = document.getElementById('modalCapNhatLink');
        if (!modal) return;

        modal.addEventListener('show.bs.modal', function (event) {
            var button = event.relatedTarget;
            if (!button) return;

            var url = button.getAttribute('data-url') || '';
            var type = button.getAttribute('data-type') || '';
            var title = button.getAttribute('data-title') || 'Cập nhật link';
            var label = button.getAttribute('data-label') || 'Link';
            var current = button.getAttribute('data-current') || '';

            var form = document.getElementById('formCapNhatLink');
            var titleEl = document.getElementById('modalCapNhatLinkLabel');
            var labelEl = document.getElementById('capNhatLinkLabel');
            var typeEl = document.getElementById('capNhatLinkType');
            var input = document.getElementById('capNhatLinkInput');

            if (form) form.action = url;
            if (titleEl) titleEl.textContent = title;
            if (labelEl) labelEl.textContent = label;
            if (typeEl) typeEl.value = type;
            if (input) input.value = current;
        });

        modal.addEventListener('shown.bs.modal', function () {
            var input = document.getElementById('capNhatLinkInput');
            if (input) input.focus();
        });
    })();
</script>

<script src="https://cdn.jsdelivr.net/npm/html2canvas@1.4.1/dist/html2canvas.min.js"></script>
<script>
    (function () {
        var btn = document.getElementById('btnTaiBriefPng');
        if (!btn) return;

        btn.addEventListener('click', async function () {
            var captureEl = document.getElementById('briefCaptureArea');
            if (!captureEl) return;

            if (typeof window.html2canvas !== 'function') {
                alert('Thiếu thư viện tạo ảnh (html2canvas). Vui lòng tải lại trang.');
                return;
            }

            var oldHtml = btn.innerHTML;
            btn.disabled = true;
            btn.textContent = 'Đang tạo ảnh...';

            try {
                var canvas = await window.html2canvas(captureEl, {
                    backgroundColor: '#ffffff',
                    scale: 2,
                    useCORS: true,
                });

                var dataUrl = canvas.toDataURL('image/png');
                var link = document.createElement('a');
                link.href = dataUrl;

                var ngay = (document.getElementById('briefNgayChup')?.textContent || '')
                    .trim()
                    .replace(/[^\d]/g, '');
                link.download = 'brief-lich-chup' + (ngay ? ('-' + ngay) : '') + '.png';

                document.body.appendChild(link);
                link.click();
                link.remove();
            } finally {
                btn.disabled = false;
                btn.innerHTML = oldHtml;
            }
        });
    })();
</script>

<script>
    (function () {
        var modal = document.getElementById('modalInBrief');
        if (!modal) return;

        function setText(id, value) {
            var el = document.getElementById(id);
            if (el) el.textContent = (value && String(value).trim()) ? value : '—';
        }

        modal.addEventListener('show.bs.modal', function (event) {
            var button = event.relatedTarget;
            if (!button) return;

            setText('briefKhachHang', button.getAttribute('data-khach-hang'));
            setText('briefSdt', button.getAttribute('data-sdt'));
            setText('briefNgayChup', button.getAttribute('data-ngay-chup'));
            setText('briefNguoiChup', button.getAttribute('data-nguoi-chup'));
            setText('briefDiaDiem', button.getAttribute('data-dia-diem'));
            setText('briefConcept', button.getAttribute('data-concept'));
            setText('briefTrangPhuc', button.getAttribute('data-trang-phuc'));
            setText('briefGhiChu', button.getAttribute('data-ghi-chu'));
        });
    })();
</script>
@endsection
