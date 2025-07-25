<?php

// app/Filament/Widgets/ActivityTimelineChart.php
namespace App\Filament\Widgets;

use App\Models\Client;
use App\Models\Company;
use App\Models\CompanyChatList;
use Illuminate\Support\Facades\DB;
use Filament\Widgets\ChartWidget;

class ActivityTimelineChart extends ChartWidget
{
    protected static ?string $heading = 'Activity Timeline (Last 30 Days)';
    protected static ?int $sort = 8;

    protected function getData(): array
    {
        $last30Days = collect(range(29, 0))->map(function ($days) {
            return now()->subDays($days)->format('M j');
        });

        $clientData = collect(range(29, 0))->map(function ($days) {
            return Client::whereDate('created_at', now()->subDays($days))->count();
        });

        $companyData = collect(range(29, 0))->map(function ($days) {
            return Company::whereDate('created_at', now()->subDays($days))->count();
        });

        $messageData = collect(range(29, 0))->map(function ($days) {
            return CompanyChatList::whereDate('created_at', now()->subDays($days))->count();
        });

        $blogData = collect(range(29, 0))->map(function ($days) {
            return DB::table('blogs')->whereDate('created_at', now()->subDays($days))->count();
        });

        return [
            'datasets' => [
                [
                    'label' => 'New Clients',
                    'data' => $clientData->toArray(),
                    'borderColor' => 'rgb(59, 130, 246)',
                    'backgroundColor' => 'rgba(59, 130, 246, 0.1)',
                    'fill' => true,
                ],
                [
                    'label' => 'New Companies',
                    'data' => $companyData->toArray(),
                    'borderColor' => 'rgb(16, 185, 129)',
                    'backgroundColor' => 'rgba(16, 185, 129, 0.1)',
                    'fill' => true,
                ],
                [
                    'label' => 'Messages',
                    'data' => $messageData->toArray(),
                    'borderColor' => 'rgb(245, 158, 11)',
                    'backgroundColor' => 'rgba(245, 158, 11, 0.1)',
                    'fill' => true,
                ],
                [
                    'label' => 'Blog Posts',
                    'data' => $blogData->toArray(),
                    'borderColor' => 'rgb(139, 92, 246)',
                    'backgroundColor' => 'rgba(139, 92, 246, 0.1)',
                    'fill' => true,
                ],
            ],
            'labels' => $last30Days->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}

