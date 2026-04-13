<section class="section-block">
    <div class="filter-bar">
        <form method="GET" action="{{ request()->routeIs('home') ? route('explore') : url()->current() }}" class="filter-form">
            <input type="search" name="q" value="{{ $search }}" placeholder="Search posts">
            <select name="category">
                <option value="">All categories</option>
                @foreach($categories as $category)
                    <option value="{{ $category->slug }}" @selected($selectedCategory === $category->slug)>{{ $category->name }}</option>
                @endforeach
            </select>
            <select name="type">
                <option value="">All media</option>
                <option value="image" @selected($selectedType === 'image')>Images</option>
                <option value="video" @selected($selectedType === 'video')>Videos</option>
            </select>
            <button type="submit">Filter</button>
        </form>

        @if($popularTags->isNotEmpty())
            <div class="tag-cloud">
                @foreach($popularTags as $tag)
                    <a href="{{ route('explore', ['tag' => $tag->slug]) }}" @class(['tag-pill', 'active' => $selectedTag === $tag->slug])>#{{ $tag->name }}</a>
                @endforeach
            </div>
        @endif
    </div>

    <div class="masonry">
        @forelse($posts as $post)
            <article class="post-card">
                <a href="{{ route('posts.show', $post) }}" class="media-frame">
                    @if($post->featured_media_type === 'video')
                        <div class="video-badge">Video</div>
                    @endif
                    @php
                        $featuredMedia = $post->media->firstWhere('is_featured', true) ?? $post->media->first();
                        $videoThumb = $featuredMedia?->thumbnail_path
                            ?: (!\Illuminate\Support\Str::endsWith(\Illuminate\Support\Str::lower((string) $post->featured_media_url), ['.mp4', '.webm', '.mov', '.ogg']) ? $post->featured_media_url : null);
                        $featuredUrl = $post->featured_media_type === 'video'
                            ? ($videoThumb ?: asset('placeholder.svg'))
                            : ($post->featured_media_url ?: asset('placeholder.svg'));
                    @endphp
                    <img src="{{ $featuredUrl }}" alt="{{ $post->title }}">
                </a>
                <div class="post-body">
                    <div class="meta-row">
                        <span class="meta-chip">{{ optional($post->category)->name ?? 'Unsorted' }}</span>
                        <span>{{ optional($post->published_at)->format('M d, Y') }}</span>
                    </div>
                    <h3><a href="{{ route('posts.show', $post) }}">{{ $post->title }}</a></h3>
                    <p>{{ $post->caption ?: $post->excerpt }}</p>
                    <div class="author-row">
                        <span>By {{ $post->author->name }}</span>
                        @if($post->like_count > 0)
                            <span>{{ $post->like_count }} {{ \Illuminate\Support\Str::plural('like', $post->like_count) }}</span>
                        @endif
                    </div>
                    @if($post->like_count > 0 || $post->save_count > 0 || $post->share_count > 0)
                        <div class="action-row">
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
                </div>
            </article>
        @empty
            <div class="empty-state">
                <h3>No posts yet.</h3>
                <p>This gallery will update automatically when admins publish images or videos.</p>
            </div>
        @endforelse
    </div>

    @if($posts->hasPages())
        <div class="pagination-wrap">
            {{ $posts->links() }}
        </div>
    @endif
</section>
