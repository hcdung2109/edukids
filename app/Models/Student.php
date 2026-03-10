<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Student extends Model
{
    protected $fillable = [
        'center_class_id',
        'name',
        'email',
        'phone',
        'date_of_birth',
        'class_name',
        'school_name',
        'parent_name',
        'parent_phone',
        'note',
        'tuition_paid',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'date_of_birth' => 'date',
            'tuition_paid' => 'boolean',
        ];
    }

    public function centerClass(): BelongsTo
    {
        return $this->belongsTo(CenterClass::class);
    }

    public function sessionAttendances(): HasMany
    {
        return $this->hasMany(SessionAttendance::class);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('name');
    }
}
