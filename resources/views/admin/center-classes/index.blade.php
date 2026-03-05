@extends('admin.layouts.app')

@section('title', 'Danh sách lớp học – ' . $center->name)

@push('styles')
<style>
.table-classes thead th { font-weight: 600; color: #495057; border-bottom-width: 2px; white-space: nowrap; }
.table-classes tbody tr:hover { background-color: #f8f9fa; }
.table-classes .btn-action-wrap { display: inline-flex; align-items: center; gap: 6px; flex-wrap: wrap; }
.table-classes .btn-action-wrap .btn { padding: 0.35rem 0.5rem; min-width: 34px; width: 34px; justify-content: center; }
.table-classes .btn-action-wrap .btn .fa { margin: 0; }
.badge-status { font-size: 0.8rem; padding: 0.35em 0.6em; }
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
                                <a href="{{ route('admin.centers.classes.students.index', [$center, $item]) }}" class="btn btn-sm btn-outline-info" title="Danh sách học viên">
                                    <i class="fas fa-user-graduate"></i> {{ $item->students_count }}
                                </a>
                            </td>
                            <td class="align-middle">
                                <div class="btn-action-wrap">
                                    @if($canTakeAttendance)
                                        <a href="{{ route('admin.centers.classes.attendance.index', [$center, $item]) }}" target="_blank" class="btn btn-sm btn-success btn-action" title="Điểm danh">
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
                            <td colspan="9" class="text-center text-muted py-5">
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
