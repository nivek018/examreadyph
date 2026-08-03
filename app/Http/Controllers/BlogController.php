<?php

namespace App\Http\Controllers;

use App\Models\BlogPost;
use App\Models\BlogCategory;
use App\Models\BlogTag;

class BlogController extends Controller
{
    public function index()
    {
        $posts = BlogPost::published()
            ->with(['category', 'author'])
            ->latest('published_at')
            ->paginate(9);

        $categories = BlogCategory::where('is_active', true)
            ->withCount(['posts' => fn($q) => $q->published()])
            ->orderBy('sort_order')
            ->get();

        $featuredPosts = BlogPost::published()
            ->featured()
            ->with(['category', 'author'])
            ->latest('published_at')
            ->take(3)
            ->get();

        return view('blog.index', compact('posts', 'categories', 'featuredPosts'));
    }

    public function show(BlogPost $post)
    {
        if ($post->status !== 'published') {
            abort(404);
        }

        $post->increment('view_count');
        $post->load(['category', 'author', 'tags']);

        $relatedPosts = BlogPost::published()
            ->where('id', '!=', $post->id)
            ->where('category_id', $post->category_id)
            ->latest('published_at')
            ->take(3)
            ->get();

        $popularTags = BlogTag::withCount('posts')
            ->orderByDesc('posts_count')
            ->take(10)
            ->get();

        return view('blog.show', compact('post', 'relatedPosts', 'popularTags'));
    }

    public function category(BlogCategory $category)
    {
        $posts = BlogPost::published()
            ->where('category_id', $category->id)
            ->with(['category', 'author'])
            ->latest('published_at')
            ->paginate(9);

        $categories = BlogCategory::where('is_active', true)
            ->withCount(['posts' => fn($q) => $q->published()])
            ->orderBy('sort_order')
            ->get();

        return view('blog.index', [
            'posts' => $posts,
            'categories' => $categories,
            'featuredPosts' => collect(),
            'currentCategory' => $category,
        ]);
    }
}
