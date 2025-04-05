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
        $categories = Blog::where('status', true)
            ->select('category')
            ->selectRaw('COUNT(*) as post_count')
            ->groupBy('category')
            ->orderBy('category')
            ->get();
        $blog = Blog::where('slug', $slug)->published()->firstOrFail();
        $recentPosts = Blog::where('status', true)->latest()->take(2)->get();

        $allKeywords = [];
        $blogs = Blog::where('status', true)->get();

        foreach ($blogs as $blogItem) {
            // Get keywords from tags field ONLY
            if (!empty($blogItem->tags)) {
                $tagsList = explode(',', $blogItem->tags);
                foreach ($tagsList as $tag) {
                    $tag = trim($tag);
                    if (!empty($tag)) {
                        $allKeywords[$tag] = true;
                    }
                }
            }
        }

        // Convert to array and sort alphabetically
        $uniqueKeywords = array_keys($allKeywords);
        sort($uniqueKeywords);
        return view('frontend.blog.post', compact('blog', 'categories', 'recentPosts', 'uniqueKeywords'));
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
