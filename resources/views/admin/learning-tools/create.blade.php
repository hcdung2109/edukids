@extends('admin.layouts.app')

@section('title', 'Thêm công cụ học')

@section('content')
<div class="card shadow-sm">
    <div class="card-header bg-white border-bottom py-3">
        <h3 class="card-title mb-0 font-weight-bold text-dark">Thêm công cụ học</h3>
        <a href="{{ route('admin.learning-tools.index') }}" class="btn btn-sm btn-outline-secondary"><i class="fas fa-arrow-left"></i> Danh sách</a>
    </div>
    <form action="{{ route('admin.learning-tools.store') }}" method="POST">
        @csrf
        <div class="card-body">
            <div class="form-group">
                <label>Tên công cụ <span class="text-danger">*</span></label>
                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required>
                @error('name')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group">
                <label>Số lượng <span class="text-danger">*</span></label>
                <input type="number" name="quantity" class="form-control @error('quantity') is-invalid @enderror" value="{{ old('quantity', 1) }}" min="0" required>
                @error('quantity')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group">
                <label>Trung tâm <span class="text-danger">*</span></label>
                <select name="center_id" id="center_id" class="form-control @error('center_id') is-invalid @enderror" required>
                    <option value="">-- Chọn trung tâm --</option>
                    @foreach($centers as $c)
                        <option value="{{ $c->id }}" {{ old('center_id') == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                    @endforeach
                </select>
                @error('center_id')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group">
                <label>Lớp (không chọn = gán chung trung tâm)</label>
                <select name="center_class_id" id="center_class_id" class="form-control @error('center_class_id') is-invalid @enderror">
                    <option value="">-- Trung tâm chung --</option>
                    @foreach($centers as $c)
                        @foreach($c->classes as $cls)
                            <option value="{{ $cls->id }}" data-center="{{ $c->id }}" {{ old('center_class_id') == $cls->id ? 'selected' : '' }}>{{ $c->name }} – {{ $cls->name }}</option>
                        @endforeach
                    @endforeach
                </select>
                @error('center_class_id')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group">
                <label>Người quản lý</label>
                <select name="managed_by_user_id" class="form-control @error('managed_by_user_id') is-invalid @enderror">
                    <option value="">-- Không chọn --</option>
                    @foreach($managers as $u)
                        <option value="{{ $u->id }}" {{ old('managed_by_user_id') == $u->id ? 'selected' : '' }}>{{ $u->name }} ({{ $u->role }})</option>
                    @endforeach
                </select>
                @error('managed_by_user_id')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group">
                <label>Ghi chú</label>
                <textarea name="note" class="form-control @error('note') is-invalid @enderror" rows="2">{{ old('note') }}</textarea>
                @error('note')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>
        </div>
        <div class="card-footer bg-white border-top">
            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Lưu</button>
            <a href="{{ route('admin.learning-tools.index') }}" class="btn btn-outline-secondary">Hủy</a>
        </div>
    </form>
</div>
@push('scripts')
<script>
(function() {
    var centerSelect = document.getElementById('center_id');
    var classSelect = document.getElementById('center_class_id');
    if (!centerSelect || !classSelect) return;
    var optionsByCenter = {};
    for (var i = 0; i < classSelect.options.length; i++) {
        var opt = classSelect.options[i];
        if (opt.value === '') continue;
        var cid = opt.getAttribute('data-center');
        if (!optionsByCenter[cid]) optionsByCenter[cid] = [];
        optionsByCenter[cid].push({ value: opt.value, text: opt.text });
    }
    function updateClassOptions() {
        var centerId = centerSelect.value;
        var currentVal = classSelect.value;
        classSelect.innerHTML = '<option value="">-- Trung tâm chung --</option>';
        if (centerId && optionsByCenter[centerId]) {
            optionsByCenter[centerId].forEach(function(o) {
                var opt = new Option(o.text, o.value, false, o.value == currentVal);
                classSelect.add(opt);
            });
        }
    }
    centerSelect.addEventListener('change', updateClassOptions);
    updateClassOptions(); // Lọc lớp theo trung tâm đã chọn khi load (vd. sau lỗi validate)
})();
</script>
@endpush
@endsection
