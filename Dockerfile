FROM node:20-bookworm-slim AS node-build
WORKDIR /app

COPY package.json package-lock.json* ./
RUN if [ -f package-lock.json ]; then npm ci; else npm install; fi

COPY resources ./resources
COPY public ./public
COPY vite.config.js ./
RUN npm run build


FROM composer:2 AS composer-build
WORKDIR /app

COPY composer.json composer.lock ./
RUN composer install --no-dev --no-scripts --optimize-autoloader --no-interaction --prefer-dist


FROM php:8.3-cli-bookworm
WORKDIR /opt/render/project/src

RUN apt-get update && apt-get install -y --no-install-recommends \
    unzip \
    libpq-dev \
    && docker-php-ext-install -j"$(nproc)" pdo pdo_pgsql \
    && rm -rf /var/lib/apt/lists/*

RUN { \
    echo "upload_max_filesize=64M"; \
    echo "post_max_size=64M"; \
    echo "max_execution_time=120"; \
    echo "max_input_time=120"; \
    echo "memory_limit=256M"; \
  } > /usr/local/etc/php/conf.d/uploads.ini

COPY . .
COPY --from=composer-build /app/vendor ./vendor
COPY --from=node-build /app/public/build ./public/build

RUN chmod +x scripts/render-start.sh \
    && mkdir -p storage/framework/cache storage/framework/sessions storage/framework/views storage/framework/testing storage/logs bootstrap/cache

EXPOSE 10000

CMD ["bash", "scripts/render-start.sh"]
