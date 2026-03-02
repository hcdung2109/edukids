@extends('admin.layouts.app')

@section('title', 'Import học viên – ' . $center_class->name)

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Import danh sách học viên từ Excel</h3>
        <a href="{{ route('admin.centers.classes.students.index', [$center, $center_class]) }}" class="btn btn-sm btn-default"><i class="fas fa-arrow-left"></i> Danh sách học viên</a>
    </div>
    <div class="card-body">
        <p class="text-muted">Lớp: <strong>{{ $center_class->name }}</strong></p>
        <div class="alert alert-info">
            <strong>Định dạng file Excel:</strong>
            <ul class="mb-0 mt-2">
                <li>Dòng đầu tiên là tiêu đề cột.</li>
                <li>Bắt buộc có cột <strong>Họ tên</strong> (hoặc "Họ và tên", "Tên").</li>
                <li>Các cột tùy chọn: <strong>Email</strong>, <strong>Điện thoại</strong>, <strong>Ngày sinh</strong>, <strong>Phụ huynh</strong>, <strong>SĐT phụ huynh</strong>, <strong>Ghi chú</strong>.</li>
                <li>File .xlsx hoặc .xls, tối đa 5MB.</li>
            </ul>
            <p class="mb-0 mt-2">
                <a href="{{ route('admin.centers.classes.students.import.template', [$center, $center_class]) }}" class="btn btn-sm btn-outline-primary"><i class="fas fa-file-download"></i> Tải file Excel mẫu</a>
            </p>
        </div>
        <form action="{{ route('admin.centers.classes.students.import.store', [$center, $center_class]) }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label>Chọn file Excel <span class="text-danger">*</span></label>
                <input type="file" name="file" class="form-control-file @error('file') is-invalid @enderror" accept=".xlsx,.xls" required>
                @error('file')
                    <span class="invalid-feedback d-block">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-success"><i class="fas fa-file-upload"></i> Import</button>
                <a href="{{ route('admin.centers.classes.students.index', [$center, $center_class]) }}" class="btn btn-default">Hủy</a>
            </div>
        </form>
    </div>
</div>
@endsection
