#!/usr/bin/env bash
set -euo pipefail

ROOT_DIR="${ROOT_DIR:-/opt/render/project/src}"
cd "$ROOT_DIR"

UPLOADS_DIR="${UPLOADS_DIR:-$ROOT_DIR/storage/app/public/uploads}"

mkdir -p "$UPLOADS_DIR/images"
mkdir -p "$UPLOADS_DIR/videos"
mkdir -p "$UPLOADS_DIR/thumbnails"

# If the repo already contains uploads, merge them into runtime uploads.
# This restores committed starter media while keeping runtime uploads.
if [ -d public/uploads ] && [ ! -L public/uploads ]; then
  cp -an public/uploads/. "$UPLOADS_DIR"/
fi

if [ -e public/uploads ] && [ ! -L public/uploads ]; then
  rm -rf public/uploads
fi

if [ ! -L public/uploads ]; then
  ln -s "$UPLOADS_DIR" public/uploads
fi

php artisan migrate --force
php artisan db:seed --class=Database\\Seeders\\DatabaseSeeder --force
php artisan media:generate-thumbnails

if [ "${CLOUDINARY_MIGRATE_ON_BOOT:-false}" = "true" ]; then
  php artisan media:migrate-to-cloudinary
fi

php artisan config:cache
php artisan route:cache
php artisan view:cache

exec php artisan serve --host=0.0.0.0 --port="${PORT:-10000}"
