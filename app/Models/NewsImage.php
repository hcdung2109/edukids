<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class NewsImage extends Model
{
    protected $fillable = ['news_id', 'path', 'sort_order'];

    public function news(): BelongsTo
    {
        return $this->belongsTo(News::class);
    }

    protected static function booted(): void
    {
        static::deleting(function (NewsImage $image): void {
            Storage::disk('public')->delete($image->path);
        });
    }
}
