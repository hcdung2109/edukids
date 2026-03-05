@extends('admin.layouts.app')

@section('title', 'Điểm danh – ' . $center_class->name)

@push('styles')
<style>
.attendance-wrap { display: flex; border: 1px solid #dee2e6; border-radius: 4px; overflow: hidden; background: #fff; }
.attendance-left { flex: 0 0 auto; width: 220px; overflow: hidden; border-right: 1px solid #dee2e6; }
.attendance-right { flex: 1 1 auto; min-width: 0; overflow-x: auto; -webkit-overflow-scrolling: touch; }
.attendance-wrap .table { margin-bottom: 0; border-collapse: collapse; }
.attendance-wrap .table th,
.attendance-wrap .table td { padding: 0.5rem 0.75rem; vertical-align: middle; border: 1px solid #dee2e6; box-sizing: border-box; }
.attendance-left .table { width: 100%; table-layout: fixed; }
.attendance-left .table thead th { background: #f8f9fa; font-size: 0.875rem; border-top: 0; }
.attendance-left .table th:first-child { width: 50px; }
.attendance-left .table tbody td:last-child { overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
.attendance-right .table { min-width: max-content; table-layout: auto; }
.attendance-right .table thead th { background: #f8f9fa; font-size: 0.875rem; border-top: 0; border-left: 0; }
.attendance-right .table td { min-width: 70px; text-align: center; padding: 0.5rem 0.35rem; border-left: 0; }
.attendance-right .table th { min-width: 70px; text-align: center; padding: 0.5rem 0.35rem; }
.attendance-wrap .table tbody tr:last-child td { border-bottom: 0; }
.attendance-wrap .table tbody td { line-height: 1.3; }
.attendance-wrap .table thead th { height: 44px; }
.attendance-wrap .table tbody td { height: 44px; }
.attendance-checkall { display: inline-flex; align-items: center; justify-content: center; gap: 0.35rem; white-space: nowrap; }
.attendance-checkall label { margin: 0; font-weight: 600; font-size: 0.75rem; color: #6c757d; cursor: pointer; }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    var toggles = document.querySelectorAll('.js-session-checkall');
    if (!toggles || toggles.length === 0) return;

    function getBoxes(sessionId) {
        return Array.prototype.slice.call(
            document.querySelectorAll('input.js-attendance-checkbox[data-session-id="' + sessionId + '"]')
        );
    }

    function syncToggle(toggleEl, sessionId) {
        var boxes = getBoxes(sessionId);
        if (boxes.length === 0) {
            toggleEl.checked = false;
            toggleEl.indeterminate = false;
            toggleEl.disabled = true;
            return;
        }

        var checkedCount = boxes.filter(function (b) { return b.checked; }).length;
        toggleEl.disabled = false;
        toggleEl.checked = checkedCount === boxes.length;
        toggleEl.indeterminate = checkedCount > 0 && checkedCount < boxes.length;
    }

    toggles.forEach(function (toggleEl) {
        var sessionId = toggleEl.getAttribute('data-session-id');
        if (!sessionId) return;

        // Init
        syncToggle(toggleEl, sessionId);

        // Toggle all in session
        toggleEl.addEventListener('change', function () {
            var boxes = getBoxes(sessionId);
            boxes.forEach(function (b) { b.checked = toggleEl.checked; });
            syncToggle(toggleEl, sessionId);
        });

        // When any box changes, update "Tất cả"
        getBoxes(sessionId).forEach(function (boxEl) {
            boxEl.addEventListener('change', function () {
                syncToggle(toggleEl, sessionId);
            });
        });
    });
});
</script>
@endpush

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title"><i class="fas fa-clipboard-check"></i> Điểm danh – {{ $center_class->name }}</h3>
        <p class="text-muted small mb-0">Trung tâm: {{ $center->name }}</p>
        <div class="card-tools">
            <a href="{{ route('admin.centers.classes.attendance.export', [$center, $center_class]) }}" class="btn btn-sm btn-success mr-2" target="_blank"><i class="fas fa-file-excel"></i> Xuất Excel</a>
            <a href="{{ route('admin.centers.classes.sessions.index', [$center, $center_class]) }}" class="btn btn-sm btn-default"><i class="fas fa-arrow-left"></i> Về lịch buổi học</a>
        </div>
    </div>
    <div class="card-body">
        <p class="small text-muted mb-2">Chọn ô nếu học viên có đi học buổi tương ứng.</p>

        <form method="POST" action="{{ route('admin.centers.classes.attendance.store', [$center, $center_class]) }}">
            @csrf
            <div class="attendance-wrap">
                <div class="attendance-left">
                    <table class="table table-bordered table-sm">
                        <thead>
                            <tr>
                                <th class="text-nowrap">STT</th>
                                <th class="text-nowrap">Họ tên</th>
                            </tr>
                            <tr>
                                <th class="text-nowrap">&nbsp;</th>
                                <th class="text-nowrap">&nbsp;</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($students as $i => $student)
                                <tr>
                                    <td class="text-nowrap">{{ $i + 1 }}</td>
                                    <td class="text-nowrap">{{ $student->name }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="2" class="text-muted text-center py-3">Chưa có học viên.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="attendance-right">
                    <table class="table table-bordered table-sm">
                        <thead>
                            <tr>
                                @foreach($sessions as $s)
                                    <th class="text-center text-nowrap">{{ $s->note ?: ('Buổi ' . $s->session_date->format('d/m/Y')) }}</th>
                                @endforeach
                            </tr>
                            <tr>
                                @foreach($sessions as $s)
                                    <th class="text-center">
                                        <span class="attendance-checkall">
                                            <label for="checkAllSession{{ $s->id }}">Tất cả</label>
                                            <input
                                                id="checkAllSession{{ $s->id }}"
                                                type="checkbox"
                                                class="js-session-checkall"
                                                data-session-id="{{ $s->id }}"
                                            >
                                        </span>
                                    </th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($students as $i => $student)
                                <tr>
                                    @foreach($sessions as $s)
                                        @php
                                            $checked = isset($attendance[$s->id][$student->id]) && $attendance[$s->id][$student->id];
                                        @endphp
                                        <td class="text-center">
                                            <input type="hidden" name="attendance[{{ $s->id }}][{{ $student->id }}]" value="0">
                                            <input
                                                type="checkbox"
                                                class="js-attendance-checkbox"
                                                data-session-id="{{ $s->id }}"
                                                name="attendance[{{ $s->id }}][{{ $student->id }}]"
                                                value="1"
                                                {{ $checked ? 'checked' : '' }}
                                            >
                                        </td>
                                    @endforeach
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="{{ $sessions->count() }}" class="text-muted text-center py-3">Chưa có học viên hoặc buổi học.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @if($students->isNotEmpty())
                <button type="submit" class="btn btn-primary btn-sm mt-2"><i class="fas fa-save"></i> Lưu điểm danh</button>
            @endif
        </form>
    </div>
</div>
@endsection
