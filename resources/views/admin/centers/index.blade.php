@extends('admin.layouts.app')

@section('title', 'Quản lý trung tâm')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Danh sách trung tâm</h3>
        <div class="card-tools">
            <a href="{{ route('admin.centers.create') }}" class="btn btn-sm btn-primary"><i class="fas fa-plus"></i> Thêm trung tâm</a>
        </div>
    </div>
    <div class="card-body">
        <form method="GET" class="mb-3 row">
            <div class="col-md-6">
                <input type="text" name="q" class="form-control" placeholder="Tìm theo tên hoặc địa chỉ..." value="{{ request('q') }}">
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i> Tìm</button>
            </div>
        </form>
        <table class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th style="width: 50px">STT</th>
                    <th>Tên trung tâm</th>
                    <th>Địa chỉ</th>
                    <th>Điện thoại</th>
                    <th>Ảnh</th>
                    <th>Trạng thái</th>
                    <th style="width: 100px">DS Lớp học</th>
                    <th style="width: 120px">Thao tác</th>
                </tr>
            </thead>
            <tbody>
                @forelse($centers as $item)
                    <tr>
                        <td>{{ $item->sort_order }}</td>
                        <td>{{ $item->name }}</td>
                        <td>{{ Str::limit($item->address, 40) ?: '–' }}</td>
                        <td>{{ $item->phone ?: '–' }}</td>
                        <td>
                            @if($item->image)
                                <img src="{{ asset('storage/' . $item->image) }}" alt="" class="img-thumbnail" style="max-height: 40px;">
                            @else
                                –
                            @endif
                        </td>
                        <td>
                            @if($item->is_active)
                                <span class="badge badge-success">Hiển thị</span>
                            @else
                                <span class="badge badge-secondary">Ẩn</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('admin.centers.classes.index', $item) }}" class="btn btn-sm btn-info" title="Danh sách lớp học"><i class="fas fa-list mr-1"></i> DS Lớp học ({{ $item->classes_count }})</a>
                        </td>
                        <td>
                            <a href="{{ route('admin.centers.edit', $item) }}" class="btn btn-sm btn-default"><i class="fas fa-edit"></i></a>
                            <form action="{{ route('admin.centers.destroy', $item) }}" method="POST" class="d-inline" onsubmit="return confirm('Xóa trung tâm này?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted">Chưa có trung tâm nào.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <div class="mt-3">
            {{ $centers->links() }}
        </div>
    </div>
</div>
@endsection
