<?php

// app/Filament/Widgets/FinancialServicesWidget.php

namespace App\Filament\Widgets;

use Illuminate\Support\Facades\DB;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class FinancialServicesWidget extends BaseWidget
{
    protected static ?int $sort = 2;

    protected function getStats(): array
    {
        // Use table name directly since model might not exist
        $totalRevenue = DB::table('services_requireds')->sum('annual_charge') ?? 0;
        $monthlyRevenue = DB::table('services_requireds')->sum('monthly_charge') ?? 0;
        $avgAnnualCharge = DB::table('services_requireds')->avg('annual_charge') ?? 0;
        
        $vatReturnsCount = DB::table('services_requireds')->whereNotNull('vat_returns')->count();
        $payrollClientsCount = DB::table('services_requireds')->whereNotNull('payroll')->count();
        $bookkeepingClientsCount = DB::table('services_requireds')->whereNotNull('bookkeeping')->count();

        return [
            Stat::make('Annual Revenue', '£' . number_format($totalRevenue, 0))
                ->description('Total contracted annual charges')
                ->descriptionIcon('heroicon-m-currency-pound')
                ->color('success'),

            Stat::make('Monthly Revenue', '£' . number_format($monthlyRevenue, 0))
                ->description('Total monthly charges')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('primary'),

            Stat::make('Avg Annual Charge', '£' . number_format($avgAnnualCharge, 0))
                ->description('Per company average')
                ->descriptionIcon('heroicon-m-calculator')
                ->color('info'),

            Stat::make('Active Services', $vatReturnsCount + $payrollClientsCount + $bookkeepingClientsCount)
                ->description($vatReturnsCount . ' VAT, ' . $payrollClientsCount . ' Payroll, ' . $bookkeepingClientsCount . ' Books')
                ->descriptionIcon('heroicon-m-briefcase')
                ->color('warning'),
        ];
    }
}