@extends('admin.layouts.app')

@section('content')
@if(empty($nhanVienId) && empty($isAdmin))
    <div class="card">
        <div class="card-body">
            <div class="alert alert-warning mb-0">
                Tài khoản của bạn chưa có hồ sơ nhân viên nên chưa thể lọc theo thợ chụp/make/edit.
            </div>
        </div>
    </div>
@else
    <div class="card app-calendar-wrapper" id="ws-lich-lam-viec">
        <div class="row g-0">
            <div class="col app-calendar-sidebar border-end" id="app-calendar-sidebar">
                <div class="px-3 pt-2">
                    <div class="inline-calendar"></div>
                </div>
                <hr class="mb-6 mx-n4 mt-3" />
                <div class="px-6 pb-2">
                    <div class="mb-4">
                        <h5 class="text-heading">Hiển thị vai trò</h5>
                        <p class="text-muted small mb-0"><strong>C</strong>(chụp), <strong>M</strong>(make), <strong>E</strong>(edit)</p>
                    </div>

                    <div class="form-check form-check-secondary mb-5 ms-2">
                        <input class="form-check-input ws-select-all" type="checkbox" id="wsSelectAll" data-value="all" checked />
                        <label class="form-check-label" for="wsSelectAll">Xem tất cả</label>
                    </div>

                    <div class="app-calendar-events-filter text-heading">
                        <div class="form-check form-check-primary mb-5 ms-2">
                            <input class="form-check-input ws-input-filter" type="checkbox" id="ws-filter-chup" data-value="chup" checked />
                            <label class="form-check-label" for="ws-filter-chup">Chụp</label>
                        </div>
                        <div class="form-check form-check-warning mb-5 ms-2">
                            <input class="form-check-input ws-input-filter" type="checkbox" id="ws-filter-make" data-value="make" checked />
                            <label class="form-check-label" for="ws-filter-make">Make</label>
                        </div>
                        <div class="form-check form-check-info ms-2">
                            <input class="form-check-input ws-input-filter" type="checkbox" id="ws-filter-edit" data-value="edit" checked />
                            <label class="form-check-label" for="ws-filter-edit">Edit</label>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col app-calendar-content">
                <div class="card shadow-none border-0">
                    <div class="card-body pb-0">
                        <div id="calendar" class="admin-work-calendar"></div>
                    </div>
                </div>
                <div class="app-overlay"></div>
            </div>
        </div>
    </div>
@endif
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
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/fullcalendar/fullcalendar.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/pages/app-calendar.css') }}" />
    <style>
        .admin-work-calendar .fc .fc-daygrid-day-number {
            padding: 6px 8px;
        }
        .admin-work-calendar .fc .fc-daygrid-event {
            border: 0;
            background: transparent;
            padding: 0;
            margin: 2px 6px 0 6px;
        }
        .admin-work-calendar .ws-day-badges {
            display: flex;
            flex-direction: column;
            gap: 4px;
            align-items: stretch;
            width: 100%;
        }
        .admin-work-calendar .ws-role-tag {
            border-radius: 8px;
            padding: 4px 8px;
            font-size: .75rem;
            font-weight: 600;
            line-height: 1.25;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .admin-work-calendar .ws-role-tag-c {
            background: #ffe4e8;
            color: #c53030;
        }
        .admin-work-calendar .ws-role-tag-m {
            background: #ede9fe;
            color: #5b21b6;
        }
        .admin-work-calendar .ws-role-tag-e {
            background: #dbeafe;
            color: #1d4ed8;
        }
        .admin-work-calendar .ws-agenda-event {
            line-height: 1.25;
            font-size: .8125rem;
        }
        .admin-work-calendar .ws-agenda-event__meta {
            font-weight: 600;
            color: #384551;
            margin-bottom: 4px;
        }
        .admin-work-calendar .ws-agenda-event__meta .ws-time {
            font-weight: 700;
            margin-right: 4px;
        }
        #wsWorkDayModalBody.is-centered {
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            min-height: 220px;
        }
    </style>
@endpush

