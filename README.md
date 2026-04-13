# Serve God Studio

A Laravel-based personal photo and video blog starter with:

- a public Pinterest-inspired discovery feed
- Instagram-style post detail pages
- multi-admin login and dashboard
- post, media, category, tag, and admin management starter flows

## Demo admin accounts

- `admin@servegod.test` / `password`
- `editor@servegod.test` / `password`

## Local setup

1. `C:\xampp\php\php.exe artisan migrate:fresh --seed`
2. `C:\xampp\php\php.exe artisan serve`
3. Open `http://127.0.0.1:8000`

## Included pages

- `/`
- `/explore`
- `/categories`
- `/posts/{slug}`
- `/about`
- `/contact`
- `/admin/login`
- `/admin/dashboard`
- `/admin/posts`
- `/admin/media`
- `/admin/admins`

## Notes

- Uploaded media is stored in `public/uploads`.
- The seeded demo content uses remote image/video URLs so the interface looks populated immediately.
- The original planning document is in `PROJECT_BLUEPRINT.md`.
- For automatic video thumbnails, install FFmpeg and set `FFMPEG_PATH` in `.env` (for Windows, example: `FFMPEG_PATH=C:\ffmpeg\bin\ffmpeg.exe`).
- Generate thumbnails for existing videos with `C:\xampp\php\php.exe artisan media:generate-thumbnails`.

## Deploy on Render

This repo now includes a `render.yaml` blueprint for one Laravel web service plus one PostgreSQL database.

1. Push this project to GitHub.
2. In Render, click **New +** > **Blueprint**.
3. Select your repo and deploy. Render will read `render.yaml` automatically.
4. In the created web service, set:
   - `APP_KEY` (required): generate locally with `C:\xampp\php\php.exe artisan key:generate --show`
   - `APP_URL` (required): your Render URL, for example `https://serve-god-web.onrender.com`
5. Redeploy the web service once those variables are saved.

### Important behavior on Render

- Startup runs migrations automatically (`php artisan migrate --force`).
- Startup seeds admin/demo records (`php artisan db:seed --class=Database\\Seeders\\DatabaseSeeder --force`).
- Sessions/cache default to file storage in production for first-time simplicity.

### Cloudinary media storage (recommended on free plan)

Render free services do not persist local uploads reliably. To make media permanent, set:

- `CLOUDINARY_CLOUD_NAME`
- `CLOUDINARY_API_KEY`
- `CLOUDINARY_API_SECRET`
- `CLOUDINARY_FOLDER` (optional, default: `serve-god`)

When these are set, new image/video uploads are stored on Cloudinary automatically.

### Optional: import your local SQL dump

If you want your existing data in Render Postgres:

1. Open Render database shell/connection details.
2. Import `serve_god_db.sql` into the Render database.
3. Redeploy the web service.
