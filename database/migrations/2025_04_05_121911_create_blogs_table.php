<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('blogs', function (Blueprint $table) {
            $table->id();
            $table->string('title'); // Blog post title
            $table->string('slug')->unique(); // Slug for the URL
            $table->string('image');
            $table->text('content'); // Blog post content
            $table->string('author'); // Author of the blog post
            $table->string('category'); // Category of the blog post
            $table->string('tags'); // Tags associated with the blog post
            $table->string('meta_description'); // Meta description for SEO
            $table->string('meta_keywords'); // Meta keywords for SEO
            $table->boolean('status')->default(true); // Status to show/hide the blog
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blogs');
    }
};
