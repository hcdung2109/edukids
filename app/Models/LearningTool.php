<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LearningTool extends Model
{
    protected $table = 'learning_tools';

    protected $fillable = [
        'name',
        'quantity',
        'note',
        'center_id',
        'center_class_id',
        'managed_by_user_id',
    ];

    protected $casts = [
        'quantity' => 'integer',
    ];

    public function center(): BelongsTo
    {
        return $this->belongsTo(Center::class);
    }

    public function centerClass(): BelongsTo
    {
        return $this->belongsTo(CenterClass::class, 'center_class_id');
    }

    public function managedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'managed_by_user_id');
    }
}
