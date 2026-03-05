<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RolePermission extends Model
{
    public $incrementing = false;

    protected $table = 'role_permissions';

    protected $fillable = ['role', 'permission_id'];

    protected $primaryKey = null;

    public function permission(): BelongsTo
    {
        return $this->belongsTo(Permission::class);
    }

    /**
     * Lấy danh sách permission_id mà role có.
     */
    public static function permissionIdsForRole(string $role): array
    {
        return static::where('role', $role)->pluck('permission_id')->toArray();
    }
}
