<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        if (User::where('email', 'admin@edukids.vn')->doesntExist()) {
            User::factory()->create([
                'name' => 'Administrator',
                'email' => 'admin@edukids.vn',
                'password' => bcrypt('12345678'),
                'role' => User::ROLE_ADMIN,
            ]);
        }

        $this->call(RoleSeeder::class);
        $this->call(PermissionSeeder::class);
        $this->call(NewsSeeder::class);
        $this->call(CourseSeeder::class);
        $this->call(CenterSeeder::class);
        $this->call(CenterClassSeeder::class);
        $this->call(StudentSeeder::class);
    }
}
