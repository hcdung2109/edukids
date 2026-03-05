@extends('admin.layouts.app')

@section('title', 'Dashboard')

@push('styles')
<style>
.dashboard-center-card {
    height: 100%;
    border-radius: 10px;
    border: 1px solid #e9ecef;
    transition: box-shadow .2s, transform .02s;
}
.dashboard-center-card:hover {
    box-shadow: 0 4px 12px rgba(0,0,0,.08);
}
.dashboard-center-card .card-body { padding: 1.25rem; }
.dashboard-center-card .center-icon-wrap {
    width: 48px; height: 48px;
    border-radius: 10px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: #fff;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.25rem;
}
.dashboard-center-card .center-name {
    font-size: 1.1rem;
    font-weight: 600;
    color: #2d3748;
    margin-bottom: 0.5rem;
}
.dashboard-center-card .center-meta {
    font-size: 0.875rem;
    color: #718096;
    margin-bottom: 0.35rem;
}
.dashboard-center-card .center-meta i {
    width: 18px;
    color: #a0aec0;
}
.dashboard-center-card .center-actions {
    margin-top: 1rem;
    padding-top: 1rem;
    border-top: 1px solid #f0f0f0;
}
</style>
@endpush

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <p class="mb-0">Chào mừng <strong>{{ auth()->user()->name }}</strong> đến trang quản trị EduKids.</p>
                <p class="text-muted small mt-1">Vai trò: <span class="badge badge-primary">{{ auth()->user()->role }}</span></p>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-12 d-flex justify-content-between align-items-center flex-wrap mb-3">
        <h5 class="mb-0 font-weight-bold text-dark">Trung tâm</h5>
        <div>
            <a href="{{ route('admin.centers.create') }}" class="btn btn-sm btn-primary"><i class="fas fa-plus"></i> Thêm trung tâm</a>
            <a href="{{ route('admin.centers.index') }}" class="btn btn-sm btn-outline-secondary">Xem tất cả</a>
        </div>
    </div>
    @if($centers->isEmpty())
        <div class="col-12">
            <div class="card border-0 bg-light">
                <div class="card-body text-center py-5">
                    <i class="fas fa-building fa-3x text-muted mb-3"></i>
                    <p class="text-muted mb-2">Chưa có trung tâm nào</p>
                    <a href="{{ route('admin.centers.create') }}" class="btn btn-primary"><i class="fas fa-plus"></i> Thêm trung tâm đầu tiên</a>
                </div>
            </div>
        </div>
    @else
        @foreach($centers as $center)
            <div class="col-12 col-sm-6 col-lg-4 mb-3">
                <div class="card dashboard-center-card h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-start mb-2">
                            <div class="center-icon-wrap mr-3 flex-shrink-0">
                                <i class="fas fa-building"></i>
                            </div>
                            <div class="flex-grow-1 min-width-0">
                                <h6 class="center-name mb-1">{{ $center->name }}</h6>
                                @if($center->is_active)
                                    <span class="badge badge-success badge-sm">Hiển thị</span>
                                @else
                                    <span class="badge badge-secondary badge-sm">Ẩn</span>
                                @endif
                            </div>
                        </div>
                        @if($center->address)
                            <p class="center-meta mb-1"><i class="fas fa-map-marker-alt"></i> {{ Str::limit($center->address, 45) }}</p>
                        @endif
                        @if($center->phone)
                            <p class="center-meta mb-0"><i class="fas fa-phone-alt"></i> {{ $center->phone }}</p>
                        @endif
                        <div class="center-actions d-flex justify-content-between align-items-center flex-wrap gap-1">
                            <a href="{{ route('admin.centers.classes.index', $center) }}" class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-graduation-cap"></i> Lớp học ({{ $center->classes_count }})
                            </a>
                            <a href="{{ route('admin.centers.edit', $center) }}" class="btn btn-sm btn-outline-secondary" title="Sửa"><i class="fas fa-edit"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    @endif
</div>
@endsection
