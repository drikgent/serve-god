<?php

namespace App\Console\Commands;

use App\Models\Media;
use App\Services\Media\VideoThumbnailGenerator;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class GenerateVideoThumbnails extends Command
{
    protected $signature = 'media:generate-thumbnails {--force : Regenerate even if thumbnail exists}';

    protected $description = 'Generate missing thumbnails for video media items using FFmpeg.';

    public function handle(VideoThumbnailGenerator $generator): int
    {
        if (! $generator->isAvailable()) {
            $this->error('FFmpeg is not available. Set FFMPEG_PATH in .env to your ffmpeg.exe path.');

            return self::FAILURE;
        }

        $videos = Media::query()
            ->where('type', 'video')
            ->when(! $this->option('force'), fn ($query) => $query->whereNull('thumbnail_path'))
            ->get();

        if ($videos->isEmpty()) {
            $this->info('No video media items need thumbnails.');

            return self::SUCCESS;
        }

        $generated = 0;

        foreach ($videos as $media) {
            $thumb = $generator->generate($media->getRawOriginal('file_path'));

            if (! $thumb) {
                continue;
            }

            $media->thumbnail_path = $thumb;
            $media->save();

            if ($media->is_featured && $media->post) {
                $featured = $media->post->getRawOriginal('featured_media_url');
                $needsUpdate = ! $featured || Str::endsWith(Str::lower($featured), ['.mp4', '.webm', '.mov', '.ogg']);

                if ($needsUpdate || $this->option('force')) {
                    $media->post->update([
                        'featured_media_url' => $thumb,
                        'featured_media_type' => 'video',
                    ]);
                }
            }

            $generated++;
        }

        $this->info("Generated thumbnails: {$generated}");

        return self::SUCCESS;
    }
}
