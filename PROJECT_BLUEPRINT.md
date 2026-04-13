# Personal Photo Blog Platform Blueprint

## 1. Complete Project Idea And Feature Breakdown

### Project concept
Build a public-facing photo and video blog platform for a personal brand or creative journal. The experience should combine:

- Pinterest-style discovery through a masonry feed
- Instagram-style post presentation and media-first storytelling
- A secure multi-admin dashboard for remote publishing and content management

### Product goals
- Publish beautiful visual stories for a public audience
- Make browsing fast, visual, and addictive across mobile and desktop
- Let multiple admins manage posts, media, and categories from anywhere
- Keep the platform scalable enough for future public release and growth

### Public features
- Masonry-style homepage with mixed image and video cards
- Explore page with discovery-focused feed
- Search for posts by title, caption, tag, category, and author
- Filter by categories and tags
- Post detail page with hero media, carousel support, metadata, and related posts
- Responsive cards with title, caption, category, author, date, and action buttons
- Save/share/like interaction layer
- Author profile snippet on posts
- SEO-friendly slugs and metadata
- Pagination or infinite scroll
- Optional dark mode

### Admin features
- Secure multi-admin authentication
- Role-based permissions:
  - super admin
  - editor/admin
- Dashboard overview with content stats
- Create, edit, publish, unpublish, and delete posts
- Draft and published status
- Upload images and videos
- Replace existing media files
- Media library with search and filters
- Category and tag management
- Admin account management
- Preview before publishing

### Optional phase-two features
- Comments and moderation
- Favorites/bookmarks
- Social login for admin accounts
- Scheduled publishing
- Analytics dashboard
- Email notifications for admin activity
- CDN and image transformation pipeline

## 2. Database Structure

Recommended database: MySQL 8+

### `admins`
- `id`
- `name`
- `username`
- `email`
- `password_hash`
- `avatar_path`
- `role_id`
- `is_active`
- `last_login_at`
- `created_at`
- `updated_at`

### `roles`
- `id`
- `name`
- `slug`
- `description`
- `created_at`
- `updated_at`

### `permissions`
- `id`
- `name`
- `slug`
- `created_at`
- `updated_at`

### `role_permissions`
- `id`
- `role_id`
- `permission_id`

### `posts`
- `id`
- `author_id`
- `title`
- `slug`
- `excerpt`
- `caption`
- `body`
- `cover_media_id`
- `content_type`
- `status`
- `visibility`
- `seo_title`
- `seo_description`
- `published_at`
- `created_at`
- `updated_at`
- `deleted_at`

### `post_media`
- `id`
- `post_id`
- `media_id`
- `sort_order`
- `is_featured`
- `created_at`

### `media`
- `id`
- `uploader_id`
- `type`
- `storage_disk`
- `file_path`
- `file_name`
- `mime_type`
- `file_size`
- `width`
- `height`
- `duration_seconds`
- `alt_text`
- `thumbnail_path`
- `optimized_path`
- `metadata_json`
- `created_at`
- `updated_at`

### `categories`
- `id`
- `name`
- `slug`
- `description`
- `cover_image_id`
- `created_at`
- `updated_at`

### `tags`
- `id`
- `name`
- `slug`
- `created_at`
- `updated_at`

### `post_categories`
- `id`
- `post_id`
- `category_id`

### `post_tags`
- `id`
- `post_id`
- `tag_id`

### `likes`
- `id`
- `post_id`
- `visitor_token`
- `ip_hash`
- `created_at`

### `bookmarks`
- `id`
- `post_id`
- `visitor_token`
- `created_at`

### `shares`
- `id`
- `post_id`
- `channel`
- `visitor_token`
- `created_at`

### `comments` (optional)
- `id`
- `post_id`
- `parent_id`
- `author_name`
- `author_email`
- `body`
- `status`
- `created_at`
- `updated_at`

### `admin_activity_logs`
- `id`
- `admin_id`
- `action`
- `entity_type`
- `entity_id`
- `payload_json`
- `created_at`

### Key relationships
- One admin creates many posts
- One post can have many media items
- One media item can be reused across posts if needed
- Posts can have many tags and many categories
- One role can have many permissions

## 3. Page Structure

### Public pages

#### Home
- Large discovery feed
- Featured categories
- Search bar
- Trending or latest posts

#### Explore
- Infinite visual feed
- Filter chips
- Sort by newest, popular, videos, images

#### Categories
- Category cards with cover images
- Entry point into niche collections

