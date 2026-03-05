@extends('admin.layouts.app')

@section('title', 'Lịch buổi học – ' . $center_class->name)

@push('styles')
<style>
.calendar-table { table-layout: fixed; width: 100%; }
.calendar-table th, .calendar-table td { vertical-align: top; padding: 0.5rem; border: 1px solid #dee2e6; text-align: center; }
.calendar-table th { background: #f8f9fa; font-size: 0.875rem; }
.calendar-day { min-height: 90px; position: relative; }
.calendar-day .day-num { font-weight: 600; margin-bottom: 4px; }
.calendar-day.other-month { background: #f8f9fa; color: #adb5bd; }
.calendar-day.today { box-shadow: inset 0 0 0 2px #17a2b8; }
.calendar-day.clickable { cursor: pointer; }
.calendar-day.clickable:hover { background: #e9ecef; }
.calendar-day .session-dots { font-size: 0.7rem; margin-top: 4px; }
.calendar-day .session-dot { display: inline-block; width: 8px; height: 8px; border-radius: 50%; background: #28a745; margin: 1px; }
.calendar-day .session-badge { display: inline-block; min-width: 22px; padding: 2px 6px; font-size: 0.8rem; font-weight: 700; line-height: 1.3; border-radius: 10px; background: #28a745; color: #fff; margin-top: 4px; }
.calendar-day.has-sessions { background: #d4edda; }
.calendar-day.has-sessions .day-num { color: #155724; font-weight: 700; }
.calendar-day.has-sessions.today { box-shadow: inset 0 0 0 2px #17a2b8; }
.session-list-item { display: flex; justify-content: space-between; align-items: center; padding: 0.35rem 0; border-bottom: 1px solid #eee; }
.session-list-item:last-child { border-bottom: 0; }
.max-height-200 { max-height: 200px; }
</style>
@endpush

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Lịch buổi học: {{ $center_class->name }}</h3>
        <p class="text-muted small mb-0">Trung tâm: {{ $center->name }}</p>
        <div class="card-tools">
            <a href="{{ route('admin.centers.classes.index', $center) }}" class="btn btn-sm btn-default"><i class="fas fa-arrow-left"></i> Về danh sách lớp</a>
        </div>
    </div>
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center flex-wrap mb-3">
            <div>
                <a href="{{ route('admin.centers.classes.sessions.index', [$center, $center_class, 'year' => $prev->year, 'month' => $prev->month]) }}" class="btn btn-sm btn-outline-secondary"><i class="fas fa-chevron-left"></i> Tháng trước</a>
                <span class="mx-3 h5 mb-0 font-weight-bold">{{ $start->translatedFormat('F Y') }}</span>
                <a href="{{ route('admin.centers.classes.sessions.index', [$center, $center_class, 'year' => $next->year, 'month' => $next->month]) }}" class="btn btn-sm btn-outline-secondary">Tháng sau <i class="fas fa-chevron-right"></i></a>
            </div>
            <div>
                @if(isset($canTakeAttendance) && $canTakeAttendance)
                    <a href="{{ route('admin.centers.classes.attendance.index', [$center, $center_class]) }}" target="_blank" class="btn btn-sm btn-info mr-2"><i class="fas fa-clipboard-check"></i> Điểm danh</a>
                @endif
                <button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#addSessionModal" id="btnAddSessionGlobal"><i class="fas fa-plus"></i> Đánh dấu buổi học</button>
            </div>
        </div>
        @if(isset($totalSessionsInMonth) && $totalSessionsInMonth > 0)
            <p class="text-muted small mb-2"><i class="fas fa-info-circle"></i> Tháng này có <strong>{{ $totalSessionsInMonth }}</strong> buổi học đã đánh dấu.</p>
        @endif
        <p class="small text-muted mb-2">
            <span class="mr-3"><span class="d-inline-block rounded px-2 py-0 bg-success text-white font-weight-bold" style="font-size: 0.75rem;">1</span> = ngày có buổi học</span>
            <span><span class="d-inline-block border border-info rounded px-1" style="width: 24px; height: 18px;"></span> = hôm nay</span>
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
                                <div class="calendar-day {{ $day['is_current_month'] ? 'clickable' : '' }} {{ $isToday ? 'today' : '' }} {{ count($sessionsList) > 0 ? 'has-sessions' : '' }}"
                                     data-date="{{ $dateKey }}"
                                     data-day-sessions="{{ $sessionsList ? e(json_encode(array_map(fn($s) => ['id' => $s->id, 'note' => $s->note ?? ''], $sessionsList))) : '[]' }}"
                                     role="button"
                                     tabindex="0">
                                    <span class="day-num">{{ $day['date']->format('j') }}</span>
                                    @if(count($sessionsList) > 0)
                                        <div class="session-dots" title="{{ count($sessionsList) }} buổi học">
                                            <span class="session-badge">{{ count($sessionsList) }}</span>
                                            @foreach(array_slice($sessionsList, 0, 4) as $s)
                                                <span class="session-dot"></span>
                                            @endforeach
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
                        <label class="small">Ghi chú (tùy chọn)</label>
                        <input type="text" name="note" class="form-control form-control-sm" placeholder="VD: Buổi 1 – Chương 2" maxlength="255">
                    </div>
                    <button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-check"></i> Đánh dấu buổi học</button>
                </form>
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
                        <label>Ghi chú (tùy chọn)</label>
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
                var note = (s.note || '').replace(/</g, '&lt;').replace(/>/g, '&gt;');
                var actionUrl = destroyUrlBase.replace(/\/0$/, '/' + s.id);
                html += '<div class="session-list-item d-flex justify-content-between align-items-center mb-2 flex-wrap">';
                html += '<span class="small">' + (note || '(Không ghi chú)') + '</span>';
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
            if (dayModalDate) dayModalDate.value = date;
            if (inputSessionDate) inputSessionDate.value = date;
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
