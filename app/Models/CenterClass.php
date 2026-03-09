<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class CenterClass extends Model
{
    public const STATUS_NOT_STARTED = 'not_started';
    public const STATUS_IN_PROGRESS = 'in_progress';
    public const STATUS_PAUSED = 'paused';
    public const STATUS_COMPLETED = 'completed';

    protected $table = 'center_classes';

    protected $fillable = [
        'center_id',
        'course_id',
        'name',
        'slug',
        'description',
        'schedule',
        'hours_per_session',
        'sort_order',
        'is_active',
        'status',
    ];

    public static function statusOptions(): array
    {
        return [
            self::STATUS_NOT_STARTED => 'Chưa bắt đầu',
            self::STATUS_IN_PROGRESS => 'Đang học',
            self::STATUS_PAUSED => 'Đang tạm dừng',
            self::STATUS_COMPLETED => 'Hoàn thành',
        ];
    }

    public function getStatusLabelAttribute(): string
    {
        return self::statusOptions()[$this->status] ?? $this->status;
    }

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'hours_per_session' => 'float',
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
