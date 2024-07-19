<?php

namespace Moh6mmad\LaravelBlog\Commands;

use Illuminate\Console\Command;
use Moh6mmad\LaravelBlog\Http\Models\LaravelBlog as Page;
use Moh6mmad\LaravelBlog\Services\BlogService;

class GenerateAiContent extends Command
{
    protected $signature = 'larablog:generate {page_id?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate one blog content';

    public function handle()
    {
        $page = Page::when(! empty($this->argument('page_id')), fn ($query) => $query->where('id', $this->argument('page_id')))
            ->where('status', false)
            ->where('generate_by_ai', true)
            ->orderBy('id', 'asc')
            ->first();
        app(BlogService::class)->generateContentByOpenAi($page);
    }
}
