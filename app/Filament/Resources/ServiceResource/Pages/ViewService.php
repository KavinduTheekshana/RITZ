<?php

namespace App\Filament\Resources\ServiceResource\Pages;

use App\Filament\Resources\ServiceResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewService extends ViewRecord
{
    protected static string $resource = ServiceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
            Actions\Action::make('viewOnSite')
                ->label('View on Website')
                ->url(fn($record) => route('service.show', $record->slug))
                ->openUrlInNewTab(),
        ];
    }
}
