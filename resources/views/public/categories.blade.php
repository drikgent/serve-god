@extends('layouts.app')

@section('content')
    <section class="section-block">
        <div class="section-heading">
            <div>
                <span class="eyebrow">Categories</span>
            </div>
        </div>

        <div class="category-grid">
            @foreach($categories as $category)
                @php
                    $latestPost = $category->latestPublishedPost;
                    $latestMedia = $latestPost?->media?->firstWhere('is_featured', true) ?? $latestPost?->media?->first();
                    $videoThumb = $latestMedia?->thumbnail_path
                        ?: (!\Illuminate\Support\Str::endsWith(\Illuminate\Support\Str::lower((string) $latestPost?->featured_media_url), ['.mp4', '.webm', '.mov', '.ogg']) ? $latestPost?->featured_media_url : null);
                    $categoryImage = $category->cover_url
                        ?: ($latestPost?->featured_media_type === 'video'
                            ? ($videoThumb ?: null)
                            : ($latestPost?->featured_media_url ?: null));
                @endphp
                <a href="{{ route('categories.show', $category) }}" class="category-card">
                    <img src="{{ $categoryImage ?: asset('placeholder.svg') }}" alt="{{ $category->name }}">
                    <div class="category-content">
                        <h3>{{ $category->name }}</h3>
                        @if($category->description)
                            <p>{{ $category->description }}</p>
                        @endif
                        @if($category->posts_count > 0)
                            <span>{{ $category->posts_count }} {{ \Illuminate\Support\Str::plural('published post', $category->posts_count) }}</span>
                        @endif
                    </div>
                </a>
            @endforeach
        </div>
    </section>
@endsection
