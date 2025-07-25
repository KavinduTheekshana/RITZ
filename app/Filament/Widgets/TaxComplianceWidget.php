<?php
// app/Filament/Widgets/TaxComplianceWidget.php

namespace App\Filament\Widgets;

use Illuminate\Support\Facades\DB;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class TaxComplianceWidget extends BaseWidget
{
    protected static ?int $sort = 3;

    protected function getStats(): array
    {
        $upcomingCT600 = DB::table('accounts_and_returns_details')
            ->where('ct600_due', '>=', now())
            ->where('ct600_due', '<=', now()->addDays(30))
            ->count();
        
        $upcomingAccounts = DB::table('accounts_and_returns_details')
            ->where('ch_accounts_next_due', '>=', now())
            ->where('ch_accounts_next_due', '<=', now()->addDays(30))
            ->count();
        
        $totalCorpTaxDue = DB::table('accounts_and_returns_details')
            ->sum('corporation_tax_amount_due') ?? 0;
        
        // Fixed: Use correct column name from your schema
        $overdueVAT = DB::table('vat_details')
            ->where('next_return_due', '<', now())
            ->count();

        return [
            Stat::make('CT600 Due (30 days)', $upcomingCT600)
                ->description('Corporation tax returns due')
                ->descriptionIcon('heroicon-m-document-text')
                ->color($upcomingCT600 > 0 ? 'warning' : 'success'),

            Stat::make('Accounts Due (30 days)', $upcomingAccounts)
                ->description('Companies House filings due')
                ->descriptionIcon('heroicon-m-building-library')
                ->color($upcomingAccounts > 0 ? 'warning' : 'success'),

            Stat::make('Corp Tax Due', '£' . number_format($totalCorpTaxDue, 0))
                ->description('Total corporation tax liability')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('info'),

            Stat::make('Overdue VAT Returns', $overdueVAT)
                ->description('VAT returns past due date')
                ->descriptionIcon('heroicon-m-exclamation-triangle')
                ->color($overdueVAT > 0 ? 'danger' : 'success'),
        ];
    }
}