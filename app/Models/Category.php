<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'accent_color',
        'cover_url',
    ];

    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }

    public function latestPublishedPost(): HasOne
    {
        return $this->hasOne(Post::class)
            ->where('status', 'published')
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now())
            ->latestOfMany('published_at');
    }
}
