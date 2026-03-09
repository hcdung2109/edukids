@extends('admin.layouts.app')

@section('title', 'Dashboard')

@push('styles')
<style>
.dashboard-center-card {
    height: 100%;
    border-radius: 10px;
    border: 1px solid #e9ecef;
    transition: box-shadow .2s, transform .02s;
}
.dashboard-center-card:hover {
    box-shadow: 0 4px 12px rgba(0,0,0,.08);
}
.dashboard-center-card .card-body { padding: 1.25rem; }
.dashboard-center-card .center-icon-wrap {
    width: 48px; height: 48px;
    border-radius: 10px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: #fff;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.25rem;
}
.dashboard-center-card .center-name {
    font-size: 1.1rem;
    font-weight: 600;
    color: #2d3748;
    margin-bottom: 0.5rem;
}
.dashboard-center-card .center-meta {
    font-size: 0.875rem;
    color: #718096;
    margin-bottom: 0.35rem;
}
.dashboard-center-card .center-meta i {
    width: 18px;
    color: #a0aec0;
}
.dashboard-center-card .center-actions {
    margin-top: 1rem;
    padding-top: 1rem;
    border-top: 1px solid #f0f0f0;
}
/* Nút thao tác Lớp đang học: hàng ngang, có khoảng cách */
.dashboard-class-actions {
    display: flex;
    flex-wrap: nowrap;
    align-items: center;
    gap: 0.5rem;
}
.dashboard-class-actions .btn {
    padding: 0.35rem 0.6rem;
    margin: 0;
    white-space: nowrap;
}
</style>
@endpush

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <p class="mb-0">Chào mừng <strong>{{ auth()->user()->name }}</strong> đến trang quản trị EduKids.</p>
                <p class="text-muted small mt-1">Vai trò: <span class="badge badge-primary">{{ auth()->user()->role }}</span></p>
            </div>
        </div>
    </div>
</div>

@if(isset($classesInProgress) && $classesInProgress->isNotEmpty())
<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center flex-wrap">
                <h5 class="mb-0 font-weight-bold text-dark"><i class="fas fa-chalkboard-teacher mr-1"></i> Lớp đang học</h5>
                <a href="{{ route('admin.centers.index') }}" class="btn btn-sm btn-outline-primary">Xem tất cả lớp</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover table-striped mb-0">
                        <thead class="thead-light">
                            <tr>
                                <th style="width: 28%;">Lớp học</th>
                                <th style="width: 25%;">Trung tâm</th>
                                <th>Giáo viên đang dạy</th>
                                <th class="text-nowrap" style="min-width: 220px;">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($classesInProgress as $class)
                            <tr>
                                <td>
                                    <a href="{{ route('admin.centers.classes.sessions.index', [$class->center, $class]) }}">{{ $class->name }}</a>
                                    @if($class->course)
                                        <br><small class="text-muted">{{ $class->course->name }}</small>
                                    @endif
                                </td>
                                <td>{{ $class->center->name ?? '—' }}</td>
                                <td>
                                    @forelse($class->teachers as $teacher)
                                        <span class="badge badge-primary badge-sm mr-1">{{ $teacher->name }}</span>
                                    @empty
                                        <span class="text-muted">Chưa gán</span>
                                    @endforelse
                                </td>
                                <td>
                                    <div class="dashboard-class-actions">
                                        @if($class->course)
                                            <a href="{{ route('admin.courses.materials.index', $class->course) }}" class="btn btn-sm btn-outline-secondary" title="Tài liệu"><i class="fas fa-file-alt mr-1"></i> Tài liệu</a>
                                        @endif
                                        <a href="{{ route('admin.centers.classes.attendance.index', [$class->center, $class]) }}" class="btn btn-sm btn-outline-info" title="Điểm danh"><i class="fas fa-clipboard-check mr-1"></i> Điểm danh</a>
                                        <a href="{{ route('admin.centers.classes.sessions.index', [$class->center, $class]) }}" class="btn btn-sm btn-outline-primary" title="Lịch học"><i class="fas fa-calendar-alt mr-1"></i> Lịch học</a>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endif

<div class="row mt-4">
    <div class="col-12 d-flex justify-content-between align-items-center flex-wrap mb-3">
        <h5 class="mb-0 font-weight-bold text-dark">Trung tâm</h5>
        <div>
            <a href="{{ route('admin.centers.create') }}" class="btn btn-sm btn-primary"><i class="fas fa-plus"></i> Thêm trung tâm</a>
            <a href="{{ route('admin.centers.index') }}" class="btn btn-sm btn-outline-secondary">Xem tất cả</a>
        </div>
    </div>
    @if($centers->isEmpty())
        <div class="col-12">
            <div class="card border-0 bg-light">
                <div class="card-body text-center py-5">
                    <i class="fas fa-building fa-3x text-muted mb-3"></i>
                    <p class="text-muted mb-2">Chưa có trung tâm nào</p>
                    <a href="{{ route('admin.centers.create') }}" class="btn btn-primary"><i class="fas fa-plus"></i> Thêm trung tâm đầu tiên</a>
                </div>
            </div>
        </div>
    @else
        @foreach($centers as $center)
            <div class="col-12 col-sm-6 col-lg-4 mb-3">
                <div class="card dashboard-center-card h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-start mb-2">
                            <div class="center-icon-wrap mr-3 flex-shrink-0">
                                <i class="fas fa-building"></i>
                            </div>
                            <div class="flex-grow-1 min-width-0">
                                <h6 class="center-name mb-1">{{ $center->name }}</h6>
                                @if($center->is_active)
                                    <span class="badge badge-success badge-sm">Hiển thị</span>
                                @else
                                    <span class="badge badge-secondary badge-sm">Ẩn</span>
                                @endif
                            </div>
                        </div>
                        @if($center->address)
                            <p class="center-meta mb-1"><i class="fas fa-map-marker-alt"></i> {{ Str::limit($center->address, 45) }}</p>
                        @endif
                        @if($center->phone)
                            <p class="center-meta mb-0"><i class="fas fa-phone-alt"></i> {{ $center->phone }}</p>
                        @endif
                        <div class="center-actions d-flex justify-content-between align-items-center flex-wrap gap-1">
                            <a href="{{ route('admin.centers.classes.index', $center) }}" class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-graduation-cap"></i> Lớp học ({{ $center->classes_count }})
                            </a>
                            <a href="{{ route('admin.centers.edit', $center) }}" class="btn btn-sm btn-outline-secondary" title="Sửa"><i class="fas fa-edit"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    @endif
</div>
@endsection
