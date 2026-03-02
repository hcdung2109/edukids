@extends('admin.layouts.app')

@section('title', 'Quản lý Tin tức & Sự kiện')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Danh sách bài viết</h3>
        <div class="card-tools">
            <a href="{{ route('admin.news.create') }}" class="btn btn-sm btn-primary"><i class="fas fa-plus"></i> Thêm bài viết</a>
        </div>
    </div>
    <div class="card-body">
        <form method="GET" class="mb-3 row">
            <div class="col-md-6">
                <input type="text" name="q" class="form-control" placeholder="Tìm theo tiêu đề..." value="{{ request('q') }}">
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i> Tìm</button>
            </div>
        </form>
        <table class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Tiêu đề</th>
                    <th>Trích dẫn</th>
                    <th>Trạng thái</th>
                    <th>Ngày đăng</th>
                    <th style="width: 120px">Thao tác</th>
                </tr>
            </thead>
            <tbody>
                @forelse($news as $item)
                    <tr>
                        <td>{{ $item->id }}</td>
                        <td>{{ Str::limit($item->title, 50) }}</td>
                        <td>{{ Str::limit($item->excerpt, 40) }}</td>
                        <td>
                            @if($item->is_published)
                                <span class="badge badge-success">Đã đăng</span>
                            @else
                                <span class="badge badge-secondary">Nháp</span>
                            @endif
                        </td>
                        <td>{{ $item->published_at?->format('d/m/Y H:i') ?? '–' }}</td>
                        <td>
                            <a href="{{ route('admin.news.edit', $item) }}" class="btn btn-sm btn-default"><i class="fas fa-edit"></i></a>
                            <form action="{{ route('admin.news.destroy', $item) }}" method="POST" class="d-inline" onsubmit="return confirm('Xóa bài viết này?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted">Chưa có bài viết.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <div class="d-flex justify-content-center mt-2">
            {{ $news->links() }}
        </div>
    </div>
</div>
@endsection
