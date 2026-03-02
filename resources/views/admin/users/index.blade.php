@extends('admin.layouts.app')

@section('title', 'Quản lý tài khoản')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Danh sách tài khoản</h3>
        <div class="card-tools">
            <a href="{{ route('admin.users.create') }}" class="btn btn-sm btn-primary"><i class="fas fa-plus"></i> Thêm tài khoản</a>
        </div>
    </div>
    <div class="card-body">
        <form method="GET" class="mb-3 row">
            <div class="col-md-4">
                <input type="text" name="q" class="form-control" placeholder="Tìm theo tên, email..." value="{{ request('q') }}">
            </div>
            <div class="col-md-3">
                <select name="role" class="form-control">
                    <option value="">Tất cả vai trò</option>
                    <option value="admin" {{ request('role') === 'admin' ? 'selected' : '' }}>Admin</option>
                    <option value="teacher" {{ request('role') === 'teacher' ? 'selected' : '' }}>Teacher</option>
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i> Lọc</button>
            </div>
        </form>
        <table class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Họ tên</th>
                    <th>Email</th>
                    <th>Vai trò</th>
                    <th>Ngày tạo</th>
                    <th style="width: 140px">Thao tác</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                    <tr>
                        <td>{{ $user->id }}</td>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>
                            @if($user->role === 'admin')
                                <span class="badge badge-danger">Admin</span>
                            @else
                                <span class="badge badge-info">Teacher</span>
                            @endif
                        </td>
                        <td>{{ $user->created_at->format('d/m/Y H:i') }}</td>
                        <td>
                            <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-sm btn-default"><i class="fas fa-edit"></i></a>
                            @if($user->id !== auth()->id())
                                <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="d-inline" onsubmit="return confirm('Bạn có chắc muốn xóa tài khoản này?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted">Chưa có tài khoản nào.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <div class="d-flex justify-content-center mt-2">
            {{ $users->links() }}
        </div>
    </div>
</div>
@endsection
