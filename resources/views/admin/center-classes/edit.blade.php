@extends('admin.layouts.app')

@section('title', 'Sửa lớp học – ' . $center->name)

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Sửa lớp học: {{ $center_class->name }}</h3>
        <a href="{{ route('admin.centers.classes.index', $center) }}" class="btn btn-sm btn-default"><i class="fas fa-arrow-left"></i> Danh sách lớp</a>
    </div>
    <form action="{{ route('admin.centers.classes.update', [$center, $center_class]) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="card-body">
            <div class="form-group">
                <label>Tên lớp học <span class="text-danger">*</span></label>
                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $center_class->name) }}" required>
                @error('name')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group">
                <label>Lịch học</label>
                <input type="text" name="schedule" class="form-control @error('schedule') is-invalid @enderror" value="{{ old('schedule', $center_class->schedule) }}">
                @error('schedule')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group">
                <label>Mô tả</label>
                <textarea name="description" rows="3" class="form-control @error('description') is-invalid @enderror">{{ old('description', $center_class->description) }}</textarea>
                @error('description')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group">
                <label>Thứ tự hiển thị</label>
                <input type="number" name="sort_order" class="form-control @error('sort_order') is-invalid @enderror" value="{{ old('sort_order', $center_class->sort_order) }}" min="0">
                @error('sort_order')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group">
                <div class="custom-control custom-checkbox">
                    <input type="checkbox" name="is_active" value="1" class="custom-control-input" id="is_active" {{ old('is_active', $center_class->is_active) ? 'checked' : '' }}>
                    <label class="custom-control-label" for="is_active">Hoạt động</label>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-primary">Cập nhật</button>
            <a href="{{ route('admin.centers.classes.index', $center) }}" class="btn btn-default">Hủy</a>
        </div>
    </form>
</div>
@endsection
