<?php

namespace Moh6mmad\LaravelBlog\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Moh6mmad\LaravelBlog\Http\Models\LaravelBlog as Page;
use Moh6mmad\LaravelBlog\Services\BlogService;

class LaravelBlogController extends Controller
{
    public function index(Request $request)
    {
        $pages = Page::where('page_group', 'blog')
            ->whereNotNull('content')
            ->active()
            ->when(!empty($request->route('tag')), fn ($q) => $q->where('tags', 'like', '%' . $request->tag . '%'))
            ->when(!empty($request->route('category')), fn ($q) => $q->where('category_id', $request->category))
            ->orderByDesc('id')->paginate(5);
        $page = [
            'title' => 'Blog posts',
            'description' => 'All blog articles about marketing & SEO',
        ];

        return view('larablog::index', compact('pages', 'page'));
    }

    public function show(Request $request, $slug, $id = null)
    {
        $type = $request->route('type');
        if ($type === 'page') {
            $page = Page::where('slug', $slug)->firstOrFail();
            return view('larablog::page', compact('page'));
        }
        $page = Page::where('id', $slug)->firstOrFail();
        $page->content = preg_replace('/(?<!\S)#([0-9a-zA-Z]+)/', '<a href="/blog/tag/$1">#$1</a>', $page->content);

        $page->related_posts = Page::inRandomOrder()->limit(2)->get();
        $tags = explode(',', $page->tags) ?? null;
        $page->increment('views');

        return view('larablog::show', compact('page', 'tags'));
    }

    public function sitemap()
    {
        return response()->view('larablog::sitemap', [
            'pages' => app(BlogService::class)->generateSitemap(),
        ])->header('Content-Type', 'text/xml');
    }
}
