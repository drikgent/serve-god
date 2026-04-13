@extends('layouts.admin')

@section('content')
    <section class="admin-header">
        <div>
            <span class="eyebrow admin-eyebrow">Manage Posts</span>
        </div>
        <a href="{{ route('admin.posts.create') }}" class="primary-button">New Post</a>
    </section>

    <form method="GET" class="toolbar">
        <select name="status">
            <option value="">All statuses</option>
            <option value="draft" @selected($status === 'draft')>Draft</option>
            <option value="published" @selected($status === 'published')>Published</option>
        </select>
        <select name="category">
            <option value="">All categories</option>
            @foreach($categories as $category)
                <option value="{{ $category->slug }}" @selected($selectedCategory === $category->slug)>{{ $category->name }}</option>
            @endforeach
        </select>
        <button type="submit">Apply</button>
    </form>

    <div class="admin-panel">
        @foreach($posts as $post)
            <div class="admin-list-row">
                <div class="list-inline">
                    @php
                        $featuredMedia = $post->media->firstWhere('is_featured', true) ?? $post->media->first();
                        $videoThumb = $featuredMedia?->thumbnail_path
                            ?: (!\Illuminate\Support\Str::endsWith(\Illuminate\Support\Str::lower((string) $post->featured_media_url), ['.mp4', '.webm', '.mov', '.ogg']) ? $post->featured_media_url : null);
                        $thumbUrl = $post->featured_media_type === 'video'
                            ? ($videoThumb ?: asset('placeholder.svg'))
                            : ($post->featured_media_url ?: asset('placeholder.svg'));
                    @endphp
                    <img src="{{ $thumbUrl }}" alt="{{ $post->title }}" class="thumb">
                    <div>
                        <strong>{{ $post->title }}</strong>
                        <span>{{ ucfirst($post->status) }} · {{ optional($post->category)->name ?? 'Unsorted' }} · {{ $post->author->name }}</span>
                    </div>
                </div>
                <div class="row-actions">
                    <a href="{{ route('admin.posts.edit', $post) }}">Edit</a>
                    <form method="POST" action="{{ route('admin.posts.destroy', $post) }}">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="link-button danger">Delete</button>
                    </form>
                </div>
            </div>
        @endforeach
    </div>

    <div class="pagination-wrap">
        {{ $posts->links() }}
    </div>
@endsection
