<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class News extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'excerpt',
        'body',
        'image',
        'is_published',
        'published_at',
    ];

    protected function casts(): array
    {
        return [
            'is_published' => 'boolean',
            'published_at' => 'datetime',
        ];
    }

    public function scopePublished($query)
    {
        return $query->where('is_published', true)
            ->where(function ($q) {
                $q->whereNull('published_at')->orWhere('published_at', '<=', now());
            });
    }

    public function images(): HasMany
    {
        return $this->hasMany(NewsImage::class)->orderBy('sort_order');
    }

    protected static function booted(): void
    {
        static::creating(function (News $news): void {
            if (empty($news->slug)) {
                $news->slug = Str::slug($news->title);
            }
            if ($news->is_published && ! $news->published_at) {
                $news->published_at = now();
            }
        });

        static::deleting(function (News $news): void {
            foreach ($news->images as $img) {
                $img->delete();
            }
            if ($news->image) {
                Storage::disk('public')->delete($news->image);
            }
        });
    }
}
