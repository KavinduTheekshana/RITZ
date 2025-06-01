<?php

use App\Http\Controllers\Api\ClientMessageController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::prefix('client-messages')->group(function () {
    // Send message from client to admin
    Route::post('/', [ClientMessageController::class, 'store']);
    
    // Get chat history between client and admin for a company
    Route::get('/history', [ClientMessageController::class, 'getChatHistory']);
    
    // Mark specific messages as read
    Route::post('/mark-read', [ClientMessageController::class, 'markAsRead']);
    
    // Get recent notifications for header dropdown
    Route::get('/recent-notifications', [ClientMessageController::class, 'getRecentNotifications']);
    
    // Mark all messages as read for a client
    Route::post('/mark-all-read', [ClientMessageController::class, 'markAllAsRead']);
    
    // Get chat statistics for dashboard
    Route::get('/statistics', [ClientMessageController::class, 'getChatStatistics']);
});