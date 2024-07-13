<?php

return [
    'route' => [
        'blog_prefix' => 'blog',
        'page_prefix' => 'page',
    ],
    'database' => [
        'table' => 'laravel_blog',
    ],
    'views' => [
        'base' => '',
        'target_yield' => 'blog_content',
    ],
    'categories' => [
        ['slug' => 'uncategorized', 'name' => 'Uncategorized'],
    ],
    'medium' => [
        'account_id' => env('MEDIUM_ACCOUNT_ID'),
        'token' => env('MEDIUM_TOKEN'),
    ],
    'openai' => [
        'prompt' => 'Write a very professional, with human tone and very accurate based on most viewed & newest articles on internet, write with modern blog structure with best practices that includes at least 4000 word blog post for following title that uses the most relevant keywords. The blog post should include an introduction, main body, as much as possible well-structured data-driven paragraphs, conclusion by using well-structured HTML tags for SEO. The conclusion should invite readers to leave a comment. Output should contains relevant keywords and well structured HTML tags for each section. Blog title: ',
        'api_key' => env('OPENAI_API_KEY'),
    ]
];
