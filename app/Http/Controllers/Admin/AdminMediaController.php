<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Media;
use App\Services\Media\CloudinaryMediaService;
use App\Services\Media\VideoThumbnailGenerator;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class AdminMediaController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->string('q')->toString();
        $type = $request->string('type')->toString();

        $mediaItems = Media::with(['post', 'uploader'])
            ->when($search, function ($query) use ($search) {
                $query->where(function ($inner) use ($search) {
                    $inner->where('title', 'like', "%{$search}%")
                        ->orWhere('alt_text', 'like', "%{$search}%")
                        ->orWhereHas('post', fn ($postQuery) => $postQuery->where('title', 'like', "%{$search}%"));
                });
            })
            ->when(in_array($type, ['image', 'video'], true), fn ($query) => $query->where('type', $type))
            ->latest()
            ->paginate(16)
            ->withQueryString();

        return view('admin.media.index', [
            'mediaItems' => $mediaItems,
            'search' => $search,
            'selectedType' => $type,
        ]);
    }

    public function update(Request $request, Media $media): RedirectResponse
    {
        $data = $request->validate([
            'title' => ['nullable', 'string', 'max:255'],
            'alt_text' => ['nullable', 'string', 'max:255'],
            'replacement_file' => ['nullable', 'file', 'max:51200'],
            'type' => ['nullable', Rule::in(['image', 'video'])],
        ]);

        if ($request->hasFile('replacement_file')) {
            $rawCurrentPath = $media->getRawOriginal('file_path');
            $this->deleteLocalFile($rawCurrentPath);

            $upload = $this->storeUploadedFile(
                $request->file('replacement_file'),
                app(CloudinaryMediaService::class)
            );
            $newType = $upload['type'];

            $media->file_path = $upload['file_path'];
            $media->type = $newType;
            $media->source = $upload['source'];

            if ($newType === 'video') {
                $this->deleteLocalFile($media->getRawOriginal('thumbnail_path'));
                $media->thumbnail_path = $upload['thumbnail_path'];
            } else {
                $media->thumbnail_path = null;
            }
        }

        $media->fill([
            'title' => $data['title'] ?? $media->title,
            'alt_text' => $data['alt_text'] ?? $media->alt_text,
            'type' => $data['type'] ?? $media->type,
        ])->save();

        if ($media->is_featured && $media->post) {
            $media->post->update([
                'featured_media_url' => $media->getRawOriginal('thumbnail_path') ?: $media->getRawOriginal('file_path'),
                'featured_media_type' => $media->type,
            ]);
        }

        return back()->with('status', 'Media updated.');
    }

    public function destroy(Media $media): RedirectResponse
    {
        $post = $media->post;
        $wasFeatured = $media->is_featured;

        $this->deleteLocalFile($media->getRawOriginal('file_path'));
        $this->deleteLocalFile($media->getRawOriginal('thumbnail_path'));

        $media->delete();

        if ($post && $wasFeatured) {
            $replacement = $post->media()->orderBy('sort_order')->first();

            $post->update([
                'featured_media_url' => $replacement?->getRawOriginal('thumbnail_path') ?: $replacement?->getRawOriginal('file_path'),
                'featured_media_type' => $replacement?->type,
            ]);

            if ($replacement && ! $replacement->is_featured) {
                $replacement->update(['is_featured' => true]);
            }
        }

        return back()->with('status', 'Media deleted.');
    }

    private function deleteLocalFile(?string $path): void
    {
        if (! $path || Str::startsWith($path, ['http://', 'https://', 'data:'])) {
            return;
        }

        $fullPath = public_path(ltrim($path, '/'));

        if (is_file($fullPath)) {
            @unlink($fullPath);
        }
    }

    private function storeUploadedFile(UploadedFile $file, CloudinaryMediaService $cloudinary): array
    {
        if ($cloudinary->enabled()) {
            try {
                return $cloudinary->upload($file);
            } catch (\Throwable $e) {
                Log::warning('Cloudinary upload failed, using local storage fallback.', [
                    'error' => $e->getMessage(),
                ]);
            }
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
