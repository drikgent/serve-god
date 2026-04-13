<?php

namespace App\Services\Media;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Symfony\Component\Process\Process;

class VideoThumbnailGenerator
{
    public function isAvailable(): bool
    {
        $process = new Process([$this->binary(), '-version']);
        $process->setTimeout(8);
        $process->run();

        return $process->isSuccessful();
    }

    public function generate(string $videoRelativePath): ?string
    {
        $videoFullPath = public_path(ltrim($videoRelativePath, '/'));

        if (! is_file($videoFullPath)) {
            return null;
        }

        $thumbDirRelative = trim(config('media.thumbnail_dir', 'uploads/thumbnails'), '/');
        $thumbDirFullPath = public_path($thumbDirRelative);

        if (! is_dir($thumbDirFullPath)) {
            @mkdir($thumbDirFullPath, 0777, true);
        }

        $outputFile = pathinfo($videoFullPath, PATHINFO_FILENAME).'-'.Str::random(8).'.jpg';
        $outputFullPath = $thumbDirFullPath.DIRECTORY_SEPARATOR.$outputFile;

        $process = new Process([
            $this->binary(),
            '-y',
            '-ss',
            (string) config('media.thumbnail_second', 1),
            '-i',
            $videoFullPath,
            '-frames:v',
            '1',
            '-q:v',
            '2',
            $outputFullPath,
        ]);

        $process->setTimeout(25);
        $process->run();

        if (! $process->isSuccessful() || ! is_file($outputFullPath)) {
            Log::warning('Unable to generate video thumbnail.', [
                'video' => $videoRelativePath,
                'error' => $process->getErrorOutput(),
            ]);

            return null;
        }

        return $thumbDirRelative.'/'.$outputFile;
    }

    private function binary(): string
    {
        return (string) config('media.ffmpeg_binary', 'ffmpeg');
    }
}
