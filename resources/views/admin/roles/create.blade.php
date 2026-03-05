@extends('admin.layouts.app')

@section('title', 'Thêm vai trò')

@section('content')
<div class="card shadow-sm">
    <div class="card-header bg-white border-bottom py-3">
        <h3 class="card-title mb-0 font-weight-bold text-dark">Thêm vai trò mới</h3>
        <a href="{{ route('admin.roles.index') }}" class="btn btn-sm btn-outline-secondary"><i class="fas fa-arrow-left"></i> Danh sách vai trò</a>
    </div>
    <form action="{{ route('admin.roles.store') }}" method="POST">
        @csrf
        <div class="card-body">
            <div class="form-group">
                <label>Mã vai trò <span class="text-danger">*</span></label>
                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" placeholder="vd: staff, moderator" required>
                <small class="form-text text-muted">Chỉ dùng chữ thường, số và dấu gạch dưới. Dùng làm mã trong hệ thống.</small>
                @error('name')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group">
                <label>Tên hiển thị <span class="text-danger">*</span></label>
                <input type="text" name="label" class="form-control @error('label') is-invalid @enderror" value="{{ old('label') }}" placeholder="vd: Nhân viên" required>
                @error('label')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group">
                <label>Mô tả</label>
                <textarea name="description" class="form-control @error('description') is-invalid @enderror" rows="2" placeholder="Mô tả ngắn về vai trò">{{ old('description') }}</textarea>
                @error('description')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>
        </div>
        <div class="card-footer bg-white border-top">
            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Lưu</button>
            <a href="{{ route('admin.roles.index') }}" class="btn btn-outline-secondary">Hủy</a>
        </div>
    </form>
</div>
@endsection
