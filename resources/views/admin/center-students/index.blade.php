@extends('admin.layouts.app')

@section('title', 'Danh sách học viên – ' . $center_class->name)

@section('content')
<div class="card" data-csrf="{{ csrf_token() }}">
    <div class="card-header">
        <h3 class="card-title">Danh sách học viên: {{ $center_class->name }}</h3>
        <p class="text-muted small mb-0">Trung tâm: {{ $center->name }}</p>
        <div class="card-tools">
            @if(auth()->user()->isAdmin())
            <a href="{{ route('admin.centers.classes.students.create', [$center, $center_class]) }}" class="btn btn-sm btn-primary"><i class="fas fa-plus"></i> Thêm học viên</a>
            <a href="{{ route('admin.centers.classes.students.import', [$center, $center_class]) }}" class="btn btn-sm btn-success"><i class="fas fa-file-excel"></i> Import Excel</a>
            @endif
            <a href="{{ route('admin.centers.classes.index', $center) }}" class="btn btn-sm btn-default"><i class="fas fa-arrow-left"></i> Về danh sách lớp</a>
        </div>
    </div>
    <div class="card-body">
        <table class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th style="width: 50px">STT</th>
                    <th>Họ tên</th>
                    <th>Lớp</th>
                    <th>Trường</th>
                    <th>Ngày sinh</th>
                    <th>Điện thoại</th>
                    <th>Phụ huynh</th>
                    <th>SĐT phụ huynh</th>
                    <th class="text-center" style="width: 100px">Thu học phí</th>
                    <th>Ghi chú</th>
                    <th style="width: 120px">Thao tác</th>
                </tr>
            </thead>
            <tbody>
                @forelse($students as $item)
                    <tr>
                        <td>{{ $item->sort_order }}</td>
                        <td>{{ $item->name }}</td>
                        <td>{{ $item->class_name ?: '–' }}</td>
                        <td>{{ Str::limit($item->school_name, 30) ?: '–' }}</td>
                        <td>{{ $item->date_of_birth?->format('d/m/Y') ?: '–' }}</td>
                        <td>{{ $item->phone ?: '–' }}</td>
                        <td>{{ $item->parent_name ?: '–' }}</td>
                        <td>{{ $item->parent_phone ?: '–' }}</td>
                        <td class="text-center">
                            <div class="custom-control custom-switch d-inline-block">
                                <input type="checkbox" class="custom-control-input js-tuition-paid" id="tuition_{{ $item->id }}"
                                    data-student-id="{{ $item->id }}"
                                    data-url="{{ route('admin.centers.classes.students.tuition-paid', [$center, $center_class, $item]) }}"
                                    {{ $item->tuition_paid ? 'checked' : '' }}>
                                <label class="custom-control-label" for="tuition_{{ $item->id }}"></label>
                            </div>
                        </td>
                        <td>{{ Str::limit($item->note, 40) ?: '–' }}</td>
                        <td>
                            @if(auth()->user()->isAdmin())
                            <a href="{{ route('admin.centers.classes.students.edit', [$center, $center_class, $item]) }}" class="btn btn-sm btn-default"><i class="fas fa-edit"></i></a>
                            <form action="{{ route('admin.centers.classes.students.destroy', [$center, $center_class, $item]) }}" method="POST" class="d-inline" onsubmit="return confirm('Xóa học viên này?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                            </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="11" class="text-center text-muted">Chưa có học viên. <a href="{{ route('admin.centers.classes.students.create', [$center, $center_class]) }}">Thêm học viên</a> hoặc <a href="{{ route('admin.centers.classes.students.import', [$center, $center_class]) }}">Import Excel</a></td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <div class="mt-3">
            {{ $students->links() }}
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
(function() {
    function getCsrf() {
        var card = document.querySelector('.card[data-csrf]');
        if (card) return card.getAttribute('data-csrf') || '';
        var meta = document.querySelector('meta[name="csrf-token"]');
        return meta ? meta.getAttribute('content') || '' : '';
    }

    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.js-tuition-paid').forEach(function(cb) {
            cb.addEventListener('change', function() {
                var url = this.getAttribute('data-url');
                var checked = this.checked;
                var checkbox = this;
                var csrf = getCsrf();

                fetch(url, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': csrf
                    },
                    body: JSON.stringify({ tuition_paid: checked ? 1 : 0 })
                }).then(function(r) {
                    if (r.ok) return r.json();
                    return r.text().then(function(text) {
                        var msg = 'Không lưu được (mã ' + r.status + ').';
                        try {
                            var body = JSON.parse(text);
                            if (body.message) msg = body.message;
                        } catch (_) {}
                        throw new Error(msg);
                    });
                }).then(function() {
                    // success
                }).catch(function(err) {
                    checkbox.checked = !checked;
                    alert(err.message || 'Không lưu được. Vui lòng thử lại.');
                });
            });
        });
    });
})();
</script>
@endpush
