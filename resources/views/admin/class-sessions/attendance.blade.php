@extends('admin.layouts.app')

@section('title', 'Điểm danh – ' . $center_class->name)

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title"><i class="fas fa-clipboard-check"></i> Điểm danh – {{ $center_class->name }}</h3>
        <p class="text-muted small mb-0">Trung tâm: {{ $center->name }}</p>
        <div class="card-tools">
            <a href="{{ route('admin.centers.classes.attendance.export', [$center, $center_class]) }}" class="btn btn-sm btn-success mr-2" target="_blank"><i class="fas fa-file-excel"></i> Xuất Excel</a>
            <a href="{{ route('admin.centers.classes.sessions.index', [$center, $center_class]) }}" class="btn btn-sm btn-default"><i class="fas fa-arrow-left"></i> Về lịch buổi học</a>
        </div>
    </div>
    <div class="card-body">
        <p class="small text-muted mb-2">Chọn ô nếu học viên có đi học buổi tương ứng.</p>

        <form method="POST" action="{{ route('admin.centers.classes.attendance.store', [$center, $center_class]) }}">
            @csrf
            <div class="table-responsive">
                <table class="table table-bordered table-sm">
                    <thead>
                        <tr>
                            <th class="text-nowrap">STT</th>
                            <th class="text-nowrap">Họ tên</th>
                            @foreach($sessions as $s)
                                <th class="text-center text-nowrap" style="min-width: 70px;">{{ $s->note ?: ('Buổi ' . $s->session_date->format('d/m/Y')) }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($students as $i => $student)
                            <tr>
                                <td class="text-nowrap">{{ $i + 1 }}</td>
                                <td class="text-nowrap">{{ $student->name }}</td>
                                @foreach($sessions as $s)
                                    @php
                                        $checked = isset($attendance[$s->id][$student->id]) && $attendance[$s->id][$student->id];
                                    @endphp
                                    <td class="text-center">
                                        <input type="hidden" name="attendance[{{ $s->id }}][{{ $student->id }}]" value="0">
                                        <input type="checkbox" class="form-check-input" name="attendance[{{ $s->id }}][{{ $student->id }}]" value="1" {{ $checked ? 'checked' : '' }}>
                                    </td>
                                @endforeach
                            </tr>
                        @empty
                            <tr>
                                <td colspan="{{ 2 + $sessions->count() }}" class="text-muted text-center">Chưa có học viên hoặc buổi học.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($students->isNotEmpty())
                <button type="submit" class="btn btn-primary btn-sm mt-2"><i class="fas fa-save"></i> Lưu điểm danh</button>
            @endif
        </form>
    </div>
</div>
@endsection
