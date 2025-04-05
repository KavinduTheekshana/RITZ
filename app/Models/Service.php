<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Service extends Model
{
    use HasFactory;

    protected $fillable = [
         'icon', 'short_title', 'title', 'slug', 'status',
        'sub_title', 'keywords', 'meta_description', 'description', 'order'
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($service) {
            $service->slug = Str::slug($service->title);
        });
    }
}
