<?php

// app/Filament/Widgets/QuickActionsWidget.php
// app/Filament/Widgets/QuickActionsWidget.php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;

class QuickActionsWidget extends Widget
{
    protected static string $view = 'filament.widgets.quick-actions';
    protected static ?int $sort = 11;
    protected int | string | array $columnSpan = 'full';

    public function getViewData(): array
    {
        return [
            'actions' => [
                [
                    'label' => 'Add New Client',
                    'icon' => 'heroicon-o-user-plus',
                    'url' => '/admin/clients/create',
                    'color' => 'primary',
                    'description' => 'Register a new client',
                ],
                [
                    'label' => 'Add New Company',
                    'icon' => 'heroicon-o-building-office-2',
                    'url' => '/admin/companies/create',
                    'color' => 'success',
                    'description' => 'Add company registration',
                ],
                [
                    'label' => 'Create Blog Post',
                    'icon' => 'heroicon-o-document-plus',
                    'url' => '/admin/blogs/create',
                    'color' => 'warning',
                    'description' => 'Write new blog content',
                ],
                [
                    'label' => 'View All Messages',
                    'icon' => 'heroicon-o-chat-bubble-left-right',
                    'url' => '/admin',
                    'color' => 'info',
                    'description' => 'View client communications',
                ],
            ],
        ];
    }
}