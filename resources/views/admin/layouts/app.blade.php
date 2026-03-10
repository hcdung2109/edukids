<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Quản trị') - EduKids</title>
    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- AdminLTE -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
    @stack('styles')
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">
    <!-- Navbar -->
    <nav class="main-header navbar navbar-expand navbar-white navbar-light">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
            </li>
        </ul>
        <ul class="navbar-nav ml-auto">
            <li class="nav-item dropdown">
                <a class="nav-link" data-toggle="dropdown" href="#">
                    <i class="fas fa-user-circle"></i> {{ auth()->user()->name }}
                </a>
                <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                    <a href="{{ route('profile.edit') }}" class="dropdown-item"><i class="fas fa-user mr-2"></i> Hồ sơ</a>
                    <div class="dropdown-divider"></div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="dropdown-item dropdown-footer"><i class="fas fa-sign-out-alt mr-2"></i> Đăng xuất</button>
                    </form>
                </div>
            </li>
        </ul>
    </nav>
    <!-- Sidebar -->
    <aside class="main-sidebar sidebar-dark-primary elevation-4">
        <a href="{{ route('admin.dashboard') }}" class="brand-link">
            <span class="brand-text font-weight-light">EduKids Admin</span>
        </a>
        <div class="sidebar">
            <nav class="mt-2">
                <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu">
                    <li class="nav-item">
                        <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-tachometer-alt"></i>
                            <p>Dashboard</p>
                        </a>
                    </li>
                    @if(auth()->user()->hasPermission('centers.view') || auth()->user()->hasPermission('centers.manage'))
                    <li class="nav-item">
                        <a href="{{ route('admin.centers.index') }}" class="nav-link {{ request()->routeIs('admin.centers.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-building"></i>
                            <p>Quản lý trung tâm</p>
                        </a>
                    </li>
                    @endif
                    @if(auth()->user()->hasPermission('users.manage'))
                    <li class="nav-item">
                        <a href="{{ route('admin.users.index') }}" class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-users"></i>
                            <p>Quản lý giáo viên</p>
                        </a>
                    </li>
                    @endif
                    @if(auth()->user()->hasPermission('courses.view') || auth()->user()->hasPermission('courses.manage'))
                    <li class="nav-item">
                        <a href="{{ route('admin.courses.index') }}" class="nav-link {{ request()->routeIs('admin.courses.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-graduation-cap"></i>
                            <p>Quản lý khóa học</p>
                        </a>
                    </li>
                    @endif
                    @if(auth()->user()->isAdmin())
                    <li class="nav-item">
                        <a href="{{ route('admin.roles.index') }}" class="nav-link {{ request()->routeIs('admin.roles.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-user-tag"></i>
                            <p>Quản lý vai trò</p>
                        </a>
                    </li>
                    @endif
                    @if(auth()->user()->isAdmin())
                    <li class="nav-item">
                        <a href="{{ route('admin.learning-tools.index') }}" class="nav-link {{ request()->routeIs('admin.learning-tools.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-toolbox"></i>
                            <p>Quản lý công cụ học</p>
                        </a>
                    </li>
                    @endif
                    @if(auth()->user()->hasPermission('news.manage'))
                    <li class="nav-item">
                        <a href="{{ route('admin.news.index') }}" class="nav-link {{ request()->routeIs('admin.news.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-newspaper"></i>
                            <p>Tin tức & Sự kiện</p>
                        </a>
                    </li>
                    @endif
                    @if(auth()->user()->hasPermission('site.manage'))
                    <li class="nav-item">
                        <a href="{{ route('admin.site.edit') }}" class="nav-link {{ request()->routeIs('admin.site.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-cog"></i>
                            <p>Quản lý site</p>
                        </a>
                    </li>
                    @endif
                    @if(auth()->user()->isAdmin())
                    <li class="nav-item">
                        <a href="{{ route('admin.permissions.index') }}" class="nav-link {{ request()->routeIs('admin.permissions.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-key"></i>
                            <p>Phân quyền</p>
                        </a>
                    </li>
                    @endif
                </ul>
            </nav>
        </div>
    </aside>
    <!-- Content -->
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">@yield('title', 'Quản trị')</h1>
                    </div>
                </div>
            </div>
        </div>
        <section class="content">
            <div class="container-fluid">
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                        {{ session('success') }}
                    </div>
                @endif
                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                        {{ session('error') }}
                    </div>
                @endif
                @yield('content')
            </div>
        </section>
    </div>
    <footer class="main-footer">
        <strong>EduKids</strong> – Trang quản trị
    </footer>
</div>
<!-- jQuery -->
<script src="https://cdn.jsdelivr.net/npm/jquery@3.6/dist/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6/dist/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE -->
<script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>
<!-- Font Awesome -->
<script src="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6/js/all.min.js"></script>
@stack('scripts')
</body>
</html>
