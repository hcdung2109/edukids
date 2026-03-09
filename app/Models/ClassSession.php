<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ClassSession extends Model
{
    protected $table = 'class_sessions';

    protected $fillable = [
        'center_class_id',
        'teacher_id',
        'session_date',
        'time_slot',
        'note',
        'hours_per_session',
    ];

    protected function casts(): array
    {
        return [
            'session_date' => 'date',
            'hours_per_session' => 'float',
        ];
    }

    /** Số giờ buổi học (nếu null thì lấy mặc định của lớp). */
    public function getEffectiveHoursPerSessionAttribute(): ?float
    {
        return $this->hours_per_session ?? $this->centerClass?->hours_per_session;
    }

    public function centerClass(): BelongsTo
    {
        return $this->belongsTo(CenterClass::class);
    }

    public function teacher(): BelongsTo
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function attendances(): HasMany
    {
        return $this->hasMany(SessionAttendance::class, 'class_session_id');
    }
}
