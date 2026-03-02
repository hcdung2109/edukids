@extends('admin.layouts.app')

@section('title', 'Sửa khóa học')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Sửa khóa học: {{ $course->name }}</h3>
    </div>
    <form action="{{ route('admin.courses.update', $course) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="card-body">
            <div class="form-group">
                <label>Tên khóa học <span class="text-danger">*</span></label>
                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $course->name) }}" required>
                @error('name')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group">
                <label>Mô tả</label>
                <textarea name="description" rows="4" class="form-control @error('description') is-invalid @enderror">{{ old('description', $course->description) }}</textarea>
                @error('description')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Icon (emoji hoặc tên class)</label>
                        <input type="text" name="icon" class="form-control @error('icon') is-invalid @enderror" value="{{ old('icon', $course->icon) }}">
                        @error('icon')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Thứ tự hiển thị</label>
                        <input type="number" name="sort_order" class="form-control @error('sort_order') is-invalid @enderror" value="{{ old('sort_order', $course->sort_order) }}" min="0">
                        @error('sort_order')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label>Ảnh đại diện</label>
                @if($course->image)
                    <div class="mb-2">
                        <img src="{{ asset('storage/' . $course->image) }}" alt="" class="img-thumbnail" style="max-height: 120px;">
                        <p class="text-muted small mb-0">Chọn ảnh mới để thay thế.</p>
                    </div>
                @endif
                <input type="file" name="image" class="form-control-file @error('image') is-invalid @enderror" accept="image/*">
                @error('image')
                    <span class="invalid-feedback d-block">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group">
                <div class="custom-control custom-checkbox">
                    <input type="checkbox" name="is_active" value="1" class="custom-control-input" id="is_active" {{ old('is_active', $course->is_active) ? 'checked' : '' }}>
                    <label class="custom-control-label" for="is_active">Hiển thị trên trang chủ</label>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-primary">Cập nhật</button>
            <a href="{{ route('admin.courses.index') }}" class="btn btn-default">Hủy</a>
        </div>
    </form>
</div>
@endsection
