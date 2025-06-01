<?php

use App\Http\Controllers\BlogController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\ClientDashboardController;
use App\Http\Controllers\ClientLoginController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\EngagementController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ServiceController;
use App\Mail\EngagementLetter;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('/');
Route::get('contact', [HomeController::class, 'contact'])->name('contact');
Route::get('about', [HomeController::class, 'about'])->name('about');
Route::get('services', [ServiceController::class, 'index'])->name('services');
Route::get('/services/{slug}', [ServiceController::class, 'show'])->name('service.show');
Route::post('/contact/store', [ContactController::class, 'store'])->name('contact.store');

Route::get('/blog', [BlogController::class, 'index'])->name('blog');
Route::get('/blog/{slug}', [BlogController::class, 'show'])->name('blog.show');
Route::get('/blog/category/{category}', [BlogController::class, 'category'])->name('blog.category');
Route::get('/blog/tag/{tag}', [BlogController::class, 'tag'])->name('blog.tag');
Route::get('/blog/search', [BlogController::class, 'search'])->name('blog.search');

// ------------------------------------------------------------------------------------------------------------
// Guest routes (only accessible if NOT logged in)
Route::middleware(['guest.client'])->group(function () {
    Route::get('client/login', [ClientLoginController::class, 'showLoginForm'])->name('client.login');
    Route::post('/login', [ClientLoginController::class, 'login'])->name('client.login.submit');
});

// Protected routes (only accessible if logged in)
Route::middleware(['auth.client'])->group(function () {
    Route::get('client/dashboard', [ClientDashboardController::class, 'index'])->name('client.dashboard');
    Route::get('client/profile', [ClientController::class, 'profile'])->name('client.profile');

    Route::get('client/engagement', [EngagementController::class, 'engagement'])->name('client.engagement');
    Route::post('/engagement/sign', [EngagementController::class, 'sign'])->name('client.engagement.sign');

    Route::post('/logout', [ClientLoginController::class, 'logout'])->name('client.logout');


      // Chat routes
    Route::get('/chat', [App\Http\Controllers\ClientChatController::class, 'index'])->name('client.chat');
    Route::get('/chat/messages', [App\Http\Controllers\ClientChatController::class, 'getMessages'])->name('client.chat.messages');
    Route::post('/chat/send', [App\Http\Controllers\ClientChatController::class, 'sendMessage'])->name('client.chat.send');
    Route::get('/chat/unread-counts', [App\Http\Controllers\ClientChatController::class, 'getUnreadCounts'])->name('client.chat.unread');
});



Route::fallback(function () {
    abort(404);
});
