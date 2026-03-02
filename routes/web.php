<?php

use App\Http\Controllers\Admin\CenterClassController as AdminCenterClassController;
use App\Http\Controllers\Admin\CenterController as AdminCenterController;
use App\Http\Controllers\Admin\CenterStudentController as AdminCenterStudentController;
use App\Http\Controllers\Admin\CourseController as AdminCourseController;
use App\Http\Controllers\Admin\NewsController as AdminNewsController;
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
    if (auth()->user()->isAdmin()) {
        return redirect()->route('admin.dashboard');
    }
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Trang quản trị (chỉ admin)
Route::prefix('admin')->name('admin.')->middleware(['auth', 'verified', 'admin'])->group(function () {
    Route::get('/', fn () => view('admin.dashboard'))->name('dashboard');
    Route::resource('users', AdminUserController::class)->except(['show']);
    Route::resource('courses', AdminCourseController::class)->except(['show']);
    Route::resource('centers', AdminCenterController::class)->except(['show']);
    Route::resource('centers.classes', AdminCenterClassController::class)->parameters(['class' => 'center_class'])->except(['show']);
    Route::get('centers/{center}/classes/{center_class}/students/import', [AdminCenterStudentController::class, 'importForm'])->name('centers.classes.students.import');
    Route::get('centers/{center}/classes/{center_class}/students/import/template', [AdminCenterStudentController::class, 'downloadTemplate'])->name('centers.classes.students.import.template');
    Route::post('centers/{center}/classes/{center_class}/students/import', [AdminCenterStudentController::class, 'import'])->name('centers.classes.students.import.store');
    Route::resource('centers.classes.students', AdminCenterStudentController::class)->parameters(['class' => 'center_class'])->except(['show']);
    Route::resource('news', AdminNewsController::class)->except(['show']);
    Route::delete('news/{news}/images/{image}', [AdminNewsController::class, 'destroyImage'])->name('news.images.destroy')->scopeBindings();
    Route::get('site', [AdminSiteSettingsController::class, 'edit'])->name('site.edit');
    Route::put('site', [AdminSiteSettingsController::class, 'update'])->name('site.update');
});

require __DIR__.'/auth.php';
