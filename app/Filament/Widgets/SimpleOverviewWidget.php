<?php
// app/Filament/Widgets/SimpleOverviewWidget.php

namespace App\Filament\Widgets;

use App\Models\Client;
use App\Models\Company;
use App\Models\CompanyChatList;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class SimpleOverviewWidget extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        return [
            Stat::make('Total Clients', Client::count())
                ->description('Registered clients')
                ->descriptionIcon('heroicon-m-users')
                ->color('success'),

            Stat::make('Total Companies', Company::count())
                ->description('Registered companies')
                ->descriptionIcon('heroicon-m-building-office-2')
                ->color('primary'),

            Stat::make('Total Messages', CompanyChatList::count())
                ->description('All communications')
                ->descriptionIcon('heroicon-m-chat-bubble-left-right')
                ->color('info'),

            Stat::make('System Users', User::count())
                ->description('Admin users')
                ->descriptionIcon('heroicon-m-user-group')
                ->color('warning'),
        ];
    }
}
