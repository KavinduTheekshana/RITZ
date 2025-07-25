<?php

// app/Filament/Widgets/ClientsCompaniesOverviewWidget.php
namespace App\Filament\Widgets;

use App\Models\Client;
use App\Models\Company;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class ClientsCompaniesOverviewWidget extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $totalClients = Client::count();
        $totalCompanies = Company::count();
        $engagedCompanies = Company::where('engagement', true)->count();
        $pendingEngagement = Company::where('engagement', false)->count();
        
        $newClientsThisMonth = Client::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)->count();
        
        $verifiedClients = Client::where('photo_id_verified', true)
            ->where('address_verified', true)->count();

        return [
            Stat::make('Total Clients', $totalClients)
                ->description($newClientsThisMonth . ' new this month')
                ->descriptionIcon('heroicon-m-user-plus')
                ->color('success')
                ->chart([7, 2, 10, 3, 15, 4, 17]),

            Stat::make('Total Companies', $totalCompanies)
                ->description($engagedCompanies . ' engaged, ' . $pendingEngagement . ' pending')
                ->descriptionIcon('heroicon-m-building-office-2')
                ->color('primary'),

            Stat::make('Verified Clients', $verifiedClients)
                ->description('Photo ID & Address verified')
                ->descriptionIcon('heroicon-m-shield-check')
                ->color('success'),

            Stat::make('Engagement Rate', number_format(($engagedCompanies / max($totalCompanies, 1)) * 100, 1) . '%')
                ->description('Companies with signed engagement')
                ->descriptionIcon('heroicon-m-document-check')
                ->color($engagedCompanies > $pendingEngagement ? 'success' : 'warning'),
        ];
    }
}