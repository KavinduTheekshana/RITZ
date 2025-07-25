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
use App\Models\CompanyChatList;
use App\Models\SelfAssessmentChatList;

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
Route::prefix('client')->middleware(['auth.client'])->group(function () {
    Route::get('/dashboard', [ClientDashboardController::class, 'index'])->name('client.dashboard');
    Route::get('/profile', [ClientController::class, 'profile'])->name('client.profile');


    Route::put('/profile/password', [ClientController::class, 'updatePassword'])->name('client.password.update');


    Route::get('/engagement', [EngagementController::class, 'engagement'])->name('client.engagement');
    Route::post('/engagement/sign', [EngagementController::class, 'sign'])->name('client.engagement.sign');

    Route::post('/logout', [ClientLoginController::class, 'logout'])->name('client.logout');

    // Chat routes
    Route::get('/chat', [App\Http\Controllers\ClientChatController::class, 'index'])->name('client.chat');
    Route::get('/chat/messages', [App\Http\Controllers\ClientChatController::class, 'getMessages'])->name('client.chat.messages');
    Route::post('/chat/send', [App\Http\Controllers\ClientChatController::class, 'sendMessage'])->name('client.chat.send');
    Route::get('/chat/unread-counts', [App\Http\Controllers\ClientChatController::class, 'getUnreadCounts'])->name('client.chat.unread');
    Route::post('/chat/sign-document', [App\Http\Controllers\ClientChatController::class, 'signDocument'])->name('client.chat.sign-document');

    // Self Assessment Chat routes
    Route::get('/self-assessment/chat/messages', [App\Http\Controllers\SelfAssessmentChatController::class, 'getMessages'])->name('client.self-assessment.chat.messages');
    Route::post('/self-assessment/chat/send', [App\Http\Controllers\SelfAssessmentChatController::class, 'sendMessage'])->name('client.self-assessment.chat.send');
    Route::get('/self-assessment/chat/unread-counts', [App\Http\Controllers\SelfAssessmentChatController::class, 'getUnreadCounts'])->name('client.self-assessment.chat.unread');
    Route::post('/self-assessment/chat/sign-document', [App\Http\Controllers\SelfAssessmentChatController::class, 'signDocument'])->name('client.self-assessment.chat.sign-document');

    Route::get('/chat/download/{message}', [App\Http\Controllers\ClientChatController::class, 'downloadAttachment'])->name('client.chat.download');
    Route::get('/self-assessment/chat/download/{message}', [App\Http\Controllers\SelfAssessmentChatController::class, 'downloadAttachment'])->name('client.self-assessment.chat.download');


});


Route::middleware(['auth'])->group(function () {
    Route::get('/admin/chat/download/company/{message}', function(CompanyChatList $message) {
        if (!$message->file_path) {
            abort(404, 'File not found');
        }
        
        $filePath = storage_path('app/public/' . $message->file_path);
        
        if (!file_exists($filePath)) {
            abort(404, 'File not found');
        }
        
        return response()->download($filePath, $message->file_name);
    })->name('admin.chat.download.company');
    
    Route::get('/admin/chat/download/self-assessment/{message}', function(SelfAssessmentChatList $message) {
        if (!$message->file_path) {
            abort(404, 'File not found');
        }
        
        $filePath = storage_path('app/public/' . $message->file_path);
        
        if (!file_exists($filePath)) {
            abort(404, 'File not found');
        }
        
        return response()->download($filePath, $message->file_name);
    })->name('admin.chat.download.self-assessment');
});


Route::fallback(function () {
    abort(404);
});