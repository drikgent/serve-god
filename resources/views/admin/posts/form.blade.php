@extends('layouts.admin')

@section('content')
    <section class="admin-header">
        <div>
            <span class="eyebrow">{{ $post->exists ? 'Edit Post' : 'Create Post' }}</span>
            @if($post->exists)
                <h1>{{ $post->title }}</h1>
            @endif
        </div>
    </section>

    <form method="POST" enctype="multipart/form-data" action="{{ $post->exists ? route('admin.posts.update', $post) : route('admin.posts.store') }}" class="editor-grid">
        @csrf
        @if($post->exists)
            @method('PUT')
        @endif

        <div class="admin-panel">
            @if($errors->any())
                <div class="flash error-text">
                    {{ $errors->first() }}
                </div>
            @endif

            <label>Title</label>
            <input type="text" name="title" value="{{ old('title', $post->title) }}" required>

            <label>Caption</label>
            <textarea name="caption" rows="4">{{ old('caption', $post->caption) }}</textarea>
        </div>

        <div class="admin-panel">
            <label>Status</label>
            <select name="status">
                <option value="draft" @selected(old('status', $post->status) === 'draft')>Draft</option>
                <option value="published" @selected(old('status', $post->status) === 'published')>Published</option>
            </select>

            <label>Media type</label>
            <select name="content_type">
                <option value="image" @selected(old('content_type', $post->content_type) === 'image')>Image</option>
                <option value="video" @selected(old('content_type', $post->content_type) === 'video')>Video</option>
            </select>

            <label>Category</label>
            <select name="category_id">
                <option value="">Unsorted</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" @selected((string) old('category_id', $post->category_id) === (string) $category->id)>{{ $category->name }}</option>
                @endforeach
            </select>

            <label>Tags</label>
            <select name="tag_ids[]" multiple size="6">
                @foreach($tags as $tag)
                    <option value="{{ $tag->id }}" @selected(in_array($tag->id, old('tag_ids', $selectedTagIds), true))>{{ $tag->name }}</option>
                @endforeach
            </select>

            <label>Upload images or videos</label>
            <input type="file" id="media_files" name="media_files[]" multiple accept="image/*,video/*">
            @error('media_files')
                <p class="error-text">{{ $message }}</p>
            @enderror
            @error('media_files.*')
                <p class="error-text">{{ $message }}</p>
            @enderror
            <div id="media-upload-preview" class="upload-preview-grid"></div>

            <button type="submit" class="primary-button full-width">{{ $post->exists ? 'Save changes' : 'Publish draft' }}</button>
        </div>
    </form>

    @if($post->exists && $post->media->isNotEmpty())
        <section class="admin-panel">
            <div class="section-heading"><h2>Current media</h2></div>
            <div class="mini-media-grid">
                @foreach($post->media as $medium)
                    <div class="mini-media-card">
                        <img src="{{ $medium->thumbnail_path ?: ($medium->type === 'image' ? $medium->file_path : asset('placeholder.svg')) }}" alt="{{ $medium->title }}">
                    </div>
                @endforeach
            </div>
        </section>
    @endif

    <script>
        (() => {
            const input = document.getElementById('media_files');
            const previewGrid = document.getElementById('media-upload-preview');
            if (!input || !previewGrid) return;
            let currentUrls = [];

            input.addEventListener('change', () => {
                currentUrls.forEach((url) => URL.revokeObjectURL(url));
                currentUrls = [];
                previewGrid.innerHTML = '';
                const files = Array.from(input.files || []);

                files.forEach((file) => {
                    const url = URL.createObjectURL(file);
                    currentUrls.push(url);
                    const item = document.createElement('div');
                    item.className = 'upload-preview-item';

                    if (file.type.startsWith('video/')) {
                        const video = document.createElement('video');
                        video.src = url;
                        video.controls = true;
                        video.preload = 'metadata';
                        item.appendChild(video);
                    } else if (file.type.startsWith('image/')) {
                        const img = document.createElement('img');
                        img.src = url;
                        img.alt = file.name;
                        item.appendChild(img);
                    }

                    const label = document.createElement('span');
                    label.textContent = file.name;
                    item.appendChild(label);
                    previewGrid.appendChild(item);
                });
            });
        })();
    </script>
@endsection
