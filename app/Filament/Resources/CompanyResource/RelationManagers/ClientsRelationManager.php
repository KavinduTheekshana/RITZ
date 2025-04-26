<?php
namespace App\Filament\Resources\CompanyResource\RelationManagers;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
class ClientsRelationManager extends RelationManager
{
protected static string $relationship = 'clients';

public function form(Form $form): Form
{
    return $form
        ->schema([
            Forms\Components\TextInput::make('first_name')
                ->required()
                ->maxLength(255),
            Forms\Components\TextInput::make('last_name')
                ->required()
                ->maxLength(255),
            Forms\Components\TextInput::make('email')
                ->email()
                ->required()
                ->maxLength(255),
            Forms\Components\TextInput::make('mobile_number')
                ->tel()
                ->maxLength(255),
        ]);
}

public function table(Table $table): Table
{
    return $table
        // Remove this line that references the non-existent column
        // ->recordTitleAttribute('full_name')
        ->columns([
            Tables\Columns\TextColumn::make('full_name')
                ->label('Name')
                ->searchable(['first_name', 'last_name'])
                ->sortable(['first_name']),
            Tables\Columns\TextColumn::make('email')
                ->searchable(),
            Tables\Columns\TextColumn::make('mobile_number')
                ->searchable(),
        ])
        ->filters([
            //
        ])
        ->headerActions([
            Tables\Actions\AttachAction::make()
                ->recordSelectSearchColumns(['first_name', 'last_name', 'email'])
                ->recordTitle(function ($record) {
                    return "{$record->title} {$record->first_name} {$record->last_name}";
                }),
        ])
        ->actions([
            Tables\Actions\EditAction::make(),
            Tables\Actions\DetachAction::make(),
        ])
        ->bulkActions([
            Tables\Actions\BulkActionGroup::make([
                Tables\Actions\DetachBulkAction::make(),
            ]),
        ]);
}
}