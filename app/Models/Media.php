<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Media extends Model
{
    use HasFactory;

    protected $fillable = [
        'post_id',
        'uploader_id',
        'type',
        'title',
        'file_path',
        'thumbnail_path',
        'alt_text',
        'sort_order',
        'is_featured',
        'source',
    ];

    protected function casts(): array
    {
        return [
            'is_featured' => 'boolean',
        ];
    }

    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }

    public function uploader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploader_id');
    }

    public function getFilePathAttribute(?string $value): ?string
    {
        return $this->resolveMediaUrl($value);
    }

    public function getThumbnailPathAttribute(?string $value): ?string
    {
        return $this->resolveMediaUrl($value);
    }

    private function resolveMediaUrl(?string $path): ?string
    {
        if (! $path) {
            return null;
        }

        if (Str::startsWith($path, ['http://', 'https://', 'data:'])) {
            return $path;
        }

        $cleanPath = ltrim($path, '/');
        $request = request();

        if (! $request) {
            return url($cleanPath);
        }

        return rtrim($request->root(), '/').'/'.$cleanPath;
    }
}
