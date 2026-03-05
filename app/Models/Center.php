<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Center extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'address',
        'phone',
        'email',
        'website',
        'description',
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

    public function classes(): HasMany
    {
        return $this->hasMany(CenterClass::class, 'center_id')->orderBy('sort_order')->orderBy('name');
    }

    public function learningTools(): HasMany
    {
        return $this->hasMany(LearningTool::class, 'center_id');
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
        static::creating(function (Center $center): void {
            if (empty($center->slug)) {
                $center->slug = Str::slug($center->name);
            }
        });
    }
}
