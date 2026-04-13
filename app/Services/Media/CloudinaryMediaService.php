<?php

namespace App\Services\Media;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use RuntimeException;

class CloudinaryMediaService
{
    public function enabled(): bool
    {
        return filled(config('cloudinary.cloud_name'))
            && filled(config('cloudinary.api_key'))
            && filled(config('cloudinary.api_secret'));
    }

    public function upload(UploadedFile $file): array
    {
        return $this->uploadFromPath(
            $file->getRealPath(),
            $file->getClientOriginalName(),
            $this->isVideoFile($file->getClientOriginalName(), (string) $file->getMimeType())
        );
    }

    public function uploadFromLocal(string $absolutePath, ?string $originalName = null): array
    {
        if (! is_file($absolutePath)) {
            throw new RuntimeException("File not found for Cloudinary upload: {$absolutePath}");
        }

        $name = $originalName ?: basename($absolutePath);
        $mime = (string) File::mimeType($absolutePath);

        return $this->uploadFromPath(
            $absolutePath,
            $name,
            $this->isVideoFile($name, $mime)
        );
    }

    public function required(): bool
    {
        return (bool) config('cloudinary.required', false);
    }

    private function uploadFromPath(string $filePath, string $fileName, bool $isVideo): array
    {
        if (! $this->enabled()) {
            throw new RuntimeException('Cloudinary credentials are not configured.');
        }

        $resourceType = $isVideo ? 'video' : 'image';
        $timestamp = time();
        $folder = trim((string) config('cloudinary.folder', 'serve-god'), '/');
        $signingParams = ['timestamp' => $timestamp];

        if ($folder !== '') {
            $signingParams['folder'] = $folder;
        }

        $handle = fopen($filePath, 'r');

        if ($handle === false) {
            throw new RuntimeException('Unable to read file for Cloudinary upload.');
        }

        $response = Http::asMultipart()
            ->acceptJson()
            ->post($this->uploadUrl($resourceType), [
                [
                    'name' => 'file',
                    'contents' => $handle,
                    'filename' => $fileName,
                ],
                [
                    'name' => 'api_key',
                    'contents' => (string) config('cloudinary.api_key'),
                ],
                [
                    'name' => 'timestamp',
                    'contents' => (string) $timestamp,
                ],
                [
                    'name' => 'signature',
                    'contents' => $this->signature($signingParams),
                ],
                ...($folder !== '' ? [[
                    'name' => 'folder',
                    'contents' => $folder,
                ]] : []),
            ]);

        fclose($handle);

        if (! $response->successful()) {
            throw new RuntimeException('Cloudinary upload failed: '.$response->body());
        }

        $payload = $response->json();
        $fileUrl = (string) ($payload['secure_url'] ?? '');

        if ($fileUrl === '') {
            throw new RuntimeException('Cloudinary upload returned no secure_url.');
        }

        $thumbnailUrl = null;

        if ($resourceType === 'video') {
            $publicId = (string) ($payload['public_id'] ?? '');
            if ($publicId !== '') {
                $thumbnailUrl = sprintf(
                    'https://res.cloudinary.com/%s/video/upload/so_1/%s.jpg',
                    config('cloudinary.cloud_name'),
                    $publicId
                );
            }
        }

        return [
            'type' => $resourceType,
            'file_path' => $fileUrl,
            'thumbnail_path' => $thumbnailUrl,
            'source' => 'cloudinary',
        ];
    }

    private function uploadUrl(string $resourceType): string
    {
        return sprintf(
            'https://api.cloudinary.com/v1_1/%s/%s/upload',
            config('cloudinary.cloud_name'),
            $resourceType
        );
    }

    private function signature(array $params): string
    {
        ksort($params);
        $query = collect($params)
            ->map(fn ($value, $key) => $key.'='.$value)
            ->implode('&');

        return sha1($query.config('cloudinary.api_secret'));
    }

    private function isVideoFile(string $fileName, string $mime): bool
    {
        if (Str::startsWith($mime, 'video/')) {
            return true;
        }

        $extension = Str::lower(pathinfo($fileName, PATHINFO_EXTENSION));

        return in_array($extension, ['mp4', 'webm', 'mov', 'ogg'], true);
    }
}
