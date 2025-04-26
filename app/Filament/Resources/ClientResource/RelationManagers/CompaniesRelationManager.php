<?php

namespace App\Filament\Resources\ClientResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CompaniesRelationManager extends RelationManager
{
    protected static string $relationship = 'companies';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('company_name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('company_number')
                    ->maxLength(255),
                Forms\Components\TextInput::make('company_status')
                    ->maxLength(255),
                Forms\Components\Select::make('company_type')
                    ->options([
                        'Limited Company' => 'Limited Company',
                        'Limited Liability Partnership' => 'Limited Liability Partnership',
                        'Sole Trader' => 'Sole Trader',
                        'Partnership' => 'Partnership',
                        'Charity' => 'Charity',
                    ]),
                Forms\Components\DatePicker::make('incorporation_date'),
                Forms\Components\TextInput::make('company_trading_as')
                    ->maxLength(255),
                Forms\Components\Textarea::make('registered_address')
                    ->rows(3),
                Forms\Components\TextInput::make('company_email')
                    ->email()
                    ->maxLength(255),
                Forms\Components\TextInput::make('company_telephone')
                    ->tel()
                    ->maxLength(255),
                Forms\Components\TextInput::make('company_utr')
                    ->label('Company UTR')
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('company_name')
            ->columns([
                Tables\Columns\TextColumn::make('company_name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('company_number')
                    ->searchable(),
                Tables\Columns\TextColumn::make('company_status')
                    ->sortable(),
                Tables\Columns\TextColumn::make('company_type')
                    ->sortable(),
                Tables\Columns\TextColumn::make('incorporation_date')
                    ->date(),
                Tables\Columns\TextColumn::make('company_email')
                    ->searchable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('company_type')
                    ->options([
                        'Limited Company' => 'Limited Company',
                        'Limited Liability Partnership' => 'Limited Liability Partnership',
                        'Sole Trader' => 'Sole Trader',
                        'Partnership' => 'Partnership',
                        'Charity' => 'Charity',
                    ]),
                Tables\Filters\SelectFilter::make('company_status')
                    ->options([
                        'Active' => 'Active',
                        'Dissolved' => 'Dissolved',
                        'Liquidation' => 'Liquidation',
                    ]),
            ])
            ->headerActions([
                Tables\Actions\AttachAction::make(),
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