@push('scripts')
@if(!empty($nhanVienId) || !empty($isAdmin))
    <script src="{{ asset('assets/vendor/libs/moment/moment.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/fullcalendar/fullcalendar.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var root = document.getElementById('ws-lich-lam-viec');
            var calendarEl = document.getElementById('calendar');
            if (!root || !calendarEl) return;

            var Calendar = window.Calendar;
            var dayGridPlugin = window.dayGridPlugin;
            var interactionPlugin = window.interactionPlugin;
            var listPlugin = window.listPlugin;
            var timegridPlugin = window.timegridPlugin;
            if (!Calendar || !dayGridPlugin || !interactionPlugin || !listPlugin || !timegridPlugin) return;

            var direction = (typeof isRtl !== 'undefined' && isRtl) ? 'rtl' : 'ltr';
            var appCalendarSidebar = root.querySelector('.app-calendar-sidebar');
            var appOverlay = root.querySelector('.app-overlay');
            var inlineCalendar = root.querySelector('.inline-calendar');
            var selectAll = root.querySelector('.ws-select-all');
            var filterInputs = Array.from(root.querySelectorAll('.ws-input-filter'));

            function modifyToggler() {
                var fcSidebarToggleButton = document.querySelector('.admin-work-calendar .fc-sidebarToggle-button');
                if (!fcSidebarToggleButton || !appCalendarSidebar || !appOverlay) return;
                fcSidebarToggleButton.classList.remove('fc-button-primary');
                fcSidebarToggleButton.classList.add('d-lg-none', 'd-inline-block', 'ps-0');
                while (fcSidebarToggleButton.firstChild) {
                    fcSidebarToggleButton.firstChild.remove();
                }
                fcSidebarToggleButton.setAttribute('type', 'button');
                fcSidebarToggleButton.insertAdjacentHTML(
                    'beforeend',
                    '<i class="icon-base ti tabler-menu-2 icon-lg text-heading"></i>'
                );
                if (!fcSidebarToggleButton.dataset.wsSidebarBound) {
                    fcSidebarToggleButton.dataset.wsSidebarBound = '1';
                    fcSidebarToggleButton.addEventListener('click', function (e) {
                        e.preventDefault();
                        e.stopPropagation();
                        appCalendarSidebar.classList.toggle('show');
                        appOverlay.classList.toggle('show');
                    });
                }
            }

            if (appOverlay && !appOverlay.dataset.wsOverlayBound) {
                appOverlay.dataset.wsOverlayBound = '1';
                appOverlay.addEventListener('click', function () {
                    if (appCalendarSidebar) appCalendarSidebar.classList.remove('show');
                    appOverlay.classList.remove('show');
                });
            }

            function selectedFilters() {
                return filterInputs.filter(function (el) { return el.checked; }).map(function (el) {
                    return el.getAttribute('data-value');
                });
            }

            function buildBadgesDom(badges) {
                var filters = selectedFilters();
                var roleKey = { C: 'chup', M: 'make', E: 'edit' };
                var filtered = (badges || []).filter(function (b) {
                    var code = String(b.code || '').toUpperCase();
                    var key = roleKey[code];
                    return key && filters.indexOf(key) !== -1;
                });
                if (!filtered.length) return null;
                var wrap = document.createElement('div');
                wrap.className = 'ws-day-badges';
                filtered.forEach(function (b) {
                    var code = String(b.code || '').toUpperCase();
                    var row = document.createElement('div');
                    row.className = 'ws-role-tag ws-role-tag-' + code.toLowerCase();
                    row.textContent = code + ': ' + String(b.name || '');
                    wrap.appendChild(row);
                });
                return wrap;
            }

            var calendar = new Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                plugins: [dayGridPlugin, interactionPlugin, listPlugin, timegridPlugin],
                height: 'auto',
                firstDay: 1,
                editable: false,
                selectable: false,
                dayMaxEvents: true,
                nowIndicator: true,
                slotMinTime: '06:00:00',
                slotMaxTime: '22:00:00',
                direction: direction,
                navLinks: true,
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
                customButtons: {
                    sidebarToggle: { text: 'Sidebar' }
                },
                headerToolbar: {
                    left: 'sidebarToggle prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay,listMonth'
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
                    var p = (arg.event.extendedProps || {});
                    var badges = p.badges || [];
                    if (arg.view && (arg.view.type === 'dayGridMonth' || arg.view.type === 'listMonth')) {
                        if (!badges.length) return { domNodes: [] };
                        var node = buildBadgesDom(badges);
                        if (!node) return { domNodes: [] };
                        return { domNodes: [node] };
                    }
                    if (arg.view && (arg.view.type === 'timeGridWeek' || arg.view.type === 'timeGridDay')) {
                        var wrap = document.createElement('div');
                        wrap.className = 'ws-agenda-event';
                        var meta = document.createElement('div');
                        meta.className = 'ws-agenda-event__meta';
                        var timeText = arg.timeText ? arg.timeText : '';
                        if (timeText) {
                            var tEl = document.createElement('span');
                            tEl.className = 'ws-time';
                            tEl.textContent = timeText + ' ';
                            meta.appendChild(tEl);
                        }
                        var titleEl = document.createElement('span');
                        titleEl.textContent = arg.event.title || '';
                        meta.appendChild(titleEl);
                        wrap.appendChild(meta);
                        var badgeBox = buildBadgesDom(badges);
                        if (badgeBox) wrap.appendChild(badgeBox);
                        return { domNodes: [wrap] };
                    }
                    return { domNodes: [] };
                },
                dateClick: function (info) {
                    var d = info.date;
                    var y = d.getFullYear();
                    var m = String(d.getMonth() + 1).padStart(2, '0');
                    var day = String(d.getDate()).padStart(2, '0');
                    openWorkDayModal(y + '-' + m + '-' + day);
                },
                eventClick: function (info) {
                    info.jsEvent.preventDefault();
                    var start = info.event.start;
                    if (!start) return;
                    var y = start.getFullYear();
                    var m = String(start.getMonth() + 1).padStart(2, '0');
                    var day = String(start.getDate()).padStart(2, '0');
                    openWorkDayModal(y + '-' + m + '-' + day);
                },
                datesSet: function () {
                    modifyToggler();
                },
                viewDidMount: function () {
                    modifyToggler();
                }
            });

            calendar.render();
            modifyToggler();

            if (inlineCalendar && typeof flatpickr !== 'undefined') {
                flatpickr(inlineCalendar, {
                    monthSelectorType: 'static',
                    static: true,
                    inline: true,
                    locale: { firstDayOfWeek: 1 },
                    defaultDate: new Date(),
                    onChange: function (selectedDates) {
                        if (!selectedDates || !selectedDates.length) return;
                        var d = selectedDates[0];
                        if (typeof moment !== 'undefined') {
                            calendar.gotoDate(moment(d).format('YYYY-MM-DD'));
                        } else {
                            calendar.gotoDate(
                                d.getFullYear() + '-' +
                                String(d.getMonth() + 1).padStart(2, '0') + '-' +
                                String(d.getDate()).padStart(2, '0')
                            );
                        }
                        modifyToggler();
                        if (appCalendarSidebar) appCalendarSidebar.classList.remove('show');
                        if (appOverlay) appOverlay.classList.remove('show');
                    }
                });
            }

            if (selectAll) {
                selectAll.addEventListener('change', function (e) {
                    if (e.currentTarget.checked) {
                        filterInputs.forEach(function (c) { c.checked = true; });
                    } else {
                        filterInputs.forEach(function (c) { c.checked = false; });
                    }
                    calendar.refetchEvents();
                });
            }
            filterInputs.forEach(function (item) {
                item.addEventListener('change', function () {
                    var checked = filterInputs.filter(function (c) { return c.checked; }).length;
                    if (selectAll) {
                        selectAll.checked = checked === filterInputs.length;
                        selectAll.indeterminate = checked > 0 && checked < filterInputs.length;
                    }
                    calendar.refetchEvents();
                });
            });

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
                            '<th style="min-width:180px">Mã hợp đồng</th>' +
                            '<th style="min-width:160px">Địa điểm</th>' +
                            '<th style="min-width:140px">Concept</th>' +
                            '<th style="min-width:140px">Người chụp</th>' +
                            '<th style="min-width:140px">Người make</th>' +
                            '<th style="min-width:140px">Người edit</th>' +
                            '</tr></thead><tbody>';

                        items.forEach(function (it) {
                            var time = it.time || '--:--';
                            var maHd = (it.ma_hop_dong && String(it.ma_hop_dong).trim())
                                ? String(it.ma_hop_dong).trim()
                                : ('HĐ #' + it.id);
                            var diaDiem = it.dia_diem || '';
                            var concept = it.concept || '';
                            var pc = it.phan_cong || {};
                            var nguoiChup = pc.chup || '';
                            var nguoiMake = pc.make || '';
                            var nguoiEdit = pc.edit || '';
                            html += '<tr>' +
                                '<td><strong>' + escapeHtml(time) + '</strong></td>' +
                                '<td>' + escapeHtml(maHd) + '</td>' +
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
                    return ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c]);
                });
            }
        });
    </script>
@endif
@endpush
