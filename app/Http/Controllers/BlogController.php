<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    public function index()
    {
        $blogs = Blog::published()->latest()->paginate(8);
        return view('frontend.blog.index', compact('blogs'));
    }

    /**
     * Display the specified blog.
     *
     * @param  string  $slug
     * @return \Illuminate\Http\Response
     */
    public function show($slug)
    {
        $blog = Blog::where('slug', $slug)->published()->firstOrFail();
        return view('frontend.blog.post', compact('blog'));
    }

    /**
     * Display a listing of blogs by category.
     *
     * @param  string  $category
     * @return \Illuminate\Http\Response
     */
    public function category($category)
    {
        $blogs = Blog::where('category', $category)->published()->latest()->paginate(9);
        return view('frontend.blog.category', compact('blogs', 'category'));
    }

    /**
     * Display a listing of blogs by tag.
     *
     * @param  string  $tag
     * @return \Illuminate\Http\Response
     */
    public function tag($tag)
    {
        // Search for the tag in both tags and meta_keywords columns
        $blogs = Blog::where(function ($query) use ($tag) {
            $query->where('tags', 'like', "%$tag%")
                ->orWhere('meta_keywords', 'like', "%$tag%");
        })
            ->published()
            ->latest()
            ->paginate(9);

        return view('frontend.blog.tags', compact('blogs', 'tag'));
    }
}
