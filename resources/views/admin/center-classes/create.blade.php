@extends('admin.layouts.app')

@section('title', 'Thêm lớp học – ' . $center->name)

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet">
@endpush

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Thêm lớp học: {{ $center->name }}</h3>
        <a href="{{ route('admin.centers.classes.index', $center) }}" class="btn btn-sm btn-default"><i class="fas fa-arrow-left"></i> Danh sách lớp</a>
    </div>
    <form action="{{ route('admin.centers.classes.store', $center) }}" method="POST">
        @csrf
        <div class="card-body">
            <div class="form-group">
                <label>Tên lớp học <span class="text-danger">*</span></label>
                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required>
                @error('name')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group">
                <label>Khóa học</label>
                <select name="course_id" id="course_id" class="form-control @error('course_id') is-invalid @enderror">
                    <option value="">-- Không chọn --</option>
                    @foreach($courses as $c)
                        <option value="{{ $c->id }}" {{ old('course_id') == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                    @endforeach
                </select>
                @error('course_id')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
                <a href="#" id="linkViewMaterials" class="btn btn-sm btn-outline-secondary mt-1" target="_blank" style="display: none;"><i class="fas fa-folder-open"></i> Xem tài liệu khóa học</a>
            </div>
            <div class="form-group">
                <label>Giáo viên</label>
                <select name="teacher_id" id="teacher_id" class="form-control @error('teacher_id') is-invalid @enderror">
                    <option value="">-- Không chọn --</option>
                    @foreach($teachers as $t)
                        <option value="{{ $t->id }}" {{ old('teacher_id') == $t->id ? 'selected' : '' }}>{{ $t->name }}</option>
                    @endforeach
                </select>
                <small class="form-text text-muted">Chọn một giáo viên cho lớp.</small>
                @error('teacher_id')
                    <span class="invalid-feedback d-block">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group">
                <label>Lịch học</label>
                <input type="text" name="schedule" class="form-control @error('schedule') is-invalid @enderror" value="{{ old('schedule') }}" placeholder="VD: Thứ 2, 4, 6 – 18h00">
                @error('schedule')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group">
                <label>Số giờ mỗi buổi học <span class="text-danger">*</span></label>
                <input
                    type="number"
                    name="hours_per_session"
                    class="form-control @error('hours_per_session') is-invalid @enderror"
                    value="{{ old('hours_per_session', 2) }}"
                    min="0.25"
                    max="24"
                    step="0.25"
                    required
                >
                <small class="form-text text-muted">Dùng để hiển thị mặc định khi đánh dấu buổi học.</small>
                @error('hours_per_session')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group">
                <label>Trạng thái lớp học</label>
                <select name="status" class="form-control @error('status') is-invalid @enderror">
                    @foreach(\App\Models\CenterClass::statusOptions() as $value => $label)
                        <option value="{{ $value }}" {{ old('status', \App\Models\CenterClass::STATUS_NOT_STARTED) == $value ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
                @error('status')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group">
                <label>Mô tả</label>
                <textarea name="description" rows="3" class="form-control @error('description') is-invalid @enderror">{{ old('description') }}</textarea>
                @error('description')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group">
                <label>Thứ tự hiển thị</label>
                <input type="number" name="sort_order" class="form-control @error('sort_order') is-invalid @enderror" value="{{ old('sort_order', 0) }}" min="0">
                @error('sort_order')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group">
                <div class="custom-control custom-checkbox">
                    <input type="checkbox" name="is_active" value="1" class="custom-control-input" id="is_active" {{ old('is_active', true) ? 'checked' : '' }}>
                    <label class="custom-control-label" for="is_active">Hoạt động</label>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-primary">Lưu</button>
            <a href="{{ route('admin.centers.classes.index', $center) }}" class="btn btn-default">Hủy</a>
        </div>
    </form>
</div>
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.full.min.js"></script>
<script>
$(function() {
    $('#teacher_id').select2({
        placeholder: 'Chọn một giáo viên',
        allowClear: true,
        width: '100%'
    });
    var select = document.getElementById('course_id');
    var link = document.getElementById('linkViewMaterials');
    var urlTpl = "{{ route('admin.courses.materials.index', ['course' => '__ID__']) }}";
    if (select && link) {
        select.addEventListener('change', function() {
            var id = this.value;
            if (id) {
                link.href = urlTpl.replace('__ID__', id);
                link.style.display = 'inline-block';
            } else {
                link.style.display = 'none';
            }
        });
        if (select.value) {
            link.href = urlTpl.replace('__ID__', select.value);
            link.style.display = 'inline-block';
        }
    }
});
</script>
@endpush
@endsection
