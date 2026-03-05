<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $fillable = ['name', 'label', 'description', 'is_system', 'sort_order'];

    protected $casts = [
        'is_system' => 'boolean',
    ];

    /**
     * Scope sắp xếp theo sort_order rồi name (có thể chain: Role::where(...)->ordered()).
     */
    public function scopeOrdered($query): \Illuminate\Database\Eloquent\Builder
    {
        return $query->orderBy('sort_order')->orderBy('name');
    }

    public function usersCount(): int
    {
        return \App\Models\User::where('role', $this->name)->count();
    }
}
