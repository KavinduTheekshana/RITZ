<?php

namespace App\Filament\Resources\BlogResource\Pages;

use App\Filament\Resources\BlogResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewBlog extends ViewRecord
{
    protected static string $resource = BlogResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
            Actions\Action::make('viewOnSite')
                ->label('View on Website')
                ->url(fn ($record) => route('blog', $record->slug))
                ->openUrlInNewTab(),
        ];
    }
}