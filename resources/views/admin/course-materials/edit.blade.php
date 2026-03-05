@extends('admin.layouts.app')

@section('title', 'Sửa – ' . $material->name)

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Sửa: {{ $material->name }}</h3>
        @php $backUrl = route('admin.courses.materials.index', $course); if ($material->parent_id) { $backUrl .= '?parent=' . $material->parent_id; } @endphp
        <a href="{{ $backUrl }}" class="btn btn-sm btn-default"><i class="fas fa-arrow-left"></i> Quay lại</a>
    </div>
    <form action="{{ route('admin.courses.materials.update', [$course, $material]) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="card-body">
            <div class="form-group">
                <label>Tên <span class="text-danger">*</span></label>
                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $material->name) }}" required>
                @error('name')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group">
                <label>Thứ tự</label>
                <input type="number" name="sort_order" class="form-control" value="{{ old('sort_order', $material->sort_order) }}" min="0">
            </div>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-primary">Cập nhật</button>
            <a href="{{ $backUrl }}" class="btn btn-default">Hủy</a>
        </div>
    </form>
</div>
@endsection
