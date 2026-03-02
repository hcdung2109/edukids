@extends('admin.layouts.app')

@section('title', 'Quản lý site')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Thông tin liên hệ & cài đặt site</h3>
        <p class="text-muted small mb-0 mt-1">Các thông tin này hiển thị tại trang chủ (header, footer, liên hệ).</p>
    </div>
    <form action="{{ route('admin.site.update') }}" method="POST">
        @csrf
        @method('PUT')
        <div class="card-body">
            <div class="form-group">
                <label>Tên site <span class="text-danger">*</span></label>
                <input type="text" name="site_name" class="form-control @error('site_name') is-invalid @enderror" value="{{ old('site_name', $site->site_name) }}" placeholder="VD: EduKids" required>
                @error('site_name')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group">
                <label>Mô tả footer</label>
                <textarea name="footer_description" rows="2" class="form-control @error('footer_description') is-invalid @enderror" placeholder="Đoạn mô tả ngắn hiển thị trong footer">{{ old('footer_description', $site->footer_description) }}</textarea>
                @error('footer_description')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>
            <hr class="my-4">
            <h5 class="mb-3">Liên hệ</h5>
            <div class="form-group">
                <label>Địa chỉ</label>
                <input type="text" name="address" class="form-control @error('address') is-invalid @enderror" value="{{ old('address', $site->address) }}" placeholder="Địa chỉ trung tâm / văn phòng">
                @error('address')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $site->email) }}" placeholder="lienhe@edukids.vn">
                        @error('email')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Số điện thoại</label>
                        <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone', $site->phone) }}" placeholder="VD: 0123 456 789">
                        @error('phone')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label>Hotline (hiển thị công khai)</label>
                <input type="text" name="hotline" class="form-control @error('hotline') is-invalid @enderror" value="{{ old('hotline', $site->hotline) }}" placeholder="VD: 1900 xxxx">
                @error('hotline')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group">
                <label>Link Facebook</label>
                <input type="url" name="facebook_url" class="form-control @error('facebook_url') is-invalid @enderror" value="{{ old('facebook_url', $site->facebook_url) }}" placeholder="https://www.facebook.com/...">
                @error('facebook_url')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
            <a href="{{ route('admin.dashboard') }}" class="btn btn-default">Hủy</a>
        </div>
    </form>
</div>
@endsection
