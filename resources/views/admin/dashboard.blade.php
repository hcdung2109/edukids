@extends('admin.layouts.app')

@section('title', 'Dashboard')

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
@endsection
