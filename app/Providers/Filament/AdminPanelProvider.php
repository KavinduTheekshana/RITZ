<?php
// app/Providers/Filament/AdminPanelProvider.php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

// Import only the working widgets
use App\Filament\Widgets\SimpleOverviewWidget;
use App\Filament\Widgets\FinancialServicesWidget;
use App\Filament\Widgets\TaxComplianceWidget;
use App\Filament\Widgets\CommunicationsWidget;
use App\Filament\Widgets\ContentManagementWidget;
use App\Filament\Widgets\RevenueBreakdownChart;
use App\Filament\Widgets\CompanyAnalyticsChart;
use App\Filament\Widgets\ActivityTimelineChart;
use App\Filament\Widgets\QuickActionsWidget;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
            ->colors([
                'primary' => Color::Lime,
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                // Start with working widgets only
                SimpleOverviewWidget::class,
                FinancialServicesWidget::class,
                TaxComplianceWidget::class,
                CommunicationsWidget::class,
                ContentManagementWidget::class,
                QuickActionsWidget::class,
                RevenueBreakdownChart::class,
                CompanyAnalyticsChart::class,
                ActivityTimelineChart::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}