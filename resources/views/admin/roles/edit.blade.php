@extends('admin.layouts.app')

@section('title', 'Sửa vai trò')

@section('content')
<div class="card shadow-sm">
    <div class="card-header bg-white border-bottom py-3">
        <h3 class="card-title mb-0 font-weight-bold text-dark">Sửa vai trò: {{ $role->label }}</h3>
        <a href="{{ route('admin.roles.index') }}" class="btn btn-sm btn-outline-secondary"><i class="fas fa-arrow-left"></i> Danh sách vai trò</a>
    </div>
    <form action="{{ route('admin.roles.update', $role) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="card-body">
            <div class="form-group">
                <label>Mã vai trò</label>
                @if($role->is_system)
                    <input type="text" class="form-control" value="{{ $role->name }}" readonly disabled>
                    <small class="form-text text-muted">Vai trò hệ thống không đổi được mã.</small>
                @else
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $role->name) }}" required>
                    <small class="form-text text-muted">Chỉ dùng chữ thường, số và dấu gạch dưới.</small>
                    @error('name')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                @endif
            </div>
            <div class="form-group">
                <label>Tên hiển thị <span class="text-danger">*</span></label>
                <input type="text" name="label" class="form-control @error('label') is-invalid @enderror" value="{{ old('label', $role->label) }}" required>
                @error('label')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group">
                <label>Mô tả</label>
                <textarea name="description" class="form-control @error('description') is-invalid @enderror" rows="2">{{ old('description', $role->description) }}</textarea>
                @error('description')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>
            @if(!$role->is_system)
                <p class="text-muted small mb-0">
                    <a href="{{ route('admin.permissions.index', ['role' => $role->name]) }}"><i class="fas fa-key"></i> Cấu hình quyền cho vai trò này</a>
                </p>
            @endif
        </div>
        <div class="card-footer bg-white border-top">
            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Cập nhật</button>
            <a href="{{ route('admin.roles.index') }}" class="btn btn-outline-secondary">Hủy</a>
        </div>
    </form>
</div>
@endsection
