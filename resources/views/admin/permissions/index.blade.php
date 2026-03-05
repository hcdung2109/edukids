@extends('admin.layouts.app')

@section('title', 'Phân quyền')

@push('styles')
<style>
.permission-role-select { max-width: 320px; }
.permission-group-card { border: 1px solid #dee2e6; border-radius: 6px; overflow: hidden; }
.permission-group-card .card-header { background: #f8f9fa; font-weight: 600; padding: 0.6rem 1rem; }
.permission-group-card .list-group-item { border-left: 0; border-right: 0; }
.role-info-admin { background: #e7f3ff; border-radius: 6px; padding: 1rem; }
</style>
@endpush

@section('content')
<div class="card shadow-sm">
    <div class="card-header bg-white border-bottom py-3">
        <h3 class="card-title mb-2 font-weight-bold text-dark">Phân quyền theo vai trò</h3>
        <p class="text-muted small mb-3">Chọn vai trò bên dưới, sau đó bật/tắt quyền cho vai trò đó. Lưu ý: <strong>Quản trị viên</strong> luôn có đủ mọi quyền và không thể chỉnh sửa.</p>

        <div class="form-group mb-0">
            <label for="role_select" class="font-weight-bold text-dark">Vai trò (Role)</label>
            <select id="role_select" class="form-control permission-role-select" name="role_select">
                @foreach($allRoles as $roleKey => $roleLabel)
                    <option value="{{ $roleKey }}" {{ $selectedRole === $roleKey ? 'selected' : '' }}>{{ $roleLabel }}</option>
                @endforeach
            </select>
        </div>
    </div>

    @if($selectedRole === 'admin')
        <div class="card-body">
            <div class="role-info-admin">
                <i class="fas fa-info-circle text-primary mr-2"></i>
                <strong>Quản trị viên</strong> có toàn quyền truy cập mọi chức năng. Không cần cấu hình quyền cho vai trò này.
            </div>
        </div>
    @else
        <form action="{{ route('admin.permissions.update') }}" method="POST">
            @csrf
            @method('PUT')
            <input type="hidden" name="role" value="{{ $selectedRole }}">
            <div class="card-body">
                <p class="text-muted small mb-3">Đang cấu hình quyền cho vai trò: <strong>{{ $allRoles[$selectedRole] ?? $selectedRole }}</strong>. Tick các ô bên dưới để cấp quyền.</p>

                <div class="row">
                    @foreach($permissions as $group => $items)
                        <div class="col-md-6 mb-3">
                            <div class="permission-group-card card h-100">
                                <div class="card-header">{{ $group }}</div>
                                <ul class="list-group list-group-flush">
                                    @foreach($items as $p)
                                        <li class="list-group-item d-flex align-items-center py-2">
                                            <div class="custom-control custom-checkbox flex-grow-1">
                                                <input type="checkbox"
                                                       class="custom-control-input"
                                                       name="permission_ids[]"
                                                       value="{{ $p->id }}"
                                                       id="perm_{{ $p->id }}"
                                                       {{ in_array($p->id, $permissionIdsForRole) ? 'checked' : '' }}>
                                                <label class="custom-control-label" for="perm_{{ $p->id }}">{{ $p->label }}</label>
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="card-footer bg-white border-top py-3">
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Lưu quyền</button>
                <a href="{{ route('admin.permissions.index') }}" class="btn btn-outline-secondary">Hủy</a>
            </div>
        </form>
    @endif
</div>

@push('scripts')
<script>
document.getElementById('role_select').addEventListener('change', function() {
    var role = this.value;
    window.location.href = '{{ route("admin.permissions.index") }}?role=' + encodeURIComponent(role);
});
</script>
@endpush
@endsection
