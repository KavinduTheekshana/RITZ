<?php

namespace App\Filament\Resources\SelfAssessmentResource\Pages;

use App\Filament\Resources\SelfAssessmentResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewSelfAssessment extends ViewRecord
{
    protected static string $resource = SelfAssessmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
