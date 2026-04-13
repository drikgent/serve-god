<?php

$cloudinaryUrl = (string) env('CLOUDINARY_URL', '');
$parsed = $cloudinaryUrl !== '' ? parse_url($cloudinaryUrl) : null;
$fromUrlCloudName = $parsed['host'] ?? null;
$fromUrlApiKey = $parsed['user'] ?? null;
$fromUrlApiSecret = $parsed['pass'] ?? null;

return [
    'cloud_name' => env('CLOUDINARY_CLOUD_NAME', $fromUrlCloudName),
    'api_key' => env('CLOUDINARY_API_KEY', $fromUrlApiKey),
    'api_secret' => env('CLOUDINARY_API_SECRET', $fromUrlApiSecret),
    'folder' => env('CLOUDINARY_FOLDER', 'serve-god'),
    'required' => env('CLOUDINARY_REQUIRED', false),
];
