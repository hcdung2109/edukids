<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class CenterClass extends Model
{
    protected $table = 'center_classes';

    protected $fillable = [
        'center_id',
        'course_id',
        'name',
        'slug',
        'description',
        'schedule',
        'sort_order',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function center(): BelongsTo
    {
        return $this->belongsTo(Center::class);
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function students(): HasMany
    {
        return $this->hasMany(Student::class, 'center_class_id')->ordered();
    }

    public function classSessions(): HasMany
    {
        return $this->hasMany(ClassSession::class)->orderBy('session_date');
    }

    public function teachers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'center_class_teacher', 'center_class_id', 'user_id')
            ->withTimestamps();
    }

    public function learningTools(): HasMany
    {
        return $this->hasMany(LearningTool::class, 'center_class_id');
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
        static::creating(function (CenterClass $centerClass): void {
            if (empty($centerClass->slug)) {
                $centerClass->slug = Str::slug($centerClass->name);
            }
        });
    }
}
