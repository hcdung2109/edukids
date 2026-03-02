<?php

namespace Database\Seeders;

use App\Models\Center;
use App\Models\CenterClass;
use Illuminate\Database\Seeder;

class CenterClassSeeder extends Seeder
{
    public function run(): void
    {
        $centers = Center::ordered()->get();
        if ($centers->isEmpty()) {
            return;
        }

        $classesByCenter = [
            'EduKids - Cơ sở Quận 1' => [
                ['name' => 'Robotics cơ bản 6–9 tuổi', 'schedule' => 'Thứ 2, 4 – 18h00', 'description' => 'Làm quen lắp ráp và lập trình robot, phát triển tư duy logic.', 'sort_order' => 1],
                ['name' => 'STEM Khoa học vui', 'schedule' => 'Thứ 3, 5 – 18h00', 'description' => 'Thí nghiệm khoa học, khám phá tự nhiên qua hoạt động thực hành.', 'sort_order' => 2],
                ['name' => 'Lập trình Scratch', 'schedule' => 'Thứ 6 – 18h00, Chủ nhật – 9h00', 'description' => 'Lập trình kéo thả với Scratch cho trẻ 8–12 tuổi.', 'sort_order' => 3],
            ],
            'EduKids - Cơ sở Quận 7' => [
                ['name' => 'Robotics nâng cao', 'schedule' => 'Thứ 2, 4 – 17h30', 'description' => 'Lập trình robot phức tạp, thi đấu và dự án nhóm.', 'sort_order' => 1],
                ['name' => 'Kỹ năng thuyết trình', 'schedule' => 'Thứ 7 – 14h00', 'description' => 'Rèn luyện tự tin, kỹ năng nói trước đám đông.', 'sort_order' => 2],
            ],
            'EduKids - Cơ sở Bình Thạnh' => [
                ['name' => 'Robotics cơ bản', 'schedule' => 'Thứ 3, 5 – 18h00', 'description' => 'Khóa học Robotics dành cho trẻ mới bắt đầu.', 'sort_order' => 1],
                ['name' => 'STEM Sáng tạo', 'schedule' => 'Thứ 7 – 9h00', 'description' => 'Kết hợp Khoa học – Công nghệ – Kỹ thuật – Toán học.', 'sort_order' => 2],
                ['name' => 'Lập trình Python cho trẻ', 'schedule' => 'Chủ nhật – 14h00', 'description' => 'Làm quen ngôn ngữ lập trình Python qua dự án nhỏ.', 'sort_order' => 3],
            ],
            'EduKids - Cơ sở Gò Vấp' => [
                ['name' => 'Robotics 6–10 tuổi', 'schedule' => 'Thứ 2, 4, 6 – 17h30', 'description' => 'Lớp Robotics phù hợp lứa tuổi tiểu học.', 'sort_order' => 1],
                ['name' => 'Lập trình game', 'schedule' => 'Thứ 7 – 15h00', 'description' => 'Tạo game đơn giản với công cụ lập trình trực quan.', 'sort_order' => 2],
            ],
        ];

        foreach ($centers as $center) {
            $classes = $classesByCenter[$center->name] ?? [];
            foreach ($classes as $index => $data) {
                CenterClass::updateOrCreate(
                    [
                        'center_id' => $center->id,
                        'name' => $data['name'],
                    ],
                    [
                        'description' => $data['description'] ?? null,
                        'schedule' => $data['schedule'] ?? null,
                        'sort_order' => $data['sort_order'] ?? $index + 1,
                        'is_active' => true,
                    ]
                );
            }
        }
    }
}
