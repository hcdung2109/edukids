<?php

namespace Database\Seeders;

use App\Models\CenterClass;
use App\Models\Student;
use Illuminate\Database\Seeder;

class StudentSeeder extends Seeder
{
    public function run(): void
    {
        $classes = CenterClass::with('center')->ordered()->get();
        if ($classes->isEmpty()) {
            return;
        }

        $samples = [
            ['name' => 'Nguyễn Minh Anh', 'date_of_birth' => '2016-05-12', 'parent_name' => 'Nguyễn Văn A', 'parent_phone' => '0901 234 567'],
            ['name' => 'Trần Bảo Chi', 'date_of_birth' => '2017-08-20', 'parent_name' => 'Trần Thị B', 'parent_phone' => '0912 345 678'],
            ['name' => 'Lê Đức Dũng', 'date_of_birth' => '2015-03-08', 'parent_name' => 'Lê Văn C', 'parent_phone' => '0987 654 321'],
            ['name' => 'Phạm Thu Hà', 'date_of_birth' => '2016-11-15', 'parent_name' => 'Phạm Thị D', 'parent_phone' => '0909 888 777'],
            ['name' => 'Hoàng Quang Huy', 'date_of_birth' => '2017-01-22', 'parent_name' => 'Hoàng Văn E', 'parent_phone' => '0933 111 222'],
            ['name' => 'Võ Thị Lan', 'date_of_birth' => '2016-07-30', 'parent_name' => 'Võ Thị F', 'parent_phone' => '0977 555 666'],
            ['name' => 'Đặng Tuấn Nam', 'date_of_birth' => '2015-09-05', 'parent_name' => 'Đặng Văn G', 'parent_phone' => '0944 333 444'],
            ['name' => 'Bùi Mai Phương', 'date_of_birth' => '2017-04-18', 'parent_name' => 'Bùi Thị H', 'parent_phone' => '0966 999 000'],
        ];

        $index = 0;
        foreach ($classes as $class) {
            $count = 3;
            if (str_contains($class->name, 'Robotics cơ bản')) {
                $count = 4;
            }
            for ($i = 0; $i < $count; $i++) {
                $s = $samples[($index + $i) % count($samples)];
                Student::updateOrCreate(
                    [
                        'center_class_id' => $class->id,
                        'name' => $s['name'],
                    ],
                    [
                        'date_of_birth' => $s['date_of_birth'],
                        'parent_name' => $s['parent_name'],
                        'parent_phone' => $s['parent_phone'],
                        'sort_order' => $i,
                    ]
                );
            }
            $index += 2;
        }
    }
}
