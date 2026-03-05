@extends('admin.layouts.app')

@section('title', 'Quản lý khóa học')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Danh sách khóa học</h3>
        <div class="card-tools">
            @if(auth()->user()->isAdmin())
            <a href="{{ route('admin.courses.create') }}" class="btn btn-sm btn-primary"><i class="fas fa-plus"></i> Thêm khóa học</a>
            @endif
        </div>
    </div>
    <div class="card-body">
        <form method="GET" class="mb-3 row">
            <div class="col-md-6">
                <input type="text" name="q" class="form-control" placeholder="Tìm theo tên khóa học..." value="{{ request('q') }}">
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i> Tìm</button>
            </div>
        </form>
        <table class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th style="width: 50px">STT</th>
                    <th>Tên khóa học</th>
                    <th>Mô tả</th>
                    <th>Icon</th>
                    <th>Ảnh</th>
                    <th>Trạng thái</th>
                    <th>Tài liệu</th>
                    <th style="width: 120px">Thao tác</th>
                </tr>
            </thead>
            <tbody>
                @forelse($courses as $item)
                    <tr>
                        <td>{{ $item->sort_order }}</td>
                        <td>{{ $item->name }}</td>
                        <td>{{ Str::limit($item->description, 50) }}</td>
                        <td>@if($item->icon)<span class="badge badge-secondary">{{ $item->icon }}</span>@else – @endif</td>
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
                            <a href="{{ route('admin.courses.materials.index', $item) }}" class="btn btn-sm btn-info"><i class="fas fa-folder-open"></i> Tài liệu ({{ $item->all_materials_count ?? 0 }})</a>
                        </td>
                        <td>
                            @if(auth()->user()->isAdmin())
                            <a href="{{ route('admin.courses.edit', $item) }}" class="btn btn-sm btn-default"><i class="fas fa-edit"></i></a>
                            <form action="{{ route('admin.courses.destroy', $item) }}" method="POST" class="d-inline" onsubmit="return confirm('Xóa khóa học này?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                            </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted">Chưa có khóa học nào.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <div class="mt-3">
            {{ $courses->links() }}
        </div>
    </div>
</div>
@endsection