#### Category Detail
- Masonry feed limited to one category
- Breadcrumb and filters

#### Post Details
- Large media viewer
- Carousel for multi-image posts
- Caption, metadata, tags, share, save, like
- Related posts section

#### Search Results
- Keyword search with applied filters
- Mixed image/video results

#### About
- Personal brand story
- Mission, style, and publishing purpose

#### Contact
- Contact form
- Social profile links

### Admin pages

#### Admin Login
- Secure login
- Rate limiting
- Password reset

#### Dashboard
- Totals for posts, drafts, media, views, and admins
- Quick actions for new post and upload

#### Manage Posts
- Table/grid toggle
- Filter by status, category, author, media type

#### Create/Edit Post
- Title, slug, caption, body
- Drag-and-drop upload
- Preview before publish
- Category/tag selection
- Draft/publish controls

#### Media Library
- Upload queue
- Search and filter by image/video/date/uploader
- Replace or reuse files

#### Categories Manager
- CRUD for categories

#### Tags Manager
- CRUD for tags

#### Manage Admins
- Invite or create admins
- Assign roles
- Activate/deactivate accounts

#### Activity Logs
- Admin history for accountability

## 4. Admin Workflow

### Content publishing flow
1. Admin logs in securely.
2. Admin opens `Create Post`.
3. Admin uploads one or more images or a video.
4. System generates previews and thumbnails.
5. Admin adds title, caption, excerpt, tags, and categories.
6. Admin arranges media order and chooses the featured cover.
7. Admin saves draft or previews the post.
8. Admin publishes immediately or schedules later.

### Editing flow
1. Admin opens an existing post from `Manage Posts`.
2. Admin updates text, tags, categories, or media.
3. Admin can replace files while preserving the post URL.
4. System keeps audit logs for important changes.

### Media management flow
1. Admin uploads media to `Media Library`.
2. System stores original plus optimized versions.
3. Admin reuses existing media in future posts.
4. Admin updates alt text, thumbnails, and metadata.

### Admin management flow
1. Super admin creates or invites admins.
2. Super admin assigns `super_admin` or `editor`.
3. Editors manage content without full system control.
4. Sensitive actions are restricted to super admins.

## 5. UI/UX Description

### Visual identity
- Elegant, airy, editorial design
- Warm neutral background with soft contrast
- Rounded cards and layered surfaces
- High visual density without feeling cluttered

### Inspiration mix
- Pinterest:
  - staggered masonry discovery feed
  - category-driven exploration
  - endless browsing behavior
- Instagram:
  - immersive post viewer
  - polished media framing
  - modern interaction buttons and overlays

### Design language
- Soft card radius around `18px` to `24px`
- Subtle shadows and lifted hover states
- Thin borders with low-contrast neutrals
- Strong typography hierarchy with a refined headline font and clean body font
- Smooth fade, scale, and slide transitions

### Suggested palette
- Background: `#f6f3ee`
- Surface: `#fffdf9`
- Text primary: `#1e1b18`
- Text secondary: `#6d655d`
- Accent: `#d97757`
- Accent soft: `#f3d7c9`
- Success: `#4f7d64`

### UX priorities
- Fast scanning of visual content
- Very low friction for upload and publishing
- Consistent card layout with flexible media heights
- Sticky search and filter controls on mobile
- Detail pages that feel immersive, not blog-heavy

## 6. Recommended Tech Stack

### Best-fit stack for public release
- Backend: Laravel 12
- Frontend: Inertia.js + React + TypeScript
- Styling: Tailwind CSS
- Database: MySQL
- Authentication: Laravel Breeze or Jetstream
- Roles/permissions: Spatie Laravel Permission
- Media handling: Spatie Media Library
- Image optimization: Intervention Image or built-in conversion pipeline
- Video handling: direct upload plus FFmpeg-based thumbnail generation
- Search: Laravel Scout with Meilisearch or database fallback
- Storage: local during development, S3-compatible storage in production
- Queue: Redis
- Cache: Redis
- Analytics-ready: Plausible or GA-friendly event hooks

### Why this stack
- Laravel is secure, mature, and ideal for multi-admin CMS workflows
- Inertia + React gives a modern app-like admin UI without splitting frontend/backend too much
- MySQL fits your XAMPP-based environment and public hosting path
- Spatie packages speed up roles and media management in a production-safe way

