@extends('admin.layouts.app')

@section('title', 'Sửa bài viết')

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.css" rel="stylesheet">
@endpush

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Sửa bài viết</h3>
    </div>
    <form action="{{ route('admin.news.update', $news) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="card-body">
            <div class="form-group">
                <label>Tiêu đề <span class="text-danger">*</span></label>
                <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title', $news->title) }}" required>
                @error('title')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group">
                <label>Ảnh đại diện (trang chủ)</label>
                @if($news->image)
                    <div class="mb-2">
                        <img src="{{ asset('storage/' . $news->image) }}" alt="" class="img-thumbnail" style="max-height: 120px;">
                        <small class="d-block text-muted">Ảnh hiện tại. Chọn file mới để thay thế.</small>
                    </div>
                @endif
                <input type="file" name="image" class="form-control-file @error('image') is-invalid @enderror" accept="image/*">
                <small class="text-muted">JPG, PNG, GIF, WebP. Tối đa 2MB.</small>
                @error('image')
                    <span class="invalid-feedback d-block">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group">
                <label>Trích dẫn</label>
                <textarea name="excerpt" rows="2" class="form-control @error('excerpt') is-invalid @enderror">{{ old('excerpt', $news->excerpt) }}</textarea>
                @error('excerpt')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group">
                <label>Nội dung</label>
                <textarea name="body" id="news-body" class="form-control @error('body') is-invalid @enderror" rows="10">{{ old('body', $news->body) }}</textarea>
                @error('body')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group">
                <label>Thư viện ảnh (thêm ảnh mới)</label>
                @if($news->images->isNotEmpty())
                    <div class="mb-3">
                        <p class="mb-1">Ảnh hiện có:</p>
                        <div class="d-flex flex-wrap gap-2">
                            @foreach($news->images as $img)
                                <div class="position-relative d-inline-block">
                                    <img src="{{ asset('storage/' . $img->path) }}" alt="" class="img-thumbnail" style="height: 80px; width: auto;">
                                    <form action="{{ route('admin.news.images.destroy', [$news, $img->id]) }}" method="POST" class="d-inline position-absolute" style="top: 0; right: 0;" onsubmit="return confirm('Xóa ảnh này?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm p-1"><i class="fas fa-times"></i></button>
                                    </form>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
                <input type="file" name="images[]" class="form-control-file" accept="image/*" multiple>
                <small class="text-muted">Chọn thêm nhiều ảnh. Mỗi ảnh tối đa 2MB.</small>
                @error('images.*')
                    <span class="invalid-feedback d-block">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group">
                <div class="custom-control custom-checkbox">
                    <input type="checkbox" name="is_published" value="1" class="custom-control-input" id="is_published" {{ old('is_published', $news->is_published) ? 'checked' : '' }}>
                    <label class="custom-control-label" for="is_published">Đăng bài</label>
                </div>
            </div>
            <div class="form-group">
                <label>Ngày đăng</label>
                <input type="datetime-local" name="published_at" class="form-control @error('published_at') is-invalid @enderror" value="{{ old('published_at', $news->published_at?->format('Y-m-d\TH:i')) }}">
                @error('published_at')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-primary">Cập nhật</button>
            <a href="{{ route('admin.news.index') }}" class="btn btn-default">Hủy</a>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.js"></script>
<script>
$(function () {
    $('#news-body').summernote({
        height: 300,
        toolbar: [
            ['style', ['style']],
            ['font', ['bold', 'italic', 'underline', 'clear']],
            ['fontname', ['fontname']],
            ['color', ['color']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['table', ['table']],
            ['insert', ['link', 'picture', 'video']],
            ['view', ['fullscreen', 'codeview', 'help']]
        ],
        placeholder: 'Nhập nội dung bài viết...'
    });
});
</script>
@endpush
