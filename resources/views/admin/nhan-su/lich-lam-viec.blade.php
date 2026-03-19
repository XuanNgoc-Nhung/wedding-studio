@extends('admin.layouts.app')

@section('content')
<div class="card">
    {{-- <h5 class="card-header">Lịch làm việc</h5> --}}
    <div class="card-body">
        @if(empty($nhanVienId) && empty($isAdmin))
            <div class="alert alert-warning mb-0">
                Tài khoản của bạn chưa có hồ sơ nhân viên nên chưa thể lọc theo thợ chụp/make/edit.
            </div>
        @else
            {{-- <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
                <div class="text-muted small">
                    Hiển thị lịch tháng. Mỗi ngày sẽ tổng hợp số lượng theo vai trò của bạn.
                </div>
                <div class="small text-muted">
                    Chỉ tính hợp đồng có <strong>ngày chụp</strong> và bạn là thợ <strong>chụp/make/edit</strong>.
                </div>
            </div> --}}

            <div id="calendar" class="admin-work-calendar"></div>
        @endif
    </div>
</div>
@endsection

@push('modals')
<div class="modal fade" id="wsWorkDayModal" tabindex="-1" aria-labelledby="wsWorkDayModalTitle" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="wsWorkDayModalTitle">Chi tiết công việc</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="wsWorkDayModalBody">
                <div class="text-muted small">Đang tải...</div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Đóng</button>
            </div>
        </div>
    </div>
</div>
@endpush

@push('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.css" />
    <style>
        .admin-work-calendar .fc .fc-daygrid-day-number{
            padding: 6px 8px;
        }
        .admin-work-calendar .fc .fc-daygrid-event{
            border: 0;
            background: transparent;
            padding: 0;
            margin: 2px 6px 0 6px;
        }
        /* Bôi xám các ngày cuối tuần (T7/CN) */
        .admin-work-calendar .fc .fc-daygrid-day.fc-day-sat,
        .admin-work-calendar .fc .fc-daygrid-day.fc-day-sun{
            /* background: rgba(67, 89, 113, .06); */
        }
        .admin-work-calendar .ws-day-summary{
            padding: 6px 8px;
            background: rgb(172, 255, 213);
            color: #28103b;
            line-height: 1.25;
            border: 1px solid rgb(172, 255, 213);
            border-radius: 8px;
            font-size: .8125rem;
        }
        .admin-work-calendar .ws-day-summary .ws-total{
            display: inline-block;
            font-weight: 600;
            margin-bottom: 4px;
        }
        .admin-work-calendar .ws-day-summary .ws-row{
            display: flex;
            justify-content: space-between;
            gap: 10px;
            white-space: nowrap;
        }
        .admin-work-calendar .ws-day-summary .ws-row b{
            font-weight: 600;
        }

        .admin-work-calendar .ws-event-item{
            border-radius: 8px;
            padding: 6px 8px;
            background: rgba(105,108,255,.10);
            color: #384551;
            line-height: 1.25;
            font-size: .8125rem;
        }
        .admin-work-calendar .ws-event-item .ws-time{
            font-weight: 700;
            margin-right: 6px;
        }
        .admin-work-calendar .ws-event-item .ws-role{
            opacity: .9;
            font-weight: 600;
        }

        /* Center content in modal body (loading/empty/error states) */
        #wsWorkDayModalBody.is-centered{
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            min-height: 220px;
        }
    </style>
