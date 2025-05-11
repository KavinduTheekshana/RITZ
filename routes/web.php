<?php

use App\Http\Controllers\BlogController;
use App\Http\Controllers\ClientDashboardController;
use App\Http\Controllers\ClientLoginController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ServiceController;
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


// Route::group(['prefix' => 'client'], function () {
//     Route::post('/login', [ClientLoginController::class, 'login'])->name('client.login.submit');
//     Route::post('/logout', [ClientLoginController::class, 'logout'])->name('client.logout');
  

//     // Protected routes for clients
//     Route::group(['middleware' => 'auth:client'], function () {
//         Route::get('/dashboard', [ClientDashboardController::class, 'index'])->name('client.dashboard');
//     });
// });

// Guest routes (only accessible if NOT logged in)
Route::middleware(['guest.client'])->group(function () {
    Route::get('client/login', [ClientLoginController::class, 'showLoginForm'])->name('client.login');
    Route::post('/login', [ClientLoginController::class, 'login'])->name('client.login.submit');
});

// Protected routes (only accessible if logged in)
Route::middleware(['auth.client'])->group(function () {
    Route::get('client/dashboard', [ClientDashboardController::class, 'index'])->name('client.dashboard');
    Route::post('/logout', [ClientLoginController::class, 'logout'])->name('client.logout');
});
