<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

class CourseMaterial extends Model
{
    protected $table = 'course_materials';

    protected $fillable = [
        'course_id',
        'parent_id',
        'name',
        'type',
        'file_path',
        'mime_type',
        'file_size',
        'sort_order',
    ];

    public const TYPE_FOLDER = 'folder';
    public const TYPE_FILE = 'file';

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(CourseMaterial::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(CourseMaterial::class, 'parent_id')->orderBy('sort_order')->orderBy('name');
    }

    public function scopeOrdered($query)
    {
        return $query->orderByRaw("type = 'folder' DESC")->orderBy('sort_order')->orderBy('name');
    }

    public function isFolder(): bool
    {
        return $this->type === self::TYPE_FOLDER;
    }

    public function isFile(): bool
    {
        return $this->type === self::TYPE_FILE;
    }

    protected static function booted(): void
    {
        static::deleting(function (CourseMaterial $material): void {
            if ($material->isFile() && $material->file_path) {
                Storage::disk('public')->delete($material->file_path);
            }
        });
    }
}
