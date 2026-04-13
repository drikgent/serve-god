@extends('layouts.admin')

@section('content')
    <section class="admin-header">
        <div>
            <span class="eyebrow admin-eyebrow">Media Library</span>
            <h1>Review</h1>
        </div>
    </section>

    <form method="GET" class="toolbar media-toolbar">
        <input type="search" name="q" value="{{ $search }}" placeholder="Search by media title or post">
        <select name="type">
            <option value="">All media</option>
            <option value="image" @selected($selectedType === 'image')>Images</option>
            <option value="video" @selected($selectedType === 'video')>Videos</option>
        </select>
        <button type="submit">Apply</button>
    </form>

    <div class="media-library-grid media-review-grid">
        @forelse($mediaItems as $item)
            <article class="admin-card media-library-card media-review-card">
                <div class="media-review-preview">
                    @if($item->type === 'video')
                        @php
                            $videoThumb = $item->thumbnail_path
                                ?: optional($item->post)->featured_media_url
                                ?: asset('placeholder.svg');
                            $videoThumb = \Illuminate\Support\Str::endsWith(\Illuminate\Support\Str::lower((string) $videoThumb), ['.mp4', '.webm', '.mov', '.ogg'])
                                ? asset('placeholder.svg')
                                : $videoThumb;
                        @endphp
                        <img src="{{ $videoThumb }}" alt="{{ $item->title ?: 'Video thumbnail' }}">
                    @else
                        <img src="{{ $item->file_path ?: asset('placeholder.svg') }}" alt="{{ $item->title }}">
                    @endif
                </div>

                <div class="media-review-copy">
                    <strong>{{ $item->title ?: 'Untitled media' }}</strong>
                    <span>{{ ucfirst($item->type) }} · {{ optional($item->post)->title ?? 'Detached media' }}</span>
                    @if($item->alt_text)
                        <p class="helper-text">{{ $item->alt_text }}</p>
                    @endif
                </div>

                <div class="media-review-actions">
                    <a href="{{ $item->file_path }}" target="_blank" rel="noreferrer" class="admin-inline-link">Open</a>

                    <form method="POST" action="{{ route('admin.media.update', $item) }}" enctype="multipart/form-data" class="media-inline-form">
                        @csrf
                        @method('PUT')
                        <input type="text" name="title" value="{{ $item->title }}" placeholder="Title">
                        <input type="text" name="alt_text" value="{{ $item->alt_text }}" placeholder="Alt text">
                        <input type="file" name="replacement_file">
                        <button type="submit" class="ghost-button media-action-button">Replace / Save</button>
                    </form>

                    <form method="POST" action="{{ route('admin.media.destroy', $item) }}">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="link-button danger">Delete</button>
                    </form>
                </div>
            </article>
        @empty
            <div class="empty-state">
                <h3>No media found.</h3>
                <p>Upload files through post creation and they will appear here for review and management.</p>
            </div>
        @endforelse
    </div>

    @if($mediaItems->hasPages())
        <div class="pagination-wrap">
            {{ $mediaItems->links() }}
        </div>
    @endif
@endsection
