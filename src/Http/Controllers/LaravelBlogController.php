<?php

namespace Moh6mmad\LaravelBlog\Http\Controllers;

use Moh6mmad\LaravelBlog\Http\Models\LaravelBlog as Page;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\Controller;
use Moh6mmad\LaravelBlog\Services\BlogService;

class LaravelBlogController extends Controller
{
    public function index(Request $request)
    {
        $pages = Page::where('page_group', 'blog')
            ->whereNotNull('content')
            ->active()
            ->when(!empty($request->filled('tag')), fn ($q) => $q->where('tags', 'like', '%' . $request->tag . '%'))
            ->when(!empty($request->filled('category')), fn ($q) => $q->where('category_id', $request->category))
            ->orderByDesc('id')->paginate(5);
        $page = [
            'title' => 'Blog posts',
            'description' => 'All blog articles about marketing & SEO',
        ];

        return view('larablog::index', compact('pages', 'page'));
    }

    public function tag($tag)
    {
        $posts = [];
        $pages = Page::where('page_group', 'blog')->where('status', '1')
            ->where('tags', 'like', '%' . $tag . '%')
            ->orderBy('id', 'desc')->paginate(9);
        if (!count($pages)) {
            $posts = Page::inRandomOrder()->limit(2)->get();
        }
        $page = [
            'title' => 'Tag ' . $tag,
            'description' => 'All blog articles with tag ' . $tag,
        ];

        return view('larablog::index', compact('pages', 'posts', 'page'));
    }

    public function category($category)
    {
        $posts = [];
        $pages = Page::where('page_group', 'blog')->where('status', '1')
            ->where('category_id', 'like', '%' . $category . '%')
            ->orderBy('id', 'desc')->paginate(9);
        if (!count($pages)) {
            $posts = Page::inRandomOrder()->limit(2)->get();
        }
        $page = [
            'title' => 'Blog posts in ' . $category . ' category',
            'description' => 'All blog articles with category: ' . $category,
        ];

        return view('larablog::index', compact('pages', 'posts', 'page'));
    }

    public function show($slug)
    {
        $page = Page::where('slug', $slug)->firstOrFail();
        return view('larablog::page', compact('page'));
    }

    public function blogShow($id, $slug)
    {
        $page = Page::where('id', $id)->firstOrFail();
        $page->content = preg_replace('/(?<!\S)#([0-9a-zA-Z]+)/', '<a href="/blog/tag/$1">#$1</a>', $page->content);
        $word = str_word_count(strip_tags($page->content));
        $m = floor($word / 200) + 1;
        $page->estimate_reading = $m . ' minute' . ($m == 1 ? '' : 's');
        $page->related_posts = Page::inRandomOrder()->limit(2)->get();
        $tags = explode(',', $page->tags) ?? null;
        Page::where('id', $page->id)->update(['views' => $page->views + 1]);

        return view('larablog::show', compact('page', 'tags'));
    }

    public function blogShowById(int $id)
    {
        $blog = Page::where('status', '1')->where('id', $id)->firstOrFail();

        return redirect(route('laravel-blog.show', $blog->slug));
    }

    

    public function sitemap()
    {
        return response()->view('larablog::sitemap', [
            'pages' => app(BlogService::class)->generateSitemap(),
        ])->header('Content-Type', 'text/xml');
    }
}
