<?php

namespace App\Console\Commands;

use App\Models\Media;
use App\Services\Media\CloudinaryMediaService;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class MigrateMediaToCloudinary extends Command
{
    protected $signature = 'media:migrate-to-cloudinary {--force : Re-upload even if media source is cloudinary}';

    protected $description = 'Migrate local media files and DB paths to Cloudinary URLs.';

    public function handle(CloudinaryMediaService $cloudinary): int
    {
        if (! $cloudinary->enabled()) {
            $this->error('Cloudinary is not configured. Set CLOUDINARY_* env vars first.');

            return self::FAILURE;
        }

        $items = Media::query()->orderBy('id')->get();
        $total = $items->count();
        $migrated = 0;
        $skipped = 0;
        $failed = 0;

        if ($total === 0) {
            $this->info('No media records found.');

            return self::SUCCESS;
        }

        $bar = $this->output->createProgressBar($total);
        $bar->start();

        foreach ($items as $media) {
            $bar->advance();

            $rawPath = (string) $media->getRawOriginal('file_path');

            if ($rawPath === '') {
                $skipped++;
                continue;
            }

            $alreadyCloudinary = Str::contains($rawPath, 'res.cloudinary.com');

            if ($alreadyCloudinary && ! $this->option('force')) {
                $skipped++;
                continue;
            }

            if (Str::startsWith($rawPath, ['http://', 'https://', 'data:']) && ! $alreadyCloudinary) {
                $skipped++;
                continue;
            }

            $absolute = public_path(ltrim(Str::after($rawPath, 'public/'), '/'));

            if (! is_file($absolute)) {
                $failed++;
                $this->newLine();
                $this->warn("Missing file for media #{$media->id}: {$absolute}");
                continue;
            }

            try {
                $upload = $cloudinary->uploadFromLocal($absolute, basename($absolute));

                $media->file_path = $upload['file_path'];
                $media->thumbnail_path = $upload['thumbnail_path'];
                $media->source = 'cloudinary';
                $media->save();

                if ($media->is_featured && $media->post) {
                    $media->post->update([
                        'featured_media_url' => $media->type === 'video'
                            ? ($upload['thumbnail_path'] ?: $upload['file_path'])
                            : $upload['file_path'],
                        'featured_media_type' => $media->type,
                    ]);
                }

                $migrated++;
            } catch (\Throwable $e) {
                $failed++;
                $this->newLine();
                $this->error("Failed media #{$media->id}: ".$e->getMessage());
            }
        }

        $bar->finish();
        $this->newLine(2);
        $this->info("Done. Migrated: {$migrated}, skipped: {$skipped}, failed: {$failed}");

        return $failed > 0 ? self::FAILURE : self::SUCCESS;
    }
}
