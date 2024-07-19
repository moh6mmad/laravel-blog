<?php

namespace Moh6mmad\LaravelBlog\Services;

use Illuminate\Support\Facades\Http;
use Moh6mmad\LaravelBlog\Http\Models\LaravelBlog as Page;
use OpenAI;

class BlogService
{
    public function getMediumAccountData(): string
    {
        $response = Http::withToken(config('laravel-blog.medium.token'))->get('https://api.medium.com/v1/me');
        setting('auth.medium_account_id', $response->json()['data']['id']);

        return $response->json()['data']['id'];
    }

    public function generateContentByOpenAi(?Page $page, $model = 'gpt-4-turbo', $role = 'user')
    {
        if (empty(config('laravel-blog.openai.api_key')) || ! $page->generate_by_ai) {
            return;
        }

        $client = OpenAI::client(config('laravel-blog.openai.api_key'));
        $result = $client->chat()->create([
            'model' => $model,
            'messages' => [
                ['role' => $role, 'content' => config('laravel-blog.openai.prompt').$page->title],
            ],
        ]);

        $page->content = $result->choices[0]->message->content;
        $page->generate_by_ai = false;

        $page->save();
    }

    public function publishOnMedium(Page $page)
    {
        $accountId = config('laravel-blog.medium.account_id') ?? $this->getMediumAccountData();
        $page->content = preg_replace('/(?<!\S)#([0-9a-zA-Z]+)/', '<a href="'.env('APP_URL').'/blog/tag/$1">#$1</a>', $page->content);

        $response = Http::withToken(config('laravel-blog.medium.token'))
            ->post('https://api.medium.com/v1/users/'.$accountId.'/posts', [
                'title' => $page->title,
                'contentFormat' => 'html',
                'content' => $page->content,
                'canonicalUrl' => env('APP_URL').'/'.config('laravel-blog.route.blog_prefix', 'blog').'/'.$page->id.'/'.$page->slug,
                'tags' => explode(',', $page->tags),
                'publishStatus' => 'public',
            ]);

        return $response->json();
    }

    public function generateSitemap(int $limit = 100): array
    {
        return Page::select('id', 'created_at', 'slug', 'tags')
            ->whereNotNull('content')
            ->where('page_group', 'blog')->where('status', true)
            ->orderBy('id', 'desc')
            ->limit($limit)
            ->get();
    }
}
