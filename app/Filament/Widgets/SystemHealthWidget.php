<?php
// app/Filament/Widgets/SystemHealthWidget.php

namespace App\Filament\Widgets;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class SystemHealthWidget extends BaseWidget
{
    protected static ?int $sort = 12;

    protected function getStats(): array
    {
        // Database connection test
        $dbStatus = 'Connected';
        try {
            DB::connection()->getPdo();
        } catch (\Exception $e) {
            $dbStatus = 'Error';
        }

        // Cache test
        $cacheStatus = 'Working';
        try {
            Cache::put('health_check', 'test', 1);
            if (Cache::get('health_check') !== 'test') {
                $cacheStatus = 'Error';
            }
            Cache::forget('health_check');
        } catch (\Exception $e) {
            $cacheStatus = 'Error';
        }

        // Storage disk space (approximate)
        $diskSpace = 'Available';
        try {
            $freeBytes = disk_free_space(storage_path());
            $totalBytes = disk_total_space(storage_path());
            if ($freeBytes && $totalBytes) {
                $usedPercent = (($totalBytes - $freeBytes) / $totalBytes) * 100;
                $diskSpace = number_format(100 - $usedPercent, 1) . '% Free';
            }
        } catch (\Exception $e) {
            $diskSpace = 'Unknown';
        }

        // Memory usage
        $memoryUsage = 'Normal';
        try {
            $memUsed = memory_get_usage(true);
            $memLimit = ini_get('memory_limit');
            $memUsage = round($memUsed / 1024 / 1024, 2) . ' MB';
        } catch (\Exception $e) {
            $memUsage = 'Unknown';
        }

        return [
            Stat::make('Database', $dbStatus)
                ->description('Connection status')
                ->descriptionIcon('heroicon-m-circle-stack')
                ->color($dbStatus === 'Connected' ? 'success' : 'danger'),

            Stat::make('Cache', $cacheStatus)
                ->description('Cache system status')
                ->descriptionIcon('heroicon-m-bolt')
                ->color($cacheStatus === 'Working' ? 'success' : 'danger'),

            Stat::make('Storage', $diskSpace)
                ->description('Disk space available')
                ->descriptionIcon('heroicon-m-server')
                ->color('info'),

            Stat::make('Memory', $memUsage)
                ->description('Current memory usage')
                ->descriptionIcon('heroicon-m-cpu-chip')
                ->color('primary'),
        ];
    }
}