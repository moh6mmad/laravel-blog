<?php

namespace Moh6mmad\LaravelBlog\Commands;

use Moh6mmad\LaravelBlog\Services\BlogService;
use Moh6mmad\LaravelBlog\Http\Models\LaravelBlog as Page;
use Illuminate\Console\Command;

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
        $page = Page::when(!empty($this->argument('page_id')), fn ($query) => $query->where('id', $this->argument('page_id')))
            ->where('status', false)
            ->where('generate_by_ai', true)
            ->orderBy('id', 'asc')
            ->first();
        app(BlogService::class)->generateContentByOpenAi($page);
    }
}
