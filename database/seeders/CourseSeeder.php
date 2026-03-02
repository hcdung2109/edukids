<?php

namespace Database\Seeders;

use App\Models\Course;
use Illuminate\Database\Seeder;

class CourseSeeder extends Seeder
{
    public function run(): void
    {
        $courses = [
            [
                'name' => 'Robotics',
                'description' => 'Lắp ráp, lập trình robot – phát triển tư duy logic và kỹ năng kỹ thuật.',
                'icon' => '🤖',
                'sort_order' => 1,
                'is_active' => true,
            ],
            [
                'name' => 'STEM',
                'description' => 'Khoa học – Công nghệ – Kỹ thuật – Toán học, học qua thực hành.',
                'icon' => '🔬',
                'sort_order' => 2,
                'is_active' => true,
            ],
            [
                'name' => 'Lập trình',
                'description' => 'Từ kéo thả đến code – xây dựng nền tảng lập trình từ nhỏ.',
                'icon' => '💻',
                'sort_order' => 3,
                'is_active' => true,
            ],
            [
                'name' => 'Kỹ năng',
                'description' => 'Làm việc nhóm, thuyết trình, tư duy phản biện và sáng tạo.',
                'icon' => '✨',
                'sort_order' => 4,
                'is_active' => true,
            ],
        ];

        foreach ($courses as $data) {
            Course::updateOrCreate(
                ['slug' => \Illuminate\Support\Str::slug($data['name'])],
                $data
            );
        }
    }
}
