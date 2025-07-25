<?php
// app/Filament/Widgets/RevenueBreakdownChart.php

namespace App\Filament\Widgets;

use Illuminate\Support\Facades\DB;
use Filament\Widgets\ChartWidget;

class RevenueBreakdownChart extends ChartWidget
{
    protected static ?string $heading = 'Revenue by Service Type';
    protected static ?int $sort = 6;

    protected function getData(): array
    {
        $services = [
            'Accounts' => DB::table('services_requireds')->sum('accounts') ?? 0,
            'Bookkeeping' => DB::table('services_requireds')->sum('bookkeeping') ?? 0,
            'VAT Returns' => DB::table('services_requireds')->sum('vat_returns') ?? 0,
            'Payroll' => DB::table('services_requireds')->sum('payroll') ?? 0,
            'CT600 Return' => DB::table('services_requireds')->sum('ct600_return') ?? 0,
            'Auto Enrolment' => DB::table('services_requireds')->sum('auto_enrolment') ?? 0,
            'Management Accounts' => DB::table('services_requireds')->sum('management_accounts') ?? 0,
            'Confirmation Statement' => DB::table('services_requireds')->sum('confirmation_statement') ?? 0,
            'P11D' => DB::table('services_requireds')->sum('p11d') ?? 0,
            'CIS' => DB::table('services_requireds')->sum('cis') ?? 0,
        ];

        // Filter out zero values and sort by value
        $services = array_filter($services, function($value) {
            return $value > 0;
        });
        arsort($services);

        return [
            'datasets' => [
                [
                    'data' => array_values($services),
                    'backgroundColor' => [
                        '#3B82F6', '#10B981', '#F59E0B', '#EF4444', '#8B5CF6',
                        '#F97316', '#06B6D4', '#84CC16', '#EC4899', '#6B7280'
                    ],
                ],
            ],
            'labels' => array_keys($services),
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
}
