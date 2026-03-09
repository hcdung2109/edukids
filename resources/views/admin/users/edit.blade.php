@extends('admin.layouts.app')

@section('title', 'Sửa tài khoản')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Sửa tài khoản: {{ $user->email }}</h3>
    </div>
    <form action="{{ route('admin.users.update', $user) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="card-body">
            <div class="form-group">
                <label>Họ tên <span class="text-danger">*</span></label>
                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $user->name) }}" required>
                @error('name')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group">
                <label>Email <span class="text-danger">*</span></label>
                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $user->email) }}" required>
                @error('email')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group">
                <label>Mật khẩu mới (để trống nếu không đổi)</label>
                <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" autocomplete="new-password">
                @error('password')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group">
                <label>Xác nhận mật khẩu mới</label>
                <input type="password" name="password_confirmation" class="form-control">
            </div>
            <div class="form-group">
                <label>Vai trò <span class="text-danger">*</span></label>
                <select name="role" class="form-control @error('role') is-invalid @enderror" required>
                    @foreach($roles as $r)
                        <option value="{{ $r->name }}" {{ old('role', $user->role) === $r->name ? 'selected' : '' }}>{{ $r->label }}</option>
                    @endforeach
                </select>
                @error('role')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group">
                <label>Lương/giờ</label>
                @php
                    $salaryValue = old('salary_per_hour', $user->salary_per_hour);
                    $salaryDisplay = $salaryValue !== null && $salaryValue !== ''
                        ? number_format((float) $salaryValue, 0, '', '.')
                        : '';
                @endphp
                <input type="hidden" name="salary_per_hour" id="salary_per_hour" value="{{ old('salary_per_hour', $user->salary_per_hour) }}">
                <input
                    type="text"
                    inputmode="numeric"
                    id="salary_per_hour_display"
                    class="form-control @error('salary_per_hour') is-invalid @enderror"
                    value="{{ $salaryDisplay }}"
                    placeholder="VD: 100.000"
                    autocomplete="off"
                >
                <small class="form-text text-muted">Tự động thêm dấu phân tách hàng nghìn (VD: 100.000). Có thể để trống.</small>
                @error('salary_per_hour')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-primary">Cập nhật</button>
            <a href="{{ route('admin.users.index') }}" class="btn btn-default">Hủy</a>
        </div>
    </form>
</div>

@push('scripts')
<script>
(function() {
    function formatThousands(raw) {
        if (!raw) return '';
        var digits = String(raw).replace(/[^\d]/g, '');
        if (!digits) return '';
        return digits.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
    }

    document.addEventListener('DOMContentLoaded', function() {
        var display = document.getElementById('salary_per_hour_display');
        var hidden = document.getElementById('salary_per_hour');
        if (!display || !hidden) return;

        function sync() {
            var formatted = formatThousands(display.value);
            display.value = formatted;
            hidden.value = formatted ? formatted.replace(/\./g, '') : '';
        }

        display.addEventListener('input', sync);
        display.addEventListener('blur', sync);
        sync();
    });
})();
</script>
@endpush
@endsection
