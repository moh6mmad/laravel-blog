<?php

namespace Moh6mmad\LaravelBlog\Http\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Config;

class LaravelBlog extends Model
{
    use HasFactory;

    protected $dbName;
    protected $table;

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->dbName = Config::get('laravel-blog.database.table', 'laravel_blog');
        $this->table = $this->dbName;
    }
    protected $fillable = [
        'page_group',
        'slug',
        'category_id',
        'title',
        'content',
        'status',
        'primary_image',
        'tags',
        'views',
        'short_description',
    ];

    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }

    public function scopeBlogPosts($query)
    {
        return $query->where('page_group', 'blog');
    }

    public function getPrimaryImageUrlAttribute()
    {
        return $this->primary_image ? Storage::url(($this->primary_image)) : asset('images/blog/blog-image-'.rand(1, 6).'.webp');
    }

    public function getTagsArrayAttribute()
    {
        return explode(',', $this->tags);
    }

    public function getExcerptAttribute()
    {

        return substr(strip_tags($this->content), 0, 100).'...';
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($page) {
            $page->slug = Str::slug(Str::limit($page->title, 80));
        });

        static::updating(function ($page) {
            if (empty($page->slug)) {
                $page->slug = Str::slug($page->title);
            }
        });
    }
}
