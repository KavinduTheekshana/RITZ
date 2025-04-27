<?php

namespace App\Filament\Resources\SelfAssessmentResource\Pages;

use App\Filament\Resources\SelfAssessmentResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSelfAssessments extends ListRecords
{
    protected static string $resource = SelfAssessmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
