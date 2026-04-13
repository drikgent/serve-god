<?php

namespace App\Services\Media;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Http;
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
        if (! $this->enabled()) {
            throw new RuntimeException('Cloudinary credentials are not configured.');
        }

        $resourceType = $this->isVideo($file) ? 'video' : 'image';
        $timestamp = time();
        $folder = trim((string) config('cloudinary.folder', 'serve-god'), '/');

        $signingParams = [
            'timestamp' => $timestamp,
        ];

        if ($folder !== '') {
            $signingParams['folder'] = $folder;
        }

        $response = Http::asMultipart()
            ->acceptJson()
            ->post($this->uploadUrl($resourceType), [
                [
                    'name' => 'file',
                    'contents' => fopen($file->getRealPath(), 'r'),
                    'filename' => $file->getClientOriginalName(),
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

        if (! $response->successful()) {
            throw new RuntimeException('Cloudinary upload failed: '.$response->body());
        }

        $payload = $response->json();
        $fileUrl = (string) ($payload['secure_url'] ?? '');

        if ($fileUrl === '') {
            throw new RuntimeException('Cloudinary upload returned no secure_url.');
        }

        $thumbUrl = null;

        if ($resourceType === 'video') {
            $publicId = (string) ($payload['public_id'] ?? '');

            if ($publicId !== '') {
                $thumbUrl = sprintf(
                    'https://res.cloudinary.com/%s/video/upload/so_1/%s.jpg',
                    config('cloudinary.cloud_name'),
                    $publicId
                );
            }
        }

        return [
            'type' => $resourceType,
            'file_path' => $fileUrl,
            'thumbnail_path' => $thumbUrl,
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

    private function isVideo(UploadedFile $file): bool
    {
        $mime = (string) $file->getMimeType();

        if (Str::startsWith($mime, 'video/')) {
            return true;
        }

        return in_array(strtolower($file->getClientOriginalExtension()), ['mp4', 'webm', 'mov', 'ogg'], true);
    }
}
