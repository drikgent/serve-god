<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Post;
use App\Models\Tag;
use App\Services\Media\CloudinaryMediaService;
use App\Services\Media\VideoThumbnailGenerator;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class AdminPostController extends Controller
{
    public function index(Request $request): View
    {
        $status = $request->string('status')->toString();
        $category = $request->string('category')->toString();

        $posts = Post::with(['author', 'category', 'media'])
            ->when(in_array($status, ['draft', 'published'], true), fn ($query) => $query->where('status', $status))
            ->when($category, fn ($query) => $query->whereHas('category', fn ($categoryQuery) => $categoryQuery->where('slug', $category)))
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return view('admin.posts.index', [
            'posts' => $posts,
            'categories' => Category::orderBy('name')->get(),
            'status' => $status,
            'selectedCategory' => $category,
        ]);
    }

    public function create(): View
    {
        return view('admin.posts.form', [
            'post' => new Post([
                'status' => 'draft',
                'content_type' => 'image',
            ]),
            'categories' => Category::orderBy('name')->get(),
            'tags' => Tag::orderBy('name')->get(),
            'selectedTagIds' => [],
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validatedData($request);

        DB::transaction(function () use ($request, $data) {
            $post = Post::create([
                ...$data,
                'author_id' => $request->user()->id,
                'slug' => $this->makeSlug($data['title']),
                'published_at' => $data['status'] === 'published' ? now() : null,
            ]);

            $post->tags()->sync($request->input('tag_ids', []));
            $this->syncUploadedMedia($request, $post);
        });

        return redirect()->route('admin.posts.index')->with('status', 'Post created successfully.');
    }

    public function edit(Post $post): View
    {
        $post->load(['media', 'tags']);

        return view('admin.posts.form', [
            'post' => $post,
            'categories' => Category::orderBy('name')->get(),
            'tags' => Tag::orderBy('name')->get(),
            'selectedTagIds' => $post->tags->pluck('id')->all(),
        ]);
    }

    public function update(Request $request, Post $post): RedirectResponse
    {
        $data = $this->validatedData($request, $post);

        DB::transaction(function () use ($request, $post, $data) {
            $post->update([
                ...$data,
                'slug' => $this->makeSlug($data['title'], $post->id),
                'published_at' => $data['status'] === 'published'
                    ? ($post->published_at ?? now())
                    : null,
            ]);

            $post->tags()->sync($request->input('tag_ids', []));
            $this->syncUploadedMedia($request, $post);
            $post->refresh();
        });

        return redirect()->route('admin.posts.edit', $post)->with('status', 'Post updated successfully.');
    }

    public function destroy(Post $post): RedirectResponse
    {
        $post->delete();

        return redirect()->route('admin.posts.index')->with('status', 'Post deleted.');
    }

    private function validatedData(Request $request, ?Post $post = null): array
    {
        return $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'caption' => ['nullable', 'string'],
            'status' => ['required', Rule::in(['draft', 'published'])],
            'content_type' => ['required', Rule::in(['image', 'video'])],
            'category_id' => ['nullable', 'exists:categories,id'],
            'tag_ids' => ['nullable', 'array'],
            'tag_ids.*' => ['exists:tags,id'],
            'media_files' => ['nullable', 'array'],
            'media_files.*' => ['file', 'max:51200'],
        ]);
    }

    private function makeSlug(string $title, ?int $ignoreId = null): string
    {
        $base = Str::slug($title);
        if ($base === '') {
            $base = 'post';
        }

        $slug = $base;
        $count = 1;

        while (
            Post::where('slug', $slug)
                ->when($ignoreId, fn ($query) => $query->whereKeyNot($ignoreId))
                ->exists()
        ) {
            $slug = "{$base}-{$count}";
            $count++;
        }

        return $slug;
    }

    private function syncUploadedMedia(Request $request, Post $post): void
    {
        if (! $request->hasFile('media_files')) {
            return;
        }

        $cloudinary = app(CloudinaryMediaService::class);
        $existingCount = $post->media()->count();

        foreach ($request->file('media_files') as $index => $file) {
            $upload = $this->storeUploadedFile($file, $cloudinary);
            $type = $upload['type'];

            $media = $post->media()->create([
                'uploader_id' => $request->user()->id,
                'type' => $type,
                'title' => $post->title.' '.($existingCount + $index + 1),
                'file_path' => $upload['file_path'],
                'thumbnail_path' => $upload['thumbnail_path'],
                'alt_text' => $post->title,
                'sort_order' => $existingCount + $index,
                'is_featured' => $existingCount === 0 && $index === 0,
                'source' => $upload['source'],
            ]);

            if (! $post->featured_media_url) {
                $post->update([
                    'featured_media_url' => $type === 'video'
                        ? ($media->getRawOriginal('thumbnail_path') ?: $media->getRawOriginal('file_path'))
                        : $media->getRawOriginal('file_path'),
                    'featured_media_type' => $type,
                ]);
            }
        }
    }

    private function storeUploadedFile(UploadedFile $file, CloudinaryMediaService $cloudinary): array
    {
        if ($cloudinary->enabled()) {
            try {
                return $cloudinary->upload($file);
            } catch (\Throwable $e) {
                throw ValidationException::withMessages([
                    'media_files' => 'Cloudinary upload failed: '.$e->getMessage(),
                ]);
            }
        }

        if ($cloudinary->required()) {
            throw ValidationException::withMessages([
                'media_files' => 'Cloudinary is required but not configured. Set CLOUDINARY_CLOUD_NAME, CLOUDINARY_API_KEY, CLOUDINARY_API_SECRET.',
            ]);
        }

        $extension = strtolower($file->getClientOriginalExtension());
        $directory = in_array($extension, ['mp4', 'webm', 'mov', 'ogg'], true) ? 'videos' : 'images';
        $filename = Str::uuid().'.'.$extension;

        $file->move(public_path("uploads/{$directory}"), $filename);

        $relativePath = "uploads/{$directory}/{$filename}";
        $type = $directory === 'videos' ? 'video' : 'image';
        $thumbnailPath = null;

        if ($type === 'video') {
            $thumbnailPath = app(VideoThumbnailGenerator::class)->generate($relativePath);
        }

        return [
            'type' => $type,
            'file_path' => $relativePath,
            'thumbnail_path' => $thumbnailPath,
            'source' => 'upload',
        ];
    }
}
