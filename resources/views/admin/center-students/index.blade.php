@extends('admin.layouts.app')

@section('title', 'Danh sách học viên – ' . $center_class->name)

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Danh sách học viên: {{ $center_class->name }}</h3>
        <p class="text-muted small mb-0">Trung tâm: {{ $center->name }}</p>
        <div class="card-tools">
            @if(auth()->user()->isAdmin())
            <a href="{{ route('admin.centers.classes.students.create', [$center, $center_class]) }}" class="btn btn-sm btn-primary"><i class="fas fa-plus"></i> Thêm học viên</a>
            <a href="{{ route('admin.centers.classes.students.import', [$center, $center_class]) }}" class="btn btn-sm btn-success"><i class="fas fa-file-excel"></i> Import Excel</a>
            @endif
            <a href="{{ route('admin.centers.classes.index', $center) }}" class="btn btn-sm btn-default"><i class="fas fa-arrow-left"></i> Về danh sách lớp</a>
        </div>
    </div>
    <div class="card-body">
        <table class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th style="width: 50px">STT</th>
                    <th>Họ tên</th>
                    <th>Lớp</th>
                    <th>Trường</th>
                    <th>Ngày sinh</th>
                    <th>Điện thoại</th>
                    <th>Phụ huynh</th>
                    <th>SĐT phụ huynh</th>
                    <th>Ghi chú</th>
                    <th style="width: 120px">Thao tác</th>
                </tr>
            </thead>
            <tbody>
                @forelse($students as $item)
                    <tr>
                        <td>{{ $item->sort_order }}</td>
                        <td>{{ $item->name }}</td>
                        <td>{{ $item->class_name ?: '–' }}</td>
                        <td>{{ Str::limit($item->school_name, 30) ?: '–' }}</td>
                        <td>{{ $item->date_of_birth?->format('d/m/Y') ?: '–' }}</td>
                        <td>{{ $item->phone ?: '–' }}</td>
                        <td>{{ $item->parent_name ?: '–' }}</td>
                        <td>{{ $item->parent_phone ?: '–' }}</td>
                        <td>{{ Str::limit($item->note, 40) ?: '–' }}</td>
                        <td>
                            @if(auth()->user()->isAdmin())
                            <a href="{{ route('admin.centers.classes.students.edit', [$center, $center_class, $item]) }}" class="btn btn-sm btn-default"><i class="fas fa-edit"></i></a>
                            <form action="{{ route('admin.centers.classes.students.destroy', [$center, $center_class, $item]) }}" method="POST" class="d-inline" onsubmit="return confirm('Xóa học viên này?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                            </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="10" class="text-center text-muted">Chưa có học viên. <a href="{{ route('admin.centers.classes.students.create', [$center, $center_class]) }}">Thêm học viên</a> hoặc <a href="{{ route('admin.centers.classes.students.import', [$center, $center_class]) }}">Import Excel</a></td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <div class="mt-3">
            {{ $students->links() }}
        </div>
    </div>
</div>
@endsection
