<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            ['name' => 'users.manage', 'label' => 'Quản lý tài khoản', 'group' => 'Quản trị', 'sort_order' => 10],
            ['name' => 'news.manage', 'label' => 'Tin tức & Sự kiện', 'group' => 'Quản trị', 'sort_order' => 20],
            ['name' => 'site.manage', 'label' => 'Quản lý site', 'group' => 'Quản trị', 'sort_order' => 30],
            ['name' => 'courses.view', 'label' => 'Xem khóa học', 'group' => 'Khóa học', 'sort_order' => 40],
            ['name' => 'courses.manage', 'label' => 'Thêm / Sửa / Xóa khóa học', 'group' => 'Khóa học', 'sort_order' => 50],
            ['name' => 'courses.materials.view', 'label' => 'Xem & tải tài liệu', 'group' => 'Khóa học', 'sort_order' => 60],
            ['name' => 'courses.materials.manage', 'label' => 'Quản lý tài liệu (thêm/sửa/xóa)', 'group' => 'Khóa học', 'sort_order' => 70],
            ['name' => 'centers.view', 'label' => 'Xem danh sách trung tâm', 'group' => 'Trung tâm', 'sort_order' => 80],
            ['name' => 'centers.manage', 'label' => 'Thêm / Sửa / Xóa trung tâm', 'group' => 'Trung tâm', 'sort_order' => 90],
            ['name' => 'center_classes.view', 'label' => 'Xem danh sách lớp học', 'group' => 'Lớp học', 'sort_order' => 100],
            ['name' => 'center_classes.manage', 'label' => 'Thêm / Sửa / Xóa lớp học', 'group' => 'Lớp học', 'sort_order' => 110],
            ['name' => 'center_students.view', 'label' => 'Xem danh sách học viên', 'group' => 'Học viên', 'sort_order' => 120],
            ['name' => 'center_students.manage', 'label' => 'Thêm / Sửa / Xóa / Import học viên', 'group' => 'Học viên', 'sort_order' => 130],
            ['name' => 'class_sessions.manage', 'label' => 'Quản lý lịch buổi học (đánh dấu buổi)', 'group' => 'Lịch & Điểm danh', 'sort_order' => 140],
            ['name' => 'attendance', 'label' => 'Điểm danh (lớp được gán)', 'group' => 'Lịch & Điểm danh', 'sort_order' => 150],
        ];

        foreach ($permissions as $p) {
            Permission::updateOrCreate(['name' => $p['name']], $p);
        }

        $teacherRole = User::ROLE_TEACHER;
        $defaultTeacherPermissions = Permission::whereIn('name', [
            'courses.view',
            'courses.materials.view',
            'centers.view',
            'center_classes.view',
            'center_students.view',
            'class_sessions.manage',
            'attendance',
        ])->pluck('id')->toArray();

        DB::table('role_permissions')->where('role', $teacherRole)->delete();
        foreach ($defaultTeacherPermissions as $pid) {
            DB::table('role_permissions')->insert(['role' => $teacherRole, 'permission_id' => $pid]);
        }
    }
}
