<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    protected $fillable = ['name', 'label', 'group', 'sort_order'];

    public $timestamps = true;

    public static function ordered(): \Illuminate\Database\Eloquent\Builder
    {
        return static::query()->orderBy('group')->orderBy('sort_order')->orderBy('name');
    }
}
