<?php

return [
    'ffmpeg_binary' => env('FFMPEG_PATH', 'ffmpeg'),
    'thumbnail_second' => (int) env('FFMPEG_THUMBNAIL_SECOND', 1),
    'thumbnail_dir' => env('FFMPEG_THUMBNAIL_DIR', 'uploads/thumbnails'),
];
