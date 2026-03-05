<?php

use App\Http\Controllers\Admin\CenterClassController as AdminCenterClassController;
use App\Http\Controllers\Admin\CenterController as AdminCenterController;
use App\Http\Controllers\Admin\CenterStudentController as AdminCenterStudentController;
use App\Http\Controllers\Admin\ClassSessionController as AdminClassSessionController;
use App\Http\Controllers\Admin\CourseMaterialController as AdminCourseMaterialController;
use App\Http\Controllers\Admin\CourseController as AdminCourseController;
use App\Http\Controllers\Admin\LearningToolController as AdminLearningToolController;
use App\Http\Controllers\Admin\NewsController as AdminNewsController;
use App\Http\Controllers\Admin\PermissionController as AdminPermissionController;
use App\Http\Controllers\Admin\RoleController as AdminRoleController;
use App\Http\Controllers\Admin\SiteSettingsController as AdminSiteSettingsController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $news = \App\Models\News::published()->latest('published_at')->take(9)->get();
    return view('welcome', compact('news'));
});

Route::get('/tin-tuc', function () {
    $news = \App\Models\News::published()->latest('published_at')->paginate(12);
    return view('news.index', compact('news'));
})->name('news.index');

Route::get('/tin-tuc/{news:slug}', function (\App\Models\News $news) {
    if (! $news->is_published) {
        abort(404);
    }
    $news->load('images');
    return view('news.show', compact('news'));
})->name('news.show');

Route::get('/dashboard', function () {
    // Admin và Teacher đều chuyển về trang quản trị
    if (auth()->user()->isAdmin() || auth()->user()->isTeacher()) {
        return redirect()->route('admin.dashboard');
    }
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Trang quản trị (admin + teacher vào được; một số route chỉ admin)
Route::prefix('admin')->name('admin.')->middleware(['auth', 'verified', 'admin'])->group(function () {
    Route::get('/', function () {
        $centers = \App\Models\Center::withCount('classes')->ordered()->get();
        return view('admin.dashboard', compact('centers'));
    })->name('dashboard');

    // Chỉ admin: quản lý tài khoản, vai trò, tin tức, cấu hình site, phân quyền
    Route::middleware('admin-only')->group(function () {
        Route::resource('users', AdminUserController::class)->except(['show']);
        Route::resource('roles', AdminRoleController::class)->except(['show']);
        Route::resource('news', AdminNewsController::class)->except(['show']);
        Route::delete('news/{news}/images/{image}', [AdminNewsController::class, 'destroyImage'])->name('news.images.destroy')->scopeBindings();
        Route::get('site', [AdminSiteSettingsController::class, 'edit'])->name('site.edit');
        Route::put('site', [AdminSiteSettingsController::class, 'update'])->name('site.update');
        Route::get('permissions', [AdminPermissionController::class, 'index'])->name('permissions.index');
        Route::put('permissions', [AdminPermissionController::class, 'update'])->name('permissions.update');
    });

    // Khóa học, tài liệu: teacher xem được; thêm/sửa/xóa kiểm tra trong controller
    Route::resource('courses', AdminCourseController::class)->except(['show']);
    Route::post('courses/{course}/materials/upload', [AdminCourseMaterialController::class, 'storeFiles'])->name('courses.materials.upload');
    Route::get('courses/{course}/materials/{material}/view', [AdminCourseMaterialController::class, 'view'])->name('courses.materials.view');
    Route::resource('courses.materials', AdminCourseMaterialController::class)->except(['show']);

    // Trung tâm, lớp, buổi học, điểm danh: teacher xem/lịch/điểm danh lớp được gán; thêm/sửa/xóa kiểm tra trong controller
    Route::resource('centers', AdminCenterController::class)->except(['show']);
    Route::resource('centers.classes', AdminCenterClassController::class)->parameters(['class' => 'center_class'])->except(['show']);
    Route::get('centers/{center}/classes/{center_class}/sessions', [AdminClassSessionController::class, 'index'])->name('centers.classes.sessions.index');
    Route::get('centers/{center}/classes/{center_class}/sessions/by-date', [AdminClassSessionController::class, 'sessionsByDate'])->name('centers.classes.sessions.by-date');
    Route::post('centers/{center}/classes/{center_class}/sessions', [AdminClassSessionController::class, 'store'])->name('centers.classes.sessions.store');
    Route::delete('centers/{center}/classes/{center_class}/sessions/{session}', [AdminClassSessionController::class, 'destroy'])->name('centers.classes.sessions.destroy');
    Route::post('centers/{center}/classes/{center_class}/sessions/destroy-by-date', [AdminClassSessionController::class, 'destroyByDate'])->name('centers.classes.sessions.destroy-by-date');
    Route::post('centers/{center}/classes/{center_class}/sessions/destroy-all', [AdminClassSessionController::class, 'destroyAll'])->name('centers.classes.sessions.destroy-all');
    Route::get('centers/{center}/classes/{center_class}/attendance', [AdminClassSessionController::class, 'attendancePage'])->name('centers.classes.attendance.index');
    Route::get('centers/{center}/classes/{center_class}/attendance-matrix', [AdminClassSessionController::class, 'attendanceMatrix'])->name('centers.classes.attendance.matrix');
    Route::post('centers/{center}/classes/{center_class}/attendance', [AdminClassSessionController::class, 'saveAttendanceBulk'])->name('centers.classes.attendance.store');
    Route::get('centers/{center}/classes/{center_class}/sessions/{session}/attendance', [AdminClassSessionController::class, 'attendance'])->name('centers.classes.sessions.attendance');
    Route::post('centers/{center}/classes/{center_class}/sessions/{session}/attendance', [AdminClassSessionController::class, 'saveAttendance'])->name('centers.classes.sessions.attendance.store');
    Route::get('centers/{center}/classes/{center_class}/attendance-export', [AdminClassSessionController::class, 'exportAttendance'])->name('centers.classes.attendance.export');
    Route::get('centers/{center}/classes/{center_class}/students/import', [AdminCenterStudentController::class, 'importForm'])->name('centers.classes.students.import');
    Route::get('centers/{center}/classes/{center_class}/students/import/template', [AdminCenterStudentController::class, 'downloadTemplate'])->name('centers.classes.students.import.template');
    Route::post('centers/{center}/classes/{center_class}/students/import', [AdminCenterStudentController::class, 'import'])->name('centers.classes.students.import.store');
    Route::resource('centers.classes.students', AdminCenterStudentController::class)->parameters(['class' => 'center_class'])->except(['show']);
    Route::resource('learning-tools', AdminLearningToolController::class)->except(['show']);
});

require __DIR__.'/auth.php';
