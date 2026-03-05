<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            ['name' => User::ROLE_ADMIN, 'label' => 'Quản trị viên', 'description' => 'Toàn quyền quản trị hệ thống', 'is_system' => true, 'sort_order' => 0],
            ['name' => User::ROLE_TEACHER, 'label' => 'Giáo viên', 'description' => 'Giảng dạy và quản lý lớp được gán', 'is_system' => true, 'sort_order' => 10],
        ];

        foreach ($roles as $r) {
            Role::updateOrCreate(['name' => $r['name']], $r);
        }
    }
}
