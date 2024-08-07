<?php

use Illuminate\Support\Facades\Route;

$prefix = config('laravel-blog.route.blog_prefix', 'blog');
$pagePrefix = config('laravel-blog.route.page_prefix', 'page');

Route::get('/' . $prefix . '/tag/{tag}', [\Moh6mmad\LaravelBlog\Http\Controllers\LaravelBlogController::class, 'index'])
->defaults('tag' , '{tag}')
    ->name('laravel-blog.tag');
    Route::get('/' . $prefix . '/category/{category}', [\Moh6mmad\LaravelBlog\Http\Controllers\LaravelBlogController::class, 'index'])
    ->defaults('category' , '{category}')
        ->name('laravel-blog.category');
Route::get('/' . $prefix . '/{id}/{slug}', [\Moh6mmad\LaravelBlog\Http\Controllers\LaravelBlogController::class, 'show'])
    ->defaults('type' , 'blog')
    ->name('laravel-blog.show');
Route::get('/' . $pagePrefix . '/{slug}', [\Moh6mmad\LaravelBlog\Http\Controllers\LaravelBlogController::class, 'show'])
    ->defaults('type' ,'page')
    ->name('laravel-blog.page.show');
Route::get('/' . $prefix, [\Moh6mmad\LaravelBlog\Http\Controllers\LaravelBlogController::class, 'index'])
    ->name('laravel-blog.blog.index');