### Deployment-ready direction
- Local development: XAMPP/MySQL + Laravel app
- Production: VPS or managed Laravel hosting
- CDN: Cloudflare
- Object storage: AWS S3, Cloudflare R2, or similar

## 7. Starter Code Structure

Recommended project structure if built with Laravel + Inertia + React:

```text
serve-god/
├─ app/
│  ├─ Actions/
│  │  ├─ Admin/
│  │  ├─ Media/
│  │  └─ Posts/
│  ├─ Http/
│  │  ├─ Controllers/
│  │  │  ├─ Admin/
│  │  │  ├─ Public/
│  │  │  └─ Auth/
│  │  ├─ Middleware/
│  │  └─ Requests/
│  ├─ Models/
│  │  ├─ Admin.php
│  │  ├─ Category.php
│  │  ├─ Comment.php
│  │  ├─ Media.php
│  │  ├─ Post.php
│  │  ├─ Role.php
│  │  └─ Tag.php
│  ├─ Policies/
│  └─ Services/
│     ├─ Media/
│     ├─ Posts/
│     └─ Search/
├─ bootstrap/
├─ config/
├─ database/
│  ├─ factories/
│  ├─ migrations/
│  └─ seeders/
├─ public/
│  ├─ images/
│  └─ uploads/
├─ resources/
│  ├─ js/
│  │  ├─ Components/
│  │  │  ├─ cards/
│  │  │  ├─ feed/
│  │  │  ├─ filters/
│  │  │  ├─ media/
│  │  │  └─ ui/
│  │  ├─ Layouts/
│  │  │  ├─ AdminLayout.tsx
│  │  │  └─ PublicLayout.tsx
│  │  ├─ Pages/
│  │  │  ├─ Admin/
│  │  │  │  ├─ Dashboard.tsx
│  │  │  │  ├─ MediaLibrary.tsx
│  │  │  │  ├─ Posts/
│  │  │  │  └─ Users/
│  │  │  ├─ Public/
│  │  │  │  ├─ About.tsx
│  │  │  │  ├─ Categories.tsx
│  │  │  │  ├─ Contact.tsx
│  │  │  │  ├─ Explore.tsx
│  │  │  │  ├─ Home.tsx
│  │  │  │  ├─ PostShow.tsx
│  │  │  │  └─ Search.tsx
│  │  ├─ hooks/
│  │  ├─ lib/
│  │  └─ types/
│  ├─ css/
│  │  ├─ app.css
│  │  └─ theme.css
│  └─ views/
├─ routes/
│  ├─ web.php
│  ├─ admin.php
│  └─ auth.php
├─ storage/
│  ├─ app/
│  │  ├─ media/
│  │  └─ public/
│  └─ logs/
├─ tests/
│  ├─ Feature/
│  └─ Unit/
├─ .env.example
├─ composer.json
├─ package.json
└─ README.md
```

## Suggested API / Route Groups

### Public routes
- `/`
- `/explore`
- `/categories`
- `/categories/{slug}`
- `/posts/{slug}`
- `/search`
- `/about`
- `/contact`

### Admin routes
- `/admin/login`
- `/admin/dashboard`
- `/admin/posts`
- `/admin/posts/create`
- `/admin/posts/{id}/edit`
- `/admin/media`
- `/admin/categories`
- `/admin/tags`
- `/admin/admins`
- `/admin/activity`

## Launch Readiness Checklist
- HTTPS enabled
- Admin auth hardened with rate limiting and secure sessions
- Role-based authorization tested
- Media optimization pipeline enabled
- SEO metadata per post
- Sitemap and robots setup
- Backup strategy for database and media
- Queue worker configured
- CDN and caching configured
- Image lazy loading and responsive sizes enabled

## Recommended Build Phases

### Phase 1
- Authentication
- Admin dashboard
- Post CRUD
- Image/video upload
- Public masonry homepage
- Post detail pages

### Phase 2
- Search and filters
- Media library improvements
- Roles and permissions
- Draft/publish workflow
- SEO and sharing metadata

### Phase 3
- Likes, bookmarks, comments
- Infinite scroll
- Analytics
- Performance tuning
- Dark mode

## Final Recommendation
If you want this to be genuinely public-release ready, build it as a custom Laravel CMS rather than a simple static gallery. That gives you:

- full admin control
- clean database-backed publishing
- strong authentication
- room to scale into a real media platform

The best next step is to scaffold the Laravel + React foundation and start with:

1. auth and roles
2. post/media schema
3. public feed UI
4. admin post creation flow
