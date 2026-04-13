@extends('layouts.app')

@section('content')
    <article class="post-detail">
        <div class="detail-media">
            @foreach($post->media as $medium)
                <div class="detail-slide">
                    @if($medium->type === 'video')
                        <video src="{{ $medium->file_path }}" controls preload="metadata" poster="{{ $medium->thumbnail_path }}"></video>
                    @else
                        <img src="{{ $medium->file_path ?: asset('placeholder.svg') }}" alt="{{ $medium->alt_text ?: $post->title }}">
                    @endif
                </div>
            @endforeach
        </div>

        <aside class="detail-sidebar">
            <span class="eyebrow">{{ optional($post->category)->name ?? 'Story' }}</span>
            <h1>{{ $post->title }}</h1>
            <p class="detail-caption">{{ $post->caption }}</p>
            <div class="detail-meta">
                <span>By {{ $post->author->name }}</span>
                <span>{{ optional($post->published_at)->format('F d, Y') }}</span>
                @if($post->view_count > 0)
                    <span>{{ $post->view_count }} {{ \Illuminate\Support\Str::plural('view', $post->view_count) }}</span>
                @endif
            </div>
            <p>{{ $post->body }}</p>

            <div class="tag-cloud">
                @foreach($post->tags as $tag)
                    <a href="{{ route('explore', ['tag' => $tag->slug]) }}" class="tag-pill">#{{ $tag->name }}</a>
                @endforeach
            </div>

            @if($post->like_count > 0 || $post->save_count > 0 || $post->share_count > 0)
                <div class="detail-actions">
                    @if($post->like_count > 0)
                        <span>{{ $post->like_count }} {{ \Illuminate\Support\Str::plural('like', $post->like_count) }}</span>
                    @endif
                    @if($post->save_count > 0)
                        <span>{{ $post->save_count }} {{ \Illuminate\Support\Str::plural('save', $post->save_count) }}</span>
                    @endif
                    @if($post->share_count > 0)
                        <span>{{ $post->share_count }} {{ \Illuminate\Support\Str::plural('share', $post->share_count) }}</span>
                    @endif
                </div>
            @endif
        </aside>
    </article>

    @if($relatedPosts->isNotEmpty())
        <section class="section-block">
            <div class="section-heading">
                <h2>Related stories</h2>
            </div>
            <div class="featured-grid">
                @foreach($relatedPosts as $related)
                    <a href="{{ route('posts.show', $related) }}" class="featured-card compact">
                        @php
                            $relatedMedia = $related->media->firstWhere('is_featured', true) ?? $related->media->first();
                            $videoThumb = $relatedMedia?->thumbnail_path
                                ?: (!\Illuminate\Support\Str::endsWith(\Illuminate\Support\Str::lower((string) $related->featured_media_url), ['.mp4', '.webm', '.mov', '.ogg']) ? $related->featured_media_url : null);
                            $relatedUrl = $related->featured_media_type === 'video'
                                ? ($videoThumb ?: asset('placeholder.svg'))
                                : ($related->featured_media_url ?: asset('placeholder.svg'));
                        @endphp
                        <img src="{{ $relatedUrl }}" alt="{{ $related->title }}">
                        <div class="featured-overlay">
                            <h3>{{ $related->title }}</h3>
                        </div>
                    </a>
                @endforeach
            </div>
        </section>
    @endif
@endsection
