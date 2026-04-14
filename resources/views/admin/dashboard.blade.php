@extends('layouts.admin')

@section('content')
    <section class="admin-header">
        <div>
            <span class="eyebrow admin-eyebrow">Dashboard</span>
        </div>
        <div class="admin-header-actions">
            <a href="{{ route('admin.posts.create') }}" class="primary-button admin-primary-button">Create Post</a>
        </div>
    </section>

    <section class="stats-grid admin-stats-grid">
        <article class="admin-card stat-contrast">
            <span>Published Posts</span>
            <strong>{{ $stats['published_posts'] }}</strong>
        </article>
        <article class="admin-card">
            <span>Drafts</span>
            <strong>{{ $stats['draft_posts'] }}</strong>
        </article>
        <article class="admin-card">
            <span>Media Items</span>
            <strong>{{ $stats['media_items'] }}</strong>
        </article>
        <article class="admin-card">
            <span>Admins</span>
            <strong>{{ $stats['admins'] }}</strong>
        </article>
    </section>

    <section class="admin-grid dashboard-grid">
        <article class="admin-panel dashboard-scroll-panel">
            <div class="section-heading">
                <h2>Recent posts</h2>
                <span class="helper-text">Latest publishing activity</span>
            </div>
            @forelse($recentPosts as $post)
                <div class="admin-list-row">
                    <div>
                        <strong>{{ $post->title }}</strong>
                        <span>{{ optional($post->category)->name ?? 'Unsorted' }} - {{ ucfirst($post->status) }}</span>
                    </div>
                    <a class="admin-inline-link" href="{{ route('admin.posts.edit', $post) }}">Edit</a>
                </div>
            @empty
                <p class="helper-text">No posts yet. Create your first one from the dashboard.</p>
            @endforelse
        </article>

        <article class="admin-panel dashboard-scroll-panel">
            <div class="section-heading">
                <h2>Latest media</h2>
                <span class="helper-text">Newest uploads</span>
            </div>
            @if($recentMedia->isNotEmpty())
                <div class="mini-media-grid">
                    @foreach($recentMedia as $medium)
                        <div class="mini-media-card">
                            <img src="{{ $medium->thumbnail_path ?: ($medium->type === 'image' ? $medium->file_path : asset('placeholder.svg')) }}" alt="{{ $medium->title }}">
                        </div>
                    @endforeach
                </div>
            @else
                <p class="helper-text">No uploaded media yet.</p>
            @endif
        </article>
    </section>
@endsection
