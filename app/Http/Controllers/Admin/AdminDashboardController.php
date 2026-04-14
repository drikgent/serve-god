<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Media;
use App\Models\Post;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Throwable;

class AdminDashboardController extends Controller
{
    public function __invoke(): View
    {
        $stats = [
            'published_posts' => 0,
            'draft_posts' => 0,
            'media_items' => 0,
            'admins' => 0,
        ];
        $recentPosts = collect();
        $recentMedia = collect();

        try {
            $stats = [
                'published_posts' => Post::where('status', 'published')->count(),
                'draft_posts' => Post::where('status', 'draft')->count(),
                'media_items' => Media::count(),
                'admins' => User::whereIn('role', ['super_admin', 'editor'])->count(),
            ];
            $recentPosts = Post::with(['author', 'category'])->latest()->take(6)->get();
            $recentMedia = Media::with('post')->latest()->take(8)->get();
        } catch (Throwable $e) {
            report($e);
        }

        return view('admin.dashboard', [
            'stats' => $stats,
            'recentPosts' => $recentPosts,
            'recentMedia' => $recentMedia,
        ]);
    }
}
