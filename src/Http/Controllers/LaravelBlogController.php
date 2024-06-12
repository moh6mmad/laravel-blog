<?php

namespace Moh6mmad\LaravelBlog\Http\Controllers;

use Moh6mmad\LaravelBlog\Http\Models\LaravelBlog as Page;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\Controller;

class LaravelBlogController extends Controller
{
    /**
     * Display a listing of the blog pages.
     *
     * @return \Illuminate\Http\Response
     */
    public function blogIndex(Request $request)
    {
        $pages = Page::where('page_group', 'blog')
            ->where('content', '!=', '')
            ->where('status', '1');
        if (! empty($tag)) {
            $pages = $pages->where('tags', 'like', '%'.$tag.'%');
        }
        if (! empty($request->category)) {
            $pages = $pages->where('category_id', 'like', '%'.$request->category.'%');
        }

        $pages = $pages->orderBy('id', 'desc')->paginate(5);
        $page = [
            'title' => 'Blog posts',
            'description' => 'All blog articles about marketing & SEO',
        ];

        return view('blog.index', compact('pages', 'page'));
    }

    public function tag($tag)
    {
        $posts = [];
        $pages = Page::where('page_group', 'blog')->where('status', '1')
            ->where('tags', 'like', '%'.$tag.'%')
            ->orderBy('id', 'desc')->paginate(9);
        if (! count($pages)) {
            $posts = Page::inRandomOrder()->limit(2)->get();
        }
        $page = [
            'title' => 'Tag '.$tag,
            'description' => 'All blog articles with tag '.$tag,
        ];

        return view('blog.index', compact('pages', 'posts', 'page'));
    }

    public function category($category)
    {
        $posts = [];
        $pages = Page::where('page_group', 'blog')->where('status', '1')
            ->where('category_id', 'like', '%'.$category.'%')
            ->orderBy('id', 'desc')->paginate(9);
        if (! count($pages)) {
            $posts = Page::inRandomOrder()->limit(2)->get();
        }
        $page = [
            'title' => 'Blog posts in '.$category.' category',
            'description' => 'All blog articles with category: '.$category,
        ];

        return view('blog.index', compact('pages', 'posts', 'page'));
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
        $page->estimate_reading = $m.' minute'.($m == 1 ? '' : 's');
        $page->related_posts = Page::inRandomOrder()->limit(2)->get();
        $tags = explode(',', $page->tags) ?? null;
        Page::where('id', $page->id)->update(['views' => $page->views + 1]);

        return view('blog.show', compact('page', 'tags'));
    }

    public function blogShowById(int $id)
    {
        $blog = Page::where('status', '1')->where('id', $id)->firstOrFail();

        return redirect(route('blog.show', $blog->slug));
    }
    
    public function index(Request $request)
    {
        $data = Page::orderBy('id', 'DESC')->paginate(20);

        return view('admin.pages.index', compact('data'))
            ->with('i', ($request->input('page', 1) - 1) * 5);
    }
    
    public function getMediumAccountData(): string
    {
        $response = Http::withToken(env('MEDIUM_TOKEN'))->get('https://api.medium.com/v1/me');
        setting('auth.medium_account_id', $response->json()['data']['id']);

        return $response->json()['data']['id'];
    }

    public function publishOnMedium(Page $page)
    {
        $accountId = setting('auth.medium_account_id');
        if (empty($accountId)) {
            $accountId = $this->getMediumAccountData();
        }
        $page->content = preg_replace('/(?<!\S)#([0-9a-zA-Z]+)/', '<a href="'.env('APP_URL').'/blog/tag/$1">#$1</a>', $page->content);

        $response = Http::withToken(env('MEDIUM_TOKEN'))
            ->post('https://api.medium.com/v1/users/'.$accountId.'/posts', [
                'title' => $page->title,
                'contentFormat' => 'html',
                'content' => $page->content,
                'canonicalUrl' => env('APP_URL').'/blog/id/'.$page->id,
                'tags' => explode(',', $page->tags),
                'publishStatus' => 'public',
            ]);

        return $response->json();
    }

    public function sitemap()
    {
        $pages = Page::select('id', 'created_at', 'slug', 'tags')
            ->where('content', '!=', '')

            ->where('page_group', 'blog')->where('status', '1')
            ->orderBy('id', 'desc')->limit(100)->get();

        return response()->view('blog.sitemap', [
            'pages' => $pages,
        ])->header('Content-Type', 'text/xml');
    }
}
