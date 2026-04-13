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
    ffmpeg \
    git \
    unzip \
    libicu-dev \
    libpq-dev \
    libzip-dev \
    && docker-php-ext-install -j"$(nproc)" bcmath intl mbstring pdo pdo_pgsql zip \
    && rm -rf /var/lib/apt/lists/*

COPY . .
COPY --from=composer-build /app/vendor ./vendor
COPY --from=node-build /app/public/build ./public/build

RUN chmod +x scripts/render-start.sh \
    && mkdir -p storage/framework/cache storage/framework/sessions storage/framework/views storage/framework/testing storage/logs bootstrap/cache

EXPOSE 10000

CMD ["bash", "scripts/render-start.sh"]
