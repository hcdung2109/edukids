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
        'session_date',
        'note',
    ];

    protected function casts(): array
    {
        return [
            'session_date' => 'date',
        ];
    }

    public function centerClass(): BelongsTo
    {
        return $this->belongsTo(CenterClass::class);
    }

    public function attendances(): HasMany
    {
        return $this->hasMany(SessionAttendance::class, 'class_session_id');
    }
}
