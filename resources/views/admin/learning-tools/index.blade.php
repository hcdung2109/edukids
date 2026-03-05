@extends('admin.layouts.app')

@section('title', 'Quản lý công cụ học')

@section('content')
<div class="card shadow-sm">
    <div class="card-header bg-white border-bottom py-3">
        <h3 class="card-title mb-0 font-weight-bold text-dark">Danh sách công cụ học</h3>
        <div class="card-tools">
            @if(auth()->user()->isAdmin())
            <a href="{{ route('admin.learning-tools.create') }}" class="btn btn-sm btn-primary"><i class="fas fa-plus"></i> Thêm công cụ</a>
            @endif
        </div>
    </div>
    <div class="card-body pt-3 pb-0">
        <form method="GET" class="form-inline flex-wrap mb-3">
            <input type="text" name="q" class="form-control form-control-sm mr-2 mb-1" placeholder="Tìm theo tên..." value="{{ request('q') }}" style="min-width: 160px;">
            <select name="center_id" class="form-control form-control-sm mr-2 mb-1" style="min-width: 180px;">
                <option value="">Tất cả trung tâm</option>
                @foreach($centers as $c)
                    <option value="{{ $c->id }}" {{ request('center_id') == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                @endforeach
            </select>
            <button type="submit" class="btn btn-sm btn-primary mb-1"><i class="fas fa-search"></i> Lọc</button>
        </form>
    </div>
    <div class="card-body p-0 pt-0">
        <div class="table-responsive">
            <table class="table table-hover table-striped mb-0">
                <thead class="thead-light">
                    <tr>
                        <th>Tên công cụ</th>
                        <th class="text-center" style="width: 90px">Số lượng</th>
                        <th>Trung tâm</th>
                        <th>Lớp</th>
                        <th>Người quản lý</th>
                        <th>Ghi chú</th>
                        @if(auth()->user()->isAdmin())
                        <th class="text-center" style="width: 100px">Thao tác</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @forelse($tools as $item)
                        <tr>
                            <td class="align-middle font-weight-medium">{{ $item->name }}</td>
                            <td class="text-center align-middle">{{ $item->quantity }}</td>
                            <td class="align-middle">{{ $item->center->name ?? '–' }}</td>
                            <td class="align-middle">{{ $item->centerClass?->name ?? '–' }}</td>
                            <td class="align-middle">{{ $item->managedBy?->name ?? '–' }}</td>
                            <td class="align-middle text-muted small">{{ Str::limit($item->note, 40) ?: '–' }}</td>
                            @if(auth()->user()->isAdmin())
                            <td class="text-center align-middle">
                                <div class="d-inline-flex align-items-center">
                                    <a href="{{ route('admin.learning-tools.edit', $item) }}" class="btn btn-sm btn-outline-primary mr-1" title="Sửa"><i class="fas fa-edit"></i></a>
                                    <form action="{{ route('admin.learning-tools.destroy', $item) }}" method="POST" class="d-inline m-0" onsubmit="return confirm('Xóa công cụ này?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Xóa"><i class="fas fa-trash"></i></button>
                                    </form>
                                </div>
                            </td>
                            @endif
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ auth()->user()->isAdmin() ? 7 : 6 }}" class="text-center text-muted py-4">Chưa có công cụ học nào.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($tools->hasPages())
            <div class="card-footer bg-white border-top py-2">
                {{ $tools->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
