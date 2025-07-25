<?php
// app/Filament/Widgets/ContentManagementWidget.php

namespace App\Filament\Widgets;

use Illuminate\Support\Facades\DB;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class ContentManagementWidget extends BaseWidget
{
    protected static ?int $sort = 5;

    protected function getStats(): array
    {
        $totalBlogs = DB::table('blogs')->count();
        $publishedBlogs = DB::table('blogs')->where('status', 1)->count();
        $draftBlogs = DB::table('blogs')->where('status', 0)->count();
        
        $totalServices = DB::table('services')->count();
        $activeServices = DB::table('services')->where('status', 1)->count();
        
        $totalFaqs = DB::table('faqs')->count();
        $activeFaqs = DB::table('faqs')->where('status', 1)->count();
        
        $totalPartners = DB::table('partners')->count();
        $activePartners = DB::table('partners')->where('status', 1)->count();

        return [
            Stat::make('Blog Posts', $totalBlogs)
                ->description($publishedBlogs . ' published, ' . $draftBlogs . ' drafts')
                ->descriptionIcon('heroicon-m-document-text')
                ->color('info'),

            Stat::make('Services', $totalServices)
                ->description($activeServices . ' active services')
                ->descriptionIcon('heroicon-m-cog-6-tooth')
                ->color('primary'),

            Stat::make('FAQs', $totalFaqs)
                ->description($activeFaqs . ' active FAQs')
                ->descriptionIcon('heroicon-m-question-mark-circle')
                ->color('success'),

            Stat::make('Partners', $totalPartners)
                ->description($activePartners . ' active partners')
                ->descriptionIcon('heroicon-m-building-office')
                ->color('warning'),
        ];
    }
}
