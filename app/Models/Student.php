<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Student extends Model
{
    protected $fillable = [
        'center_class_id',
        'name',
        'email',
        'phone',
        'date_of_birth',
        'parent_name',
        'parent_phone',
        'note',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'date_of_birth' => 'date',
        ];
    }

    public function centerClass(): BelongsTo
    {
        return $this->belongsTo(CenterClass::class);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('name');
    }
}
