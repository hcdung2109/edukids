@extends('admin.layouts.app')

@section('title', 'Quản lý trung tâm')

@push('styles')
<style>
.table-centers thead th { font-weight: 600; color: #495057; border-bottom-width: 2px; white-space: nowrap; }
.table-centers tbody tr:hover { background-color: #f8f9fa; }
.table-centers .btn-action-wrap { display: inline-flex; align-items: center; gap: 6px; flex-wrap: wrap; }
.table-centers .btn-action-wrap .btn { padding: 0.35rem 0.5rem; min-width: 34px; width: 34px; justify-content: center; }
.table-centers .btn-action-wrap .btn .fa { margin: 0; }
.badge-status { font-size: 0.8rem; padding: 0.35em 0.6em; }
</style>
@endpush

@section('content')
<div class="card shadow-sm">
    <div class="card-header bg-white border-bottom py-3">
        <h3 class="card-title mb-0 font-weight-bold text-dark">Danh sách trung tâm</h3>
        <div class="card-tools">
            @if(auth()->user()->isAdmin())
            <a href="{{ route('admin.centers.create') }}" class="btn btn-sm btn-primary"><i class="fas fa-plus"></i> Thêm trung tâm</a>
            @endif
        </div>
    </div>
    <div class="card-body pt-3 pb-0">
        <form method="GET" class="form-inline mb-3">
            <label class="sr-only" for="search-center">Tìm kiếm</label>
            <input type="text" name="q" id="search-center" class="form-control form-control-sm mr-2" style="min-width: 220px;" placeholder="Tìm theo tên hoặc địa chỉ..." value="{{ request('q') }}">
            <button type="submit" class="btn btn-sm btn-primary"><i class="fas fa-search"></i> Tìm</button>
        </form>
    </div>
    <div class="card-body p-0 pt-0">
        <div class="table-responsive">
            <table class="table table-centers table-hover table-striped mb-0">
                <thead class="thead-light">
                    <tr>
                        <th style="width: 50px" class="text-center">STT</th>
                        <th>Tên trung tâm</th>
                        <th>Điện thoại</th>
                        <th class="text-center" style="width: 80px">Ảnh</th>
                        <th class="text-center" style="width: 100px">Trạng thái</th>
                        <th class="text-center" style="width: 100px">Lớp học</th>
                        <th class="text-center" style="width: 120px">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($centers as $item)
                        <tr>
                            <td class="text-center align-middle">{{ $item->sort_order }}</td>
                            <td class="align-middle">
                                <span class="font-weight-medium text-dark">{{ $item->name }}</span>
                            </td>
                            <td class="align-middle text-muted">{{ $item->phone ?: '–' }}</td>
                            <td class="text-center align-middle">
                                @if($item->image)
                                    <img src="{{ asset('storage/' . $item->image) }}" alt="" class="img-thumbnail" style="max-height: 36px;">
                                @else
                                    <span class="text-muted">–</span>
                                @endif
                            </td>
                            <td class="text-center align-middle">
                                @if($item->is_active)
                                    <span class="badge badge-status badge-success">Hiển thị</span>
                                @else
                                    <span class="badge badge-status badge-secondary">Ẩn</span>
                                @endif
                            </td>
                            <td class="text-center align-middle">
                                <a href="{{ route('admin.centers.classes.index', $item) }}" class="btn btn-sm btn-outline-info" title="Danh sách lớp học">
                                    <i class="fas fa-list"></i> {{ $item->classes_count }}
                                </a>
                            </td>
                            <td class="align-middle">
                                <div class="btn-action-wrap">
                                    @if(auth()->user()->isAdmin())
                                    <a href="{{ route('admin.centers.edit', $item) }}" class="btn btn-sm btn-outline-primary btn-action" title="Sửa">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.centers.destroy', $item) }}" method="POST" class="d-inline" onsubmit="return confirm('Xóa trung tâm này?');">
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
                            <td colspan="7" class="text-center text-muted py-5">
                                Chưa có trung tâm nào. <a href="{{ route('admin.centers.create') }}">Thêm trung tâm</a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($centers->hasPages())
            <div class="card-footer bg-white border-top py-2">
                {{ $centers->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
