#!/usr/bin/env bash
set -euo pipefail

ROOT_DIR="${ROOT_DIR:-/opt/render/project/src}"
cd "$ROOT_DIR"

mkdir -p /var/data/uploads/images
mkdir -p /var/data/uploads/videos
mkdir -p /var/data/uploads/thumbnails

if [ -e public/uploads ] && [ ! -L public/uploads ]; then
  rm -rf public/uploads
fi

if [ ! -L public/uploads ]; then
  ln -s /var/data/uploads public/uploads
fi

php artisan migrate --force
php artisan config:cache
php artisan route:cache
php artisan view:cache

exec php artisan serve --host=0.0.0.0 --port="${PORT:-10000}"
