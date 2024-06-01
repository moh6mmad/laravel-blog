<?php

namespace Moh6mmad\LaravelBlog\Http\Controllers;

use App\Http\Requests\PageRequest;
use Moh6mmad\LaravelBlog\Http\Models\LaravelBlog as Page;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
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

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($slug)
    {
        $page = Page::where('slug', $slug)->firstOrFail();

        return view('blog.page', compact('page'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
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

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $data = Page::orderBy('id', 'DESC')->paginate(20);

        return view('admin.pages.index', compact('data'))
            ->with('i', ($request->input('page', 1) - 1) * 5);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.pages.page');
    }

    /**
     * Storing a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PageRequest $request)
    {
        $input = $request->all();
        $input['slug'] = Str::slug($request->title);
        if ($request->hasFile('primary_image')) {
            $input['primary_image'] = $input['slug'].'.'.$request->primary_image->getClientOriginalExtension();
            Storage::putFileAs('public/blog_images', $request->file('primary_image'), $input['primary_image']);
        }
        $page = Page::create($input);
        if ($request->filled('publish_on_medium')) {
            $data = $this->publishOnMedium($page);
            if (! empty($data['data']['id'])) {
                $page->update(['shared_id' => $data['data']['id']]);
            }
        }

        return redirect()->route('pages.index')
            ->with('success', 'Page created successfully');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(PageRequest $request, $id)
    {
        $page = Page::find($id);
        $input = $request->all();
        if ($request->hasFile('primary_image')) {
            $input['primary_image'] = $input['slug'].'.'.$request->primary_image->getClientOriginalExtension();
            Storage::putFileAs('public/blog_images', $request->file('primary_image'), $input['primary_image']);
        }
        $input['status'] = $request->filled('status') ? 1 : 0;
        $page->update($input);
        if ($request->filled('publish_on_medium')) {
            $data = $this->publishOnMedium($page);

            if (! empty($data['data']['id'])) {
                $page->update(['shared_id' => $data['data']['id']]);
            }
        }

        return redirect()->route('pages.index')
            ->with('success', 'Page updated successfully');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $page = Page::find($id);

        return view('admin.pages.page', compact('page'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Page::find($id)->delete();

        return redirect()->route('pages.index')
            ->with('success', 'Page deleted successfully');
    }

    public function legal($legalTerm)
    {
        $page = Page::where('slug', $legalTerm)->firstOrFail();

        return view('statics.legal', compact('page'));
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

    public function resources(Request $request)
    {
        $pages = Page::where('page_group', 'resources')->where('status', '1')->paginate(6);

        return view('blog.resources', compact('pages'));
    }

    public function services(Request $request)
    {
        $pages = Page::where('page_group', 'services')->where('status', '1')->paginate(6);

        return view('blog.services', compact('pages'));
    }
}
