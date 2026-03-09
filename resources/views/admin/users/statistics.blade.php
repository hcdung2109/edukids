@extends('admin.layouts.app')

@section('title', 'Thống kê – ' . $user->name)

@push('styles')
<style>
    .salary-table th, .salary-table td { vertical-align: middle; }
    .salary-table { table-layout: fixed; }
    .salary-table th, .salary-table td { padding: 0.35rem 0.5rem; }
    .salary-table .month-col { width: 110px; }
    .salary-table .hours-col { width: 120px; }
    .salary-table .salary-col { width: 140px; }
    .salary-table .paid-col { width: 90px; }
    .salary-table .num { white-space: nowrap; font-weight: 600; }
    .salary-table .num-muted { color: #6c757d; font-weight: 500; }
</style>
@endpush

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Thống kê: {{ $user->name }}</h3>
        <a href="{{ route('admin.users.index') }}" class="btn btn-sm btn-default"><i class="fas fa-arrow-left"></i> Danh sách tài khoản</a>
    </div>
    <div class="card-body">
        @if($user->role !== \App\Models\User::ROLE_TEACHER)
            <p class="text-muted mb-0">Tài khoản này không phải giáo viên, không có lớp được gán.</p>
        @else
            <div class="row">
                {{-- Cột trái: Tổng số giờ đã dạy theo 12 tháng --}}
                <div class="col-12 col-lg-6 mb-4 mb-lg-0">
                    <div class="card h-100">
                        <div class="card-body">
                            <div class="d-flex flex-wrap align-items-center justify-content-between mb-3">
                                <div>
                                    <h5 class="mb-0">Tổng số giờ đã dạy</h5>
                                    <div class="text-muted small">Theo 12 tháng trong năm {{ $year }}</div>
                                </div>
                                <form method="GET" class="d-flex align-items-end">
                                    <div class="mr-2">
                                        <label class="small mb-1">Năm</label>
                                        <select name="year" class="form-control form-control-sm">
                                            @for($y = now()->year; $y >= now()->year - 10; $y--)
                                                <option value="{{ $y }}" {{ (int) $year === $y ? 'selected' : '' }}>{{ $y }}</option>
                                            @endfor
                                        </select>
                                    </div>
                                    <button type="submit" class="btn btn-primary btn-sm mb-0"><i class="fas fa-filter"></i> Lọc</button>
                                </form>
                            </div>

                            <table class="table table-bordered table-striped table-sm mb-0 salary-table">
                                <thead>
                                    <tr>
                                        <th class="month-col">Tháng</th>
                                        <th class="text-right hours-col">Tổng số giờ</th>
                                        <th class="text-right salary-col">Tổng lương</th>
                                        <th class="text-center paid-col">Đã trả lương</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach([1=>'Tháng 1', 2=>'Tháng 2', 3=>'Tháng 3', 4=>'Tháng 4', 5=>'Tháng 5', 6=>'Tháng 6', 7=>'Tháng 7', 8=>'Tháng 8', 9=>'Tháng 9', 10=>'Tháng 10', 11=>'Tháng 11', 12=>'Tháng 12'] as $m => $label)
                                        @php
                                            $mHours = (float) ($monthlyHours[$m] ?? 0);
                                            $mSalary = (float) ($monthlySalary[$m] ?? 0);
                                            $mPaid = !empty($paidMonths[$m]);
                                        @endphp
                                        <tr>
                                            <td>{{ $label }}</td>
                                            <td class="text-right">
                                                <span class="num {{ $mHours > 0 ? '' : 'num-muted' }}">{{ number_format($mHours, 1, ',', '') }} giờ</span>
                                            </td>
                                            <td class="text-right">
                                                @if(($salaryPerHour ?? 0) > 0)
                                                    <span class="num {{ $mSalary > 0 ? '' : 'num-muted' }}">{{ number_format($mSalary, 0, ',', '.') }} đ</span>
                                                @else
                                                    <span class="text-muted small">Chưa có lương/giờ</span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                <div class="custom-control custom-switch d-inline-block">
                                                    <input
                                                        type="checkbox"
                                                        class="custom-control-input js-salary-paid"
                                                        id="paid_{{ $m }}"
                                                        data-month="{{ $m }}"
                                                        {{ $mPaid ? 'checked' : '' }}
                                                    >
                                                    <label class="custom-control-label" for="paid_{{ $m }}"></label>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                    <tr class="font-weight-bold">
                                        <td>Tổng cả năm {{ $year }}</td>
                                        <td class="text-right"><span class="num">{{ number_format($totalHours, 1, ',', '') }} giờ</span></td>
                                        <td class="text-right">
                                            @if(($salaryPerHour ?? 0) > 0)
                                                <span class="num">{{ number_format($totalSalary ?? 0, 0, ',', '.') }} đ</span>
                                            @endif
                                        </td>
                                        <td></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                {{-- Cột phải: Danh sách lớp giáo viên đã/đang dạy --}}
                <div class="col-12 col-lg-6">
                    <div class="card h-100">
                        <div class="card-body">
                            <h5 class="mb-2">Danh sách lớp</h5>
                            <p class="text-muted small mb-3">Giáo viên đã dạy / đang dạy / chưa bắt đầu / tạm dừng. Sắp xếp: <strong>Đang học</strong> lên đầu.</p>
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover table-sm mb-0">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Tên lớp</th>
                                            <th>Trung tâm</th>
                                            <th>Khóa học</th>
                                            <th class="text-right">Tổng giờ</th>
                                            <th>Trạng thái</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($classes as $index => $class)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>{{ $class->name }}</td>
                                                <td>{{ $class->center->name ?? '–' }}</td>
                                                <td>{{ $class->course->name ?? '–' }}</td>
                                                <td class="text-right">{{ number_format($hoursByClass[$class->id] ?? 0, 1, ',', '') }} giờ</td>
                                                <td>
                                                    @php
                                                        $statusBadges = [
                                                            \App\Models\CenterClass::STATUS_IN_PROGRESS => 'badge-success',
                                                            \App\Models\CenterClass::STATUS_NOT_STARTED => 'badge-secondary',
                                                            \App\Models\CenterClass::STATUS_PAUSED => 'badge-warning',
                                                            \App\Models\CenterClass::STATUS_COMPLETED => 'badge-info',
                                                        ];
                                                        $badge = $statusBadges[$class->status] ?? 'badge-light';
                                                    @endphp
                                                    <span class="badge {{ $badge }}">{{ $class->status_label }}</span>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="6" class="text-center text-muted">Chưa có lớp nào được gán cho giáo viên này.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
(function() {
    var updateUrl = "{{ route('admin.users.salary-paid', $user) }}";
    var csrf = "{{ csrf_token() }}";
    var year = {{ (int) $year }};

    function setDisabledAll(disabled) {
        document.querySelectorAll('.js-salary-paid').forEach(function(el) {
            el.disabled = disabled;
        });
    }

    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.js-salary-paid').forEach(function(cb) {
            cb.addEventListener('change', function() {
                var month = parseInt(this.getAttribute('data-month'), 10);
                var isPaid = this.checked ? 1 : 0;

                setDisabledAll(true);
                fetch(updateUrl, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': csrf
                    },
                    body: JSON.stringify({ year: year, month: month, is_paid: isPaid })
                }).then(function(r) {
                    if (!r.ok) return r.json().then(function(j) { throw j; });
                    return r.json();
                }).catch(function() {
                    cb.checked = !cb.checked;
                    alert('Không lưu được trạng thái trả lương. Vui lòng thử lại.');
                }).finally(function() {
                    setDisabledAll(false);
                });
            });
        });
    });
})();
</script>
@endpush
