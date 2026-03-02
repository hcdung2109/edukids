@extends('admin.layouts.app')

@section('title', 'Danh sách lớp học – ' . $center->name)

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Danh sách lớp học: {{ $center->name }}</h3>
        <div class="card-tools">
            <a href="{{ route('admin.centers.classes.create', $center) }}" class="btn btn-sm btn-primary"><i class="fas fa-plus"></i> Thêm lớp học</a>
            <a href="{{ route('admin.centers.index') }}" class="btn btn-sm btn-default"><i class="fas fa-arrow-left"></i> Về danh sách trung tâm</a>
        </div>
    </div>
    <div class="card-body">
        <table class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th style="width: 50px">STT</th>
                    <th>Tên lớp học</th>
                    <th>Lịch học</th>
                    <th>Mô tả</th>
                    <th>Trạng thái</th>
                    <th style="width: 110px">DS Học viên</th>
                    <th style="width: 120px">Thao tác</th>
                </tr>
            </thead>
            <tbody>
                @forelse($classes as $item)
                    <tr>
                        <td>{{ $item->sort_order }}</td>
                        <td>{{ $item->name }}</td>
                        <td>{{ $item->schedule ?: '–' }}</td>
                        <td>{{ Str::limit($item->description, 50) ?: '–' }}</td>
                        <td>
                            @if($item->is_active)
                                <span class="badge badge-success">Hoạt động</span>
                            @else
                                <span class="badge badge-secondary">Ẩn</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('admin.centers.classes.students.index', [$center, $item]) }}" class="btn btn-sm btn-info" title="Danh sách học viên"><i class="fas fa-user-graduate mr-1"></i> DS HV ({{ $item->students_count }})</a>
                        </td>
                        <td>
                            <a href="{{ route('admin.centers.classes.edit', [$center, $item]) }}" class="btn btn-sm btn-default"><i class="fas fa-edit"></i></a>
                            <form action="{{ route('admin.centers.classes.destroy', [$center, $item]) }}" method="POST" class="d-inline" onsubmit="return confirm('Xóa lớp học này?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted">Chưa có lớp học nào. <a href="{{ route('admin.centers.classes.create', $center) }}">Thêm lớp học</a></td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <div class="mt-3">
            {{ $classes->links() }}
        </div>
    </div>
</div>
@endsection
