@extends('admin.layouts.app')

@section('title', 'Quản lý vai trò')

@section('content')
<div class="card shadow-sm">
    <div class="card-header bg-white border-bottom py-3">
        <h3 class="card-title mb-0 font-weight-bold text-dark">Danh sách vai trò</h3>
        <div class="card-tools">
            <a href="{{ route('admin.roles.create') }}" class="btn btn-sm btn-primary"><i class="fas fa-plus"></i> Thêm vai trò</a>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover table-striped mb-0">
                <thead class="thead-light">
                    <tr>
                        <th style="width: 80px">Mã</th>
                        <th>Tên hiển thị</th>
                        <th>Mô tả</th>
                        <th class="text-center" style="width: 100px">Loại</th>
                        <th class="text-center" style="width: 100px">Số tài khoản</th>
                        <th class="text-center" style="width: 120px">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($roles as $role)
                        <tr>
                            <td><code>{{ $role->name }}</code></td>
                            <td class="font-weight-medium">{{ $role->label }}</td>
                            <td class="text-muted small">{{ Str::limit($role->description, 60) ?: '–' }}</td>
                            <td class="text-center">
                                @if($role->is_system)
                                    <span class="badge badge-secondary">Hệ thống</span>
                                @else
                                    <span class="badge badge-light text-dark">Tùy chỉnh</span>
                                @endif
                            </td>
                            <td class="text-center">{{ $role->users_count ?? 0 }}</td>
                            <td class="text-center">
                                <a href="{{ route('admin.roles.edit', $role) }}" class="btn btn-sm btn-outline-primary" title="Sửa"><i class="fas fa-edit"></i></a>
                                @if(!$role->is_system && ($role->users_count ?? 0) === 0)
                                    <form action="{{ route('admin.roles.destroy', $role) }}" method="POST" class="d-inline" onsubmit="return confirm('Xóa vai trò này?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Xóa"><i class="fas fa-trash"></i></button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">Chưa có vai trò nào.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
