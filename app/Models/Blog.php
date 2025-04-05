<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Blog extends Model
{
    use HasFactory;

    protected $table = 'blogs';

    protected $fillable = [
        'title',
        'slug',
        'image',
        'content',
        'author',
        'category',
        'tags',
        'meta_description',
        'meta_keywords',
        'status',
    ];

    protected $casts = [
        'status' => 'boolean',
    ];

    // Generate slug from title
    public static function boot()
    {
        parent::boot();

        static::creating(function ($blog) {
            if (empty($blog->slug)) {
                $blog->slug = Str::slug($blog->title);
            }
        });

        static::updating(function ($blog) {
            if ($blog->isDirty('title') && !$blog->isDirty('slug')) {
                $blog->slug = Str::slug($blog->title);
            }
        });
    }

    // Scope for published blogs
    public function scopePublished($query)
    {
        return $query->where('status', true);
    }

    // Get URL attribute
    public function getUrlAttribute()
    {
        return route('blog.show', $this->slug);
    }
}
