<?php

// app/Filament/Widgets/CommunicationsWidget.php
namespace App\Filament\Widgets;

use App\Models\CompanyChatList;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class CommunicationsWidget extends BaseWidget
{
    protected static ?int $sort = 4;

    protected function getStats(): array
    {
        $totalMessages = CompanyChatList::count();
        $unreadClientMessages = CompanyChatList::where('is_read', false)
            ->where('sender_type', 'client')->count();
        $todayMessages = CompanyChatList::whereDate('created_at', today())->count();
        $messagesWithFiles = CompanyChatList::whereNotNull('file_path')->count();

        return [
            Stat::make('Total Messages', $totalMessages)
                ->description($todayMessages . ' messages today')
                ->descriptionIcon('heroicon-m-chat-bubble-left-right')
                ->color('info'),

            Stat::make('Unread Client Messages', $unreadClientMessages)
                ->description('Requiring attention')
                ->descriptionIcon('heroicon-m-bell-alert')
                ->color($unreadClientMessages > 0 ? 'danger' : 'success'),

            Stat::make('Messages with Files', $messagesWithFiles)
                ->description('File attachments received')
                ->descriptionIcon('heroicon-m-paper-clip')
                ->color('primary'),
        ];
    }
}