@endpush

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var el = document.getElementById('calendar');
            if (!el || typeof FullCalendar === 'undefined') return;

            var calendar = new FullCalendar.Calendar(el, {
                initialView: 'dayGridMonth',
                height: 'auto',
                firstDay: 1, // Monday
                locale: 'vi',
                buttonText: {
                    today: 'Hôm nay',
                    month: 'Tháng',
                    week: 'Tuần',
                    day: 'Ngày',
                    list: 'Danh sách'
                },
                views: {
                    dayGridMonth: { buttonText: 'Tháng' },
                    listMonth: { buttonText: 'Danh sách' }
                },
                allDayText: 'Cả ngày',
                moreLinkText: function (n) { return '+ ' + n + ' mục'; },
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,listMonth'
                },
                dayMaxEvents: true,
                slotMinTime: '06:00:00',
                slotMaxTime: '22:00:00',
                nowIndicator: true,
                dayCellDidMount: function (arg) {
                    // Bắt click thủ công để luôn hoạt động (không phụ thuộc interaction plugin)
                    if (!arg || !arg.el || !arg.date) return;
                    // chỉ áp dụng ở lịch tháng
                    if (!calendar.view || calendar.view.type !== 'dayGridMonth') return;

                    arg.el.style.cursor = 'pointer';
                    arg.el.addEventListener('click', function () {
                        // Lấy ngày theo local timezone (tránh lệch -1 ngày do UTC)
                        var y = arg.date.getFullYear();
                        var m = String(arg.date.getMonth() + 1).padStart(2, '0');
                        var d = String(arg.date.getDate()).padStart(2, '0');
                        openWorkDayModal(y + '-' + m + '-' + d);
                    });
                },
                events: function (info, successCallback, failureCallback) {
                    var baseUrl = @json(route('admin.nhan-su.lich-lam-viec.data'));
                    var viewType = (calendar && calendar.view && calendar.view.type) ? calendar.view.type : '';
                    var params = new URLSearchParams({
                        start: info.startStr,
                        end: info.endStr,
                        view: viewType
                    });

                    fetch(baseUrl + '?' + params.toString(), { method: 'GET' })
                        .then(function (r) { return r.json(); })
                        .then(function (data) { successCallback(data); })
                        .catch(function (e) { failureCallback(e); });
                },
                eventContent: function (arg) {
                    // View tháng/danh sách: hiển thị tổng hợp theo ngày
                    if (arg.view && (arg.view.type === 'dayGridMonth' || arg.view.type === 'listMonth')) {
                    var p = (arg.event.extendedProps || {});
                    var chup = Number(p.chup || 0);
                    var make = Number(p.make || 0);
                    var edit = Number(p.edit || 0);
                    var tong = Number(p.tong || (chup + make + edit));

                    // Nếu 0 hết thì khỏi render
                    if (!tong) return { domNodes: [] };

                    var wrap = document.createElement('div');
                    wrap.className = 'ws-day-summary';
                    wrap.innerHTML =
                        '<div class="ws-total">Tổng: ' + tong + '</div>' +
                        '<div class="ws-row"><span><b>Chụp</b></span><span>' + chup + '</span></div>' +
                        '<div class="ws-row"><span><b>Make</b></span><span>' + make + '</span></div>' +
                        '<div class="ws-row"><span><b>Edit</b></span><span>' + edit + '</span></div>';

                    return { domNodes: [wrap] };
                    }

                    // View tuần/ngày: hiển thị theo giờ
                    var p2 = (arg.event.extendedProps || {});
                    var item = document.createElement('div');
                    item.className = 'ws-event-item';

                    var timeText = arg.timeText ? arg.timeText : '';
                    var roleText = p2.role ? (' • ' + p2.role) : '';
                    var titleText = arg.event.title || '';

                    item.innerHTML =
                        (timeText ? ('<span class="ws-time">' + timeText + '</span>') : '') +
                        '<span>' + titleText + '</span>' +
                        (roleText ? ('<span class="ws-role">' + roleText + '</span>') : '');

                    return { domNodes: [item] };
                }
            });

            calendar.render();

            function openWorkDayModal(dateStr) {
                var modalEl = document.getElementById('wsWorkDayModal');
                var titleEl = document.getElementById('wsWorkDayModalTitle');
                var bodyEl = document.getElementById('wsWorkDayModalBody');
                if (!modalEl || !titleEl || !bodyEl) return;

                titleEl.textContent = 'Chi tiết công việc ngày ' + dateStr;
                bodyEl.classList.add('is-centered');
                bodyEl.innerHTML = '<div class="text-muted small">Đang tải...</div>';

                if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
                    bootstrap.Modal.getOrCreateInstance(modalEl).show();
                } else if (window.jQuery && window.jQuery.fn && window.jQuery.fn.modal) {
                    window.jQuery(modalEl).modal('show');
                } else {
                    modalEl.classList.add('show');
                    modalEl.style.display = 'block';
                }

                var detailUrl = @json(route('admin.nhan-su.lich-lam-viec.chi-tiet-ngay'));
                var params = new URLSearchParams({ date: dateStr });
                fetch(detailUrl + '?' + params.toString(), { method: 'GET' })
                    .then(function (r) { return r.json(); })
                    .then(function (payload) {
                        var items = (payload && payload.items) ? payload.items : [];
                        if (!items.length) {
                            bodyEl.classList.add('is-centered');
                            bodyEl.innerHTML = '<div class="alert alert-info mb-0">Không có công việc trong ngày này.</div>';
                            return;
                        }

                        bodyEl.classList.remove('is-centered');
                        var html = '<div class="table-responsive"><table class="table table-sm align-middle">';
                        html += '<thead><tr>' +
                            '<th style="width:80px">Giờ</th>' +
                            '<th style="min-width:180px">Khách hàng</th>' +
                            '<th style="min-width:160px">Địa điểm</th>' +
                            '<th style="min-width:140px">Concept</th>' +
                            '<th style="min-width:140px">Người chụp</th>' +
                            '<th style="min-width:140px">Người make</th>' +
                            '<th style="min-width:140px">Người edit</th>' +
                            '</tr></thead><tbody>';

                        items.forEach(function (it) {
                            var time = it.time || '--:--';
                            var kh = it.khach_hang || ('SBR00' + it.id);
                            var roles = (it.roles && it.roles.length) ? it.roles.join(', ') : '';
                            var diaDiem = it.dia_diem || '';
                            var concept = it.concept || '';
                            var pc = it.phan_cong || {};
                            var nguoiChup = pc.chup || '';
                            var nguoiMake = pc.make || '';
                            var nguoiEdit = pc.edit || '';
                            html += '<tr>' +
                                '<td><strong>' + escapeHtml(time) + '</strong></td>' +
                                '<td>' + escapeHtml(kh) + '</td>' +
                                '<td>' + escapeHtml(diaDiem) + '</td>' +
                                '<td>' + escapeHtml(concept) + '</td>' +
                                '<td>' + escapeHtml(nguoiChup) + '</td>' +
                                '<td>' + escapeHtml(nguoiMake) + '</td>' +
                                '<td>' + escapeHtml(nguoiEdit) + '</td>' +
                                '</tr>';
                        });

                        html += '</tbody></table></div>';
                        bodyEl.innerHTML = html;
                    })
                    .catch(function () {
                        bodyEl.classList.add('is-centered');
                        bodyEl.innerHTML = '<div class="alert alert-danger mb-0">Không tải được dữ liệu chi tiết. Vui lòng thử lại.</div>';
                    });
            }

            function escapeHtml(s) {
                return String(s ?? '').replace(/[&<>"']/g, function (c) {
                    return ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c]);
                });
            }
        });
    </script>
@endpush