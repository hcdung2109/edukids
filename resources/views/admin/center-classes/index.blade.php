@extends('admin.layouts.app')

@section('title', 'Danh sách lớp học – ' . $center->name)

@push('styles')
<style>
.table-classes thead th { font-weight: 600; color: #495057; border-bottom-width: 2px; white-space: nowrap; }
.table-classes tbody tr:hover { background-color: #f8f9fa; }
.table-classes .btn-action-wrap { display: inline-flex; align-items: center; gap: 6px; flex-wrap: wrap; }
.table-classes .btn-action-wrap .btn { padding: 0.35rem 0.5rem; min-width: 34px; width: 34px; justify-content: center; }
.table-classes .btn-action-wrap .btn .fa { margin: 0; }
/* Badge thống nhất: tông xanh nhạt cho trạng thái tốt, xám cho trung tính */
.table-classes .badge-status {
    font-size: 0.8rem;
    padding: 0.35em 0.65em;
    border-radius: 6px;
    font-weight: 500;
    border: 1px solid transparent;
}
.table-classes .badge-status.badge-success {
    background-color: #d4edda;
    color: #155724;
    border-color: #c3e6cb;
}
.table-classes .badge-status.badge-secondary {
    background-color: #e9ecef;
    color: #495057;
    border-color: #dee2e6;
}
/* Nút trong bảng: dùng outline để nhẹ hơn */
.table-classes .btn-action-wrap .btn-outline-success { color: #28a745; border-color: #28a745; }
.table-classes .btn-action-wrap .btn-outline-success:hover { background-color: #28a745; color: #fff; }
.table-classes .btn-outline-primary { color: #007bff; border-color: #007bff; }
.table-classes .btn-outline-primary:hover { background-color: #007bff; color: #fff; }
.table-classes .btn-outline-danger:hover { color: #fff; }
/* Nút Học viên và Xem tài liệu: tông trung tính */
.table-classes .btn-outline-info, .table-classes .btn-outline-secondary { color: #5a6268; border-color: #adb5bd; }
.table-classes .btn-outline-info:hover, .table-classes .btn-outline-secondary:hover { background-color: #e9ecef; border-color: #adb5bd; color: #495057; }
</style>
@endpush

@section('content')
<div class="card shadow-sm">
    <div class="card-header bg-white border-bottom py-3">
        <h3 class="card-title mb-0 font-weight-bold text-dark">Danh sách lớp học: {{ $center->name }}</h3>
        <div class="card-tools">
            <a href="{{ route('admin.centers.index') }}" class="btn btn-sm btn-outline-secondary mr-1"><i class="fas fa-arrow-left"></i> Về danh sách trung tâm</a>
            @if(auth()->user()->isAdmin())
            <a href="{{ route('admin.centers.classes.create', $center) }}" class="btn btn-sm btn-primary"><i class="fas fa-plus"></i> Thêm lớp học</a>
            @endif
        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-classes table-hover table-striped mb-0">
                <thead class="thead-light">
                    <tr>
                        <th style="width: 50px" class="text-center">STT</th>
                        <th>Tên lớp học</th>
                        <th>Lịch học</th>
                        <th>Tên khóa học</th>
                        <th class="text-center" style="width: 100px">Tài liệu khóa học</th>
                        <th>Giáo viên</th>
                        <th class="text-center" style="width: 100px">Trạng thái</th>
                        <th class="text-center" style="width: 100px">Học viên</th>
                        <th class="text-center" style="width: 110px">Thu học phí</th>
                        <th class="text-center" style="width: 200px">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $user = auth()->user();
                        $isAdmin = $user && $user->isAdmin();
                    @endphp
                    @forelse($classes as $item)
                        @php $canTakeAttendance = $isAdmin || ($user && $item->teachers->contains($user)); @endphp
                        <tr>
                            <td class="text-center align-middle">{{ $item->sort_order }}</td>
                            <td class="align-middle">
                                <span class="font-weight-medium text-dark">{{ $item->name }}</span>
                            </td>
                            <td class="align-middle text-muted">{{ $item->schedule ?: '–' }}</td>
                            <td class="align-middle">{{ $item->course?->name ?: '–' }}</td>
                            <td class="text-center align-middle">
                                @if($item->course)
                                    <a href="{{ route('admin.courses.materials.index', $item->course) }}" class="btn btn-sm btn-outline-secondary" title="Xem tài liệu khóa học">
                                        <i class="fas fa-folder-open"></i> Xem tài liệu
                                    </a>
                                @else
                                    <span class="text-muted">–</span>
                                @endif
                            </td>
                            <td class="align-middle small">{{ $item->teachers->pluck('name')->join(', ') ?: '–' }}</td>
                            <td class="text-center align-middle">
                                @if($item->is_active)
                                    <span class="badge badge-status badge-success">Hoạt động</span>
                                @else
                                    <span class="badge badge-status badge-secondary">Ẩn</span>
                                @endif
                            </td>
                            <td class="text-center align-middle">
                                <a href="{{ route('admin.centers.classes.students.index', [$center, $item]) }}" class="btn btn-sm btn-outline-secondary btn-table-action" title="Danh sách học viên">
                                    <i class="fas fa-user-graduate"></i> {{ $item->students_count }}
                                </a>
                            </td>
                            <td class="text-center align-middle">
                                @php
                                    $allPaid = ($item->students_count ?? 0) > 0 && ($item->students_paid_count ?? 0) == ($item->students_count ?? 0);
                                @endphp
                                @if($allPaid)
                                    <span class="badge badge-status badge-success">Hoàn thành</span>
                                @else
                                    <span class="badge badge-status badge-secondary">Chưa hoàn thành</span>
                                @endif
                            </td>
                            <td class="align-middle">
                                <div class="btn-action-wrap">
                                    @if($canTakeAttendance)
                                        <a href="{{ route('admin.centers.classes.attendance.index', [$center, $item]) }}" target="_blank" class="btn btn-sm btn-outline-success btn-action" title="Điểm danh">
                                            <i class="fas fa-clipboard-check"></i>
                                        </a>
                                    @endif
                                    <a href="{{ route('admin.centers.classes.sessions.index', [$center, $item]) }}" class="btn btn-sm btn-outline-primary btn-action" title="Lịch buổi học">
                                        <i class="fas fa-calendar-alt"></i>
                                    </a>
                                    @if(auth()->user()->isAdmin())
                                    <a href="{{ route('admin.centers.classes.edit', [$center, $item]) }}" class="btn btn-sm btn-outline-secondary btn-action" title="Sửa">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.centers.classes.destroy', [$center, $item]) }}" method="POST" class="d-inline" onsubmit="return confirm('Xóa lớp học này?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger btn-action" title="Xóa"><i class="fas fa-trash"></i></button>
                                    </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="text-center text-muted py-5">
                                Chưa có lớp học nào. <a href="{{ route('admin.centers.classes.create', $center) }}">Thêm lớp học</a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($classes->hasPages())
            <div class="card-footer bg-white border-top py-2">
                {{ $classes->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
