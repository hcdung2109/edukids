@extends('admin.layouts.app')

@section('title', 'Thêm tài liệu – ' . $course->name)

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Tạo thư mục hoặc thêm file</h3>
        @php $backUrl = route('admin.courses.materials.index', $course); if ($parent) { $backUrl .= '?parent=' . $parent->id; } @endphp
        <a href="{{ $backUrl }}" class="btn btn-sm btn-default"><i class="fas fa-arrow-left"></i> Quay lại</a>
    </div>
    <form action="{{ route('admin.courses.materials.store', $course) }}" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="parent_id" value="{{ $parent?->id }}">
        <div class="card-body">
            <div class="form-group">
                <label>Loại <span class="text-danger">*</span></label>
                <div>
                    <div class="custom-control custom-radio custom-control-inline">
                        <input type="radio" id="type_folder" name="type" value="folder" class="custom-control-input" {{ old('type', 'folder') === 'folder' ? 'checked' : '' }}>
                        <label class="custom-control-label" for="type_folder">Thư mục</label>
                    </div>
                    <div class="custom-control custom-radio custom-control-inline">
                        <input type="radio" id="type_file" name="type" value="file" class="custom-control-input" {{ old('type') === 'file' ? 'checked' : '' }}>
                        <label class="custom-control-label" for="type_file">File</label>
                    </div>
                </div>
            </div>
            <div id="folder-fields" class="form-group">
                <label>Tên thư mục <span class="text-danger">*</span></label>
                <input type="text" id="input-name" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" placeholder="Tên thư mục">
                @error('name')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>
            <div id="file-fields" class="form-group" style="display: none;">
                <label>Chọn file <span class="text-danger">*</span></label>
                <input type="file" name="file" id="input-file" class="form-control-file @error('file') is-invalid @enderror" accept=".pdf,.doc,.docx,.ppt,.pptx,.xls,.xlsx,.txt,.jpg,.jpeg,.png">
                <small class="text-muted">PDF, Word, PowerPoint, Excel, ảnh, txt. Tối đa 50MB.</small>
                @error('file')
                    <span class="invalid-feedback d-block">{{ $message }}</span>
                @enderror
                <div class="mt-2">
                    <label>Tên hiển thị (tùy chọn)</label>
                    <input type="text" name="display_name" class="form-control" value="{{ old('display_name') }}" placeholder="Để trống sẽ dùng tên file">
                </div>
            </div>
            <div class="form-group">
                <label>Thứ tự</label>
                <input type="number" name="sort_order" class="form-control" value="{{ old('sort_order', 0) }}" min="0">
            </div>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-primary">Lưu</button>
            <a href="{{ $backUrl }}" class="btn btn-default">Hủy</a>
        </div>
    </form>
</div>
<script>
document.querySelectorAll('input[name="type"]').forEach(function(r) {
    r.addEventListener('change', function() {
        var isFile = document.getElementById('type_file').checked;
        document.getElementById('folder-fields').style.display = isFile ? 'none' : 'block';
        document.getElementById('file-fields').style.display = isFile ? 'block' : 'none';
        document.getElementById('input-name').required = !isFile;
        document.getElementById('input-file').required = isFile;
    });
});
if (document.getElementById('type_file').checked) document.querySelector('input[name="type"]').dispatchEvent(new Event('change'));
</script>
@endsection
