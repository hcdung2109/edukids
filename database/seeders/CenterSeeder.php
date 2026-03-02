<?php

namespace Database\Seeders;

use App\Models\Center;
use Illuminate\Database\Seeder;

class CenterSeeder extends Seeder
{
    public function run(): void
    {
        $centers = [
            [
                'name' => 'EduKids - Cơ sở Quận 1',
                'address' => '123 Nguyễn Huệ, Phường Bến Nghé, Quận 1, TP. Hồ Chí Minh',
                'phone' => '028 1234 5678',
                'email' => 'quan1@edukids.vn',
                'website' => 'https://edukids.vn',
                'description' => 'Trung tâm chính tại Quận 1, không gian học tập hiện đại, đầy đủ thiết bị Robotics và STEM.',
                'sort_order' => 1,
                'is_active' => true,
            ],
            [
                'name' => 'EduKids - Cơ sở Quận 7',
                'address' => '456 Nguyễn Lương Bằng, Phường Tân Phú, Quận 7, TP. Hồ Chí Minh',
                'phone' => '028 8765 4321',
                'email' => 'quan7@edukids.vn',
                'website' => null,
                'description' => 'Cơ sở Quận 7 phục vụ khu vực phía Nam, nhiều lớp Robotics và Lập trình cho trẻ.',
                'sort_order' => 2,
                'is_active' => true,
            ],
            [
                'name' => 'EduKids - Cơ sở Bình Thạnh',
                'address' => '789 Điện Biên Phủ, Phường 25, Quận Bình Thạnh, TP. Hồ Chí Minh',
                'phone' => '028 2345 6789',
                'email' => 'binhthanh@edukids.vn',
                'website' => null,
                'description' => 'Trung tâm tại Bình Thạnh với chương trình STEM và Kỹ năng mềm đa dạng.',
                'sort_order' => 3,
                'is_active' => true,
            ],
            [
                'name' => 'EduKids - Cơ sở Gò Vấp',
                'address' => '321 Quang Trung, Phường 10, Quận Gò Vấp, TP. Hồ Chí Minh',
                'phone' => '028 3456 7890',
                'email' => 'govap@edukids.vn',
                'website' => null,
                'description' => 'Cơ sở Gò Vấp thuận tiện cho phụ huynh khu vực phía Bắc, đào tạo Robotics và Lập trình.',
                'sort_order' => 4,
                'is_active' => true,
            ],
        ];

        foreach ($centers as $data) {
            Center::updateOrCreate(
                ['slug' => \Illuminate\Support\Str::slug($data['name'])],
                $data
            );
        }
    }
}
