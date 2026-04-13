<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'author_id',
        'category_id',
        'title',
        'slug',
        'excerpt',
        'caption',
        'body',
        'status',
        'content_type',
        'featured_media_url',
        'featured_media_type',
        'published_at',
        'view_count',
        'like_count',
        'save_count',
        'share_count',
        'is_featured',
    ];

    protected function casts(): array
    {
        return [
            'published_at' => 'datetime',
            'is_featured' => 'boolean',
        ];
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class)->withTimestamps();
    }

    public function media(): HasMany
    {
        return $this->hasMany(Media::class)->orderBy('sort_order');
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query
            ->where('status', 'published')
            ->whereNotNull('published_at')
            ->whereNotNull('slug')
            ->where('slug', '!=', '')
            ->where('published_at', '<=', now());
    }

    public function getFeaturedMediaUrlAttribute(?string $value): ?string
    {
        return $this->resolveMediaUrl($value, (string) $this->featured_media_type);
    }

    private function resolveMediaUrl(?string $path, string $mediaType = ''): ?string
    {
        if (! $path) {
            return null;
        }

        if (Str::startsWith($path, ['http://', 'https://', 'data:'])) {
            return $path;
        }

        $cleanPath = ltrim($path, '/');

        if (Str::startsWith($cleanPath, 'public/uploads/')) {
            $cleanPath = Str::after($cleanPath, 'public/');
        }

        if (! Str::contains($cleanPath, '/')) {
            if ($mediaType === 'video') {
                $cleanPath = 'uploads/thumbnails/'.$cleanPath;
            } else {
                $extension = Str::lower(pathinfo($cleanPath, PATHINFO_EXTENSION));
                $folder = in_array($extension, ['mp4', 'webm', 'mov', 'ogg'], true) ? 'videos' : 'images';
                $cleanPath = 'uploads/'.$folder.'/'.$cleanPath;
            }
        }

        $request = request();

        if (! $request) {
            return url($cleanPath);
        }

        return rtrim($request->root(), '/').'/'.$cleanPath;
    }
}
