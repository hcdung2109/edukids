<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SiteSetting extends Model
{
    protected $fillable = [
        'site_name',
        'footer_description',
        'address',
        'email',
        'phone',
        'hotline',
        'facebook_url',
    ];

    protected $casts = [];

    /** Lấy bản ghi cài đặt (singleton: 1 dòng). */
    public static function get(): self
    {
        $setting = static::query()->first();
        if ($setting) {
            return $setting;
        }
        return static::query()->create([
            'site_name' => 'EduKids',
            'footer_description' => 'Tổ Hợp Công Nghệ Giáo Dục – Robotics, STEM, Lập trình, Kỹ năng cho trẻ em.',
            'email' => 'lienhe@edukids.vn',
            'hotline' => '1900 xxxx',
            'facebook_url' => 'https://www.facebook.com/ToHopCongNgheGiaoDucEDUKIDS',
        ]);
    }
}
