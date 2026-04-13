@extends('layouts.app')

@section('content')
    @if($featuredPosts->isNotEmpty())
        <section class="section-block">
            <div class="section-heading">
                <h2>Featured stories</h2>
                <a href="{{ route('explore') }}">View all</a>
            </div>
            <div class="featured-grid">
                @foreach($featuredPosts as $post)
                    <a href="{{ route('posts.show', $post) }}" class="featured-card">
                        @php
                            $featuredMedia = $post->media->firstWhere('is_featured', true) ?? $post->media->first();
                            $videoThumb = $featuredMedia?->thumbnail_path
                                ?: (!\Illuminate\Support\Str::endsWith(\Illuminate\Support\Str::lower((string) $post->featured_media_url), ['.mp4', '.webm', '.mov', '.ogg']) ? $post->featured_media_url : null);
                            $featuredUrl = $post->featured_media_type === 'video'
                                ? ($videoThumb ?: asset('placeholder.svg'))
                                : ($post->featured_media_url ?: asset('placeholder.svg'));
                        @endphp
                        <img src="{{ $featuredUrl }}" alt="{{ $post->title }}">
                        <div class="featured-overlay">
                            <span>{{ optional($post->category)->name }}</span>
                            <h3>{{ $post->title }}</h3>
                            <p>{{ $post->excerpt }}</p>
                        </div>
                    </a>
                @endforeach
            </div>
        </section>
    @endif

    @include('public.partials.feed')
@endsection
