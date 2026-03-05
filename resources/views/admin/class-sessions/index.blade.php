@extends('admin.layouts.app')

@section('title', 'Lịch buổi học – ' . $center_class->name)

@push('styles')
<style>
/* Card & header */
.session-calendar-card { border: none; border-radius: 10px; box-shadow: 0 1px 3px rgba(0,0,0,.08); overflow: hidden; }
.session-calendar-card .card-header { background: #fff; border-bottom: 1px solid #e9ecef; padding: 1rem 1.25rem; }
.session-calendar-card .card-title { font-size: 1.15rem; font-weight: 600; color: #2c3e50; margin-bottom: 0.15rem; }
.session-calendar-card .card-body { padding: 1.25rem; }

/* Month nav & actions */
.calendar-nav-wrap { display: flex; flex-wrap: wrap; align-items: center; justify-content: space-between; margin-bottom: 1rem; }
.calendar-nav-wrap > div:first-child { margin-right: 1rem; }
.calendar-nav-wrap > div:last-child .btn { margin-left: 0; }
.calendar-nav-wrap > div:last-child .btn + .btn { margin-left: 0.5rem; }
.calendar-nav-wrap .btn-outline-secondary { border-radius: 8px; padding: 0.4rem 0.75rem; font-weight: 500; transition: background .2s, border-color .2s; }
.calendar-nav-wrap .btn-outline-secondary:hover { background: #f0f2f5; border-color: #adb5bd; }
.calendar-month-title { font-size: 1.25rem; font-weight: 700; color: #2c3e50; letter-spacing: -0.02em; }
.calendar-nav-wrap .btn-primary { border-radius: 8px; font-weight: 500; box-shadow: 0 1px 2px rgba(0,0,0,.06); }
.calendar-nav-wrap .btn-info { border-radius: 8px; font-weight: 500; }

/* Legend */
.calendar-legend { font-size: 0.8125rem; color: #6c757d; margin-bottom: 1rem; padding: 0.5rem 0; }
.calendar-legend .legend-badge { display: inline-block; padding: 0.2rem 0.5rem; border-radius: 6px; font-size: 0.75rem; font-weight: 600; }
.calendar-legend .legend-today { display: inline-block; width: 22px; height: 18px; border-radius: 6px; border: 2px solid #17a2b8; vertical-align: middle; margin-right: 0.25rem; }

/* Table */
.calendar-table { table-layout: fixed; width: 100%; border-radius: 10px; overflow: hidden; border-collapse: separate; border-spacing: 0; }
.calendar-table th, .calendar-table td { vertical-align: top; padding: 0.6rem; border: 1px solid #e9ecef; text-align: center; }
.calendar-table thead th { background: linear-gradient(180deg, #f8f9fa 0%, #f0f2f5 100%); font-size: 0.8125rem; font-weight: 600; color: #495057; padding: 0.6rem 0.4rem; }
.calendar-table tbody td { background: #fff; transition: background .15s; }

/* Day cell */
.calendar-day { min-height: 100px; position: relative; border-radius: 8px; padding: 0.4rem; transition: background .2s, transform .15s; }
.calendar-day .day-num { font-size: 1rem; font-weight: 700; color: #2c3e50; margin-bottom: 0.35rem; display: block; }
.calendar-day.other-month { background: #fafbfc; color: #adb5bd; }
.calendar-day.other-month .day-num { color: #adb5bd; font-weight: 500; }
.calendar-day.clickable { cursor: pointer; }
.calendar-day.clickable:hover { background: #f0f2f5 !important; }
.calendar-day.clickable:active { transform: scale(0.98); }

/* Session indicators */
.calendar-day .session-labels { margin-top: 0.35rem; margin-bottom: 0.25rem; }
.calendar-day .session-time-label { display: inline-block; font-size: 0.8rem; color: crimson; font-weight: 600; line-height: 1.3; padding: 0.15rem 0.4rem; margin: 0.1rem 0.05rem 0.1rem 0; background: rgba(40,167,69,.15); border-radius: 6px; white-space: nowrap; }
.calendar-day .session-dots { margin-top: 0.25rem; }
.calendar-day .session-badge { display: inline-block; min-width: 24px; padding: 0.2rem 0.5rem; font-size: 0.75rem; font-weight: 700; line-height: 1.3; border-radius: 12px; background: linear-gradient(180deg, #28a745 0%, #218838 100%); color: #fff; box-shadow: 0 1px 2px rgba(40,167,69,.3); }
.calendar-day.has-sessions { background: linear-gradient(180deg, #e8f5e9 0%, #d4edda 100%); }
.calendar-day.has-sessions .day-num { color: #155724; }

/* Modal list */
.session-list-item { display: flex; justify-content: space-between; align-items: center; padding: 0.5rem 0; border-bottom: 1px solid #eee; }
.session-list-item:last-child { border-bottom: 0; }
</style>
@endpush

@section('content')
<div class="card session-calendar-card">
    <div class="card-header d-flex flex-wrap align-items-center justify-content-between">
        <div>
            <h3 class="card-title">Lịch buổi học: {{ $center_class->name }}</h3>
            <p class="text-muted small mb-0">Trung tâm: {{ $center->name }}</p>
        </div>
        <a href="{{ route('admin.centers.classes.index', $center) }}" class="btn btn-sm btn-outline-secondary"><i class="fas fa-arrow-left"></i> Về danh sách lớp</a>
    </div>
    <div class="card-body">
        <div class="calendar-nav-wrap">
            <div class="d-flex align-items-center flex-wrap">
                <a href="{{ route('admin.centers.classes.sessions.index', [$center, $center_class, 'year' => $prev->year, 'month' => $prev->month]) }}" class="btn btn-sm btn-outline-secondary"><i class="fas fa-chevron-left"></i> Tháng trước</a>
                <span class="calendar-month-title mx-2">{{ $start->translatedFormat('F Y') }}</span>
                <a href="{{ route('admin.centers.classes.sessions.index', [$center, $center_class, 'year' => $next->year, 'month' => $next->month]) }}" class="btn btn-sm btn-outline-secondary">Tháng sau <i class="fas fa-chevron-right"></i></a>
            </div>
            <div class="d-flex align-items-center">
                @if(isset($canTakeAttendance) && $canTakeAttendance)
                    <a href="{{ route('admin.centers.classes.attendance.index', [$center, $center_class]) }}" target="_blank" class="btn btn-sm btn-info mr-2"><i class="fas fa-clipboard-check"></i> Điểm danh</a>
                @endif
                <form action="{{ route('admin.centers.classes.sessions.destroy-all', [$center, $center_class]) }}" method="POST" class="d-inline mr-2" onsubmit="return confirm('Xóa toàn bộ lịch học?');">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-outline-danger"><i class="fas fa-trash-alt"></i> Clear All</button>
                </form>
                <button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#addSessionModal" id="btnAddSessionGlobal"><i class="fas fa-plus"></i> Đánh dấu buổi học</button>
            </div>
        </div>
        @if(isset($totalSessionsInMonth) && $totalSessionsInMonth > 0)
            <p class="calendar-legend mb-2"><i class="fas fa-info-circle text-info"></i> Tháng này có <strong>{{ $totalSessionsInMonth }}</strong> buổi học đã đánh dấu.</p>
        @endif
        <p class="calendar-legend">
            <span class="mr-4"><span class="legend-badge bg-success text-white">1</span> = ngày có buổi học</span>
        </p>

        <table class="table table-bordered calendar-table table-calendar">
            <thead>
                <tr>
                    <th>Thứ 2</th>
                    <th>Thứ 3</th>
                    <th>Thứ 4</th>
                    <th>Thứ 5</th>
                    <th>Thứ 6</th>
                    <th>Thứ 7</th>
                    <th>Chủ nhật</th>
                </tr>
            </thead>
            <tbody>
                @foreach($weeks as $week)
                    <tr>
                        @foreach($week as $day)
                            @php
                                $dateKey = $day['date']->toDateString();
                                $isToday = $day['date']->isToday();
                                $sessionsList = is_array($day['sessions']) ? $day['sessions'] : [];
                            @endphp
                            <td class="{{ $day['is_current_month'] ? '' : 'other-month' }}">
                                <div class="calendar-day {{ $day['is_current_month'] ? 'clickable' : '' }} {{ count($sessionsList) > 0 ? 'has-sessions' : '' }}"
                                     data-date="{{ $dateKey }}"
                                     data-day-sessions="{{ $sessionsList ? e(json_encode(array_map(fn($s) => ['id' => $s->id, 'note' => $s->note ?? ''], $sessionsList))) : '[]' }}"
                                     role="button"
                                     tabindex="0">
                                    <span class="day-num">{{ $day['date']->format('j') }}</span>
                                    @if(count($sessionsList) > 0)
                                        <div class="session-labels mt-1">
                                            @foreach($sessionsList as $s)
                                                @if(!empty($s->time_slot))
                                                    <span class="session-time-label">{{ $s->time_slot }}</span>
                                                @endif
                                            @endforeach
                                        </div>
                                        <div class="session-dots mt-0" title="{{ count($sessionsList) }} buổi học">
                                            <span class="session-badge">{{ count($sessionsList) }}</span>
                                        </div>
                                    @endif
                                </div>
                            </td>
                        @endforeach
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

{{-- Modal chọn ngày / xem buổi học --}}
<div class="modal fade" id="dayModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Buổi học – <span id="dayModalTitle"></span></h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="dayModalDate" value="">
                <div id="dayModalSessionsList" class="mb-3"></div>
                <hr>
                <p class="small text-muted mb-2">Thêm buổi học cho ngày này:</p>
                <form id="formAddSession" action="{{ route('admin.centers.classes.sessions.store', [$center, $center_class]) }}" method="POST">
                    @csrf
                    <input type="hidden" name="session_date" id="inputSessionDate" value="">
                    <div class="form-group">
                        <label class="small">Ca học</label>
                        <input type="text" name="time_slot" class="form-control form-control-sm" placeholder="VD: 8h-12h, 14h-17h" maxlength="50">
                    </div>
                    <div class="form-group">
                        <label class="small">Ghi chú</label>
                        <input type="text" name="note" class="form-control form-control-sm" placeholder="VD: Buổi 1 – Chương 2" maxlength="255">
                    </div>
                    <div class="d-flex align-items-center flex-wrap">
                        <button type="submit" class="btn btn-primary btn-sm mr-2"><i class="fas fa-check"></i> Đánh dấu buổi học</button>
                </form>
                        <form id="formDeleteSessionsByDate" action="{{ route('admin.centers.classes.sessions.destroy-by-date', [$center, $center_class]) }}" method="POST" class="d-inline" onsubmit="return confirm('Xóa tất cả buổi học trong ngày này?');">
                            @csrf
                            <input type="hidden" name="session_date" id="inputDeleteSessionDate" value="">
                            <button type="submit" class="btn btn-outline-danger btn-sm"><i class="fas fa-trash-alt mr-1"></i>Xóa buổi học</button>
                        </form>
                    </div>
            </div>
        </div>
    </div>
</div>

{{-- Modal chỉ thêm buổi (chọn ngày) --}}
<div class="modal fade" id="addSessionModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Đánh dấu buổi học</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <form action="{{ route('admin.centers.classes.sessions.store', [$center, $center_class]) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label>Ngày <span class="text-danger">*</span></label>
                        <input type="date" name="session_date" class="form-control" value="{{ now()->format('Y-m-d') }}" required>
                        <small class="form-text text-muted">Chọn đúng ngày cần đánh dấu (ví dụ: 02/03 = 2 tháng 3).</small>
                    </div>
                    <div class="form-group">
                        <label>Ca học</label>
                        <input type="text" name="time_slot" class="form-control" placeholder="VD: 8h-12h, 14h-17h" maxlength="50">
                    </div>
                    <div class="form-group">
                        <label>Ghi chú</label>
                        <input type="text" name="note" class="form-control" placeholder="VD: Buổi 1 – Chương 2" maxlength="255">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary"><i class="fas fa-check"></i> Lưu</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Hủy</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
(function() {
    var destroyUrlBase = "{{ route('admin.centers.classes.sessions.destroy', [$center, $center_class, 0]) }}";
    var sessionsByDateUrl = "{{ route('admin.centers.classes.sessions.by-date', [$center, $center_class]) }}";

    function applySessions(listEl, sessions) {
        if (!listEl) return;
        if (!sessions || sessions.length === 0) {
            listEl.innerHTML = '<p class="text-muted small mb-0">Chưa có buổi học nào trong ngày này.</p>';
        } else {
            var html = '<p class="small font-weight-bold mb-2">Các buổi đã đánh dấu:</p>';
            sessions.forEach(function(s) {
                var timeSlot = (s.time_slot || '').replace(/</g, '&lt;').replace(/>/g, '&gt;');
                var note = (s.note || '').replace(/</g, '&lt;').replace(/>/g, '&gt;');
                var label = timeSlot ? (note ? timeSlot + ' – ' + note : timeSlot) : (note || '(Không ghi chú)');
                var actionUrl = destroyUrlBase.replace(/\/0$/, '/' + s.id);
                html += '<div class="session-list-item d-flex justify-content-between align-items-center mb-2 flex-wrap">';
                html += '<span class="small">' + label + '</span>';
                html += '<form action="' + actionUrl + '" method="POST" class="d-inline" onsubmit="return confirm(\'Xóa buổi học này?\');">';
                html += '<input type="hidden" name="_token" value="{{ csrf_token() }}">';
                html += '<input type="hidden" name="_method" value="DELETE">';
                html += '<button type="submit" class="btn btn-xs btn-link text-danger p-0"><i class="fas fa-trash"></i></button></form>';
                html += '</div>';
            });
            listEl.innerHTML = html;
        }
    }

    function openDayModal(cell) {
        if (!cell || !cell.classList.contains('calendar-day')) return;
        var date = cell.getAttribute('data-date');
        var titleEl = document.getElementById('dayModalTitle');
        var listEl = document.getElementById('dayModalSessionsList');
        if (date) {
            var d = new Date(date + 'T12:00:00');
            var label = d.toLocaleDateString('vi-VN', { weekday: 'long', day: 'numeric', month: 'long', year: 'numeric' });
            if (titleEl) titleEl.textContent = label;
            var dayModalDate = document.getElementById('dayModalDate');
            var inputSessionDate = document.getElementById('inputSessionDate');
            var inputDeleteSessionDate = document.getElementById('inputDeleteSessionDate');
            if (dayModalDate) dayModalDate.value = date;
            if (inputSessionDate) inputSessionDate.value = date;
            if (inputDeleteSessionDate) inputDeleteSessionDate.value = date;
        } else if (titleEl) {
            titleEl.textContent = '';
        }
        if (listEl) listEl.innerHTML = '<p class="text-muted small mb-0"><i class="fas fa-spinner fa-spin"></i> Đang tải...</p>';
        var dayModal = document.getElementById('dayModal');
        if (typeof $ !== 'undefined' && $.fn.modal) $(dayModal).modal('show');
        else if (dayModal && window.bootstrap && window.bootstrap.Modal) (new window.bootstrap.Modal(dayModal)).show();
        if (date) {
            fetch(sessionsByDateUrl + '?date=' + encodeURIComponent(date), { headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' } })
                .then(function(r) { return r.json(); })
                .then(function(data) { applySessions(listEl, data.sessions || []); })
                .catch(function() { if (listEl) listEl.innerHTML = '<p class="text-muted small mb-0">Chưa có buổi học nào trong ngày này.</p>'; });
        } else {
            applySessions(listEl, []);
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        var table = document.querySelector('.table-calendar');
        if (table) {
            table.addEventListener('click', function(e) {
                var cell = e.target.closest('.calendar-day.clickable');
                if (cell) { e.preventDefault(); openDayModal(cell); }
            });
            table.addEventListener('keydown', function(e) {
                if ((e.key === 'Enter' || e.key === ' ') && e.target.closest('.calendar-day.clickable')) {
                    e.preventDefault(); openDayModal(e.target.closest('.calendar-day.clickable'));
                }
            });
        }
    });
})();
</script>
@endpush
