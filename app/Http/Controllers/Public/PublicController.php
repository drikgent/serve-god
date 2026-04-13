<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Post;
use App\Models\Tag;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Mail;

class PublicController extends Controller
{
    public function home(Request $request): View
    {
        return view('public.home', $this->feedData($request, true));
    }

    public function explore(Request $request): View
    {
        return view('public.explore', $this->feedData($request));
    }

    public function categories(): View
    {
        return view('public.categories', [
            'categories' => Category::withCount(['posts' => fn ($query) => $query->published()])
                ->with([
                    'latestPublishedPost' => fn ($query) => $query->with('media'),
                ])
                ->orderBy('name')
                ->get(),
        ]);
    }

    public function category(Category $category, Request $request): View
    {
        $search = $request->string('q')->toString();
        $tagSlug = $request->string('tag')->toString();
        $type = $request->string('type')->toString();

        $posts = Post::with(['author', 'category', 'tags', 'media'])
            ->published()
            ->whereBelongsTo($category)
            ->when($search, function ($query) use ($search) {
                $query->where(function ($inner) use ($search) {
                    $inner->where('title', 'like', "%{$search}%")
                        ->orWhere('caption', 'like', "%{$search}%")
                        ->orWhere('excerpt', 'like', "%{$search}%");
                });
            })
            ->when($tagSlug, fn ($query) => $query->whereHas('tags', fn ($tagQuery) => $tagQuery->where('slug', $tagSlug)))
            ->when(in_array($type, ['image', 'video'], true), fn ($query) => $query->where('content_type', $type))
            ->latest('published_at')
            ->paginate(12)
            ->withQueryString();

        return view('public.category', [
            'category' => $category,
            'posts' => $posts,
            'categories' => Category::orderBy('name')->get(),
            'popularTags' => Tag::withCount(['posts' => fn ($query) => $query->published()])
                ->has('posts')
                ->orderByDesc('posts_count')
                ->take(8)
                ->get(),
            'selectedCategory' => $category->slug,
            'selectedTag' => $tagSlug,
            'selectedType' => $type,
            'search' => $search,
        ]);
    }

    public function show(Post $post): View
    {
        abort_unless($post->status === 'published', 404);

        $post->increment('view_count');
        $post->load(['author', 'category', 'tags', 'media']);

        return view('public.post', [
            'post' => $post,
            'relatedPosts' => Post::with(['author', 'category', 'tags', 'media'])
                ->published()
                ->whereKeyNot($post->id)
                ->when($post->category_id, fn ($query) => $query->where('category_id', $post->category_id))
                ->latest('published_at')
                ->take(6)
                ->get(),
        ]);
    }

    public function about(): View
    {
        return view('public.about');
    }

    public function contact(): View
    {
        return view('public.contact');
    }

    public function sendContact(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:190'],
            'message' => ['required', 'string', 'max:5000'],
        ]);

        $subject = 'New website inquiry from '.$data['name'];
        $recipient = 'gaelakenneth106@gmail.com';
        $body = "Name: {$data['name']}\n"
            ."Email: {$data['email']}\n\n"
            ."Message:\n{$data['message']}";

        try {
            Mail::raw($body, function ($message) use ($recipient, $subject, $data) {
                $message
                    ->to($recipient)
                    ->subject($subject)
                    ->replyTo($data['email'], $data['name']);
            });
        } catch (\Throwable $exception) {
            report($exception);

            return back()
                ->withInput()
                ->withErrors([
                    'message' => 'Inquiry could not be sent right now. Please try again in a moment.',
                ]);
        }

        return back()->with('status', 'Inquiry sent successfully.');
    }

    private function feedData(Request $request, bool $featuredFirst = false): array
    {
        $search = $request->string('q')->toString();
        $categorySlug = $request->string('category')->toString();
        $tagSlug = $request->string('tag')->toString();
        $type = $request->string('type')->toString();

        $posts = Post::with(['author', 'category', 'tags', 'media'])
            ->published()
            ->when($featuredFirst, fn ($query) => $query->orderByDesc('is_featured'))
            ->when($search, function ($query) use ($search) {
                $query->where(function ($inner) use ($search) {
                    $inner->where('title', 'like', "%{$search}%")
                        ->orWhere('caption', 'like', "%{$search}%")
                        ->orWhere('excerpt', 'like', "%{$search}%")
                        ->orWhereHas('tags', fn ($tagQuery) => $tagQuery->where('name', 'like', "%{$search}%"))
                        ->orWhereHas('author', fn ($authorQuery) => $authorQuery->where('name', 'like', "%{$search}%"));
                });
            })
            ->when($categorySlug, fn ($query) => $query->whereHas('category', fn ($categoryQuery) => $categoryQuery->where('slug', $categorySlug)))
            ->when($tagSlug, fn ($query) => $query->whereHas('tags', fn ($tagQuery) => $tagQuery->where('slug', $tagSlug)))
            ->when(in_array($type, ['image', 'video'], true), fn ($query) => $query->where('content_type', $type))
            ->latest('published_at')
            ->paginate(15)
            ->withQueryString();

        return [
            'posts' => $posts,
            'featuredPosts' => Post::with(['author', 'category', 'media'])
                ->published()
                ->where('is_featured', true)
                ->latest('published_at')
                ->take(3)
                ->get(),
            'categories' => Category::withCount(['posts' => fn ($query) => $query->published()])
                ->orderBy('name')
                ->get(),
            'popularTags' => Tag::withCount(['posts' => fn ($query) => $query->published()])
                ->has('posts')
                ->orderByDesc('posts_count')
                ->take(8)
                ->get(),
            'search' => $search,
            'selectedCategory' => $categorySlug,
            'selectedTag' => $tagSlug,
            'selectedType' => $type,
        ];
    }
}
