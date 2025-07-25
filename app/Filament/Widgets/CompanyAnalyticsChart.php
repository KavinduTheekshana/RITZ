<?php
// app/Filament/Widgets/CompanyAnalyticsChart.php
namespace App\Filament\Widgets;

use App\Models\Company;
use Filament\Widgets\ChartWidget;

class CompanyAnalyticsChart extends ChartWidget
{
    protected static ?string $heading = 'Company Types & Engagement Status';
    protected static ?int $sort = 7;

    protected function getData(): array
    {
        $companyTypes = Company::selectRaw('company_type, COUNT(*) as count')
            ->whereNotNull('company_type')
            ->groupBy('company_type')
            ->pluck('count', 'company_type')
            ->toArray();

        return [
            'datasets' => [
                [
                    'data' => array_values($companyTypes),
                    'backgroundColor' => ['#3B82F6', '#10B981', '#F59E0B', '#EF4444', '#8B5CF6'],
                ],
            ],
            'labels' => array_keys($companyTypes),
        ];
    }

    protected function getType(): string
    {
        return 'pie';
    }
}