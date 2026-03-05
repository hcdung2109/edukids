<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Course extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
        'icon',
        'image',
        'sort_order',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function centerClasses(): HasMany
    {
        return $this->hasMany(CenterClass::class, 'course_id');
    }

    public function materials(): HasMany
    {
        return $this->hasMany(CourseMaterial::class)->whereNull('parent_id')->orderBy('sort_order')->orderBy('name');
    }

    public function allMaterials(): HasMany
    {
        return $this->hasMany(CourseMaterial::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('name');
    }

    protected static function booted(): void
    {
        static::creating(function (Course $course): void {
            if (empty($course->slug)) {
                $course->slug = Str::slug($course->name);
            }
        });
    }
}
