<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ServiceResource\Pages;
use App\Filament\Resources\ServiceResource\RelationManagers;
use App\Models\Service;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\{TextInput, FileUpload, Toggle, Textarea, RichEditor, Grid, Select, Hidden, View};
use Filament\Tables\Columns\{ImageColumn, TextColumn, ToggleColumn};
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use TomatoPHP\FilamentIcons\Components\IconPicker;
use Filament\Forms\Components\ViewField;

class ServiceResource extends Resource
{
    protected static ?string $model = Service::class;
    protected static ?string $navigationLabel = 'Services';
    protected static ?string $pluralLabel = 'Services';
    protected static ?string $slug = 'services';
    protected static ?string $recordTitleAttribute = 'title';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Grid::make(2)->schema([
                    TextInput::make('short_title')->required(),
                    TextInput::make('title')->required(),
                    TextInput::make('slug')->disabled()->unique(),
                    TextInput::make('sub_title')->nullable(),


                    Grid::make(9)->schema([ // Wrap icon textarea and preview in a small grid
                        FileUpload::make('image')
                            ->image()
                            ->directory('services') // Store logos in the "brands" directory
                            ->maxSize(2048) // 2MB max file size
                            ->columnSpan(4)
                            ->required(),
                        Textarea::make('icon')
                            ->placeholder('Paste SVG code here')
                            ->rows(4)
                            ->nullable()
                            ->helperText('Paste your custom SVG icon code from ( https://heroicons.com )')
                            ->live()
                            ->columnSpan(4) // Take 2/3 of the width
                            ->afterStateUpdated(fn($state, $set) => $set('preview_icon', is_array($state) ? '' : $state))
                            ->afterStateHydrated(fn($state, $set) => $set('preview_icon', is_array($state) ? '' : $state)), // Add this line

                        ViewField::make('preview_icon')
                            ->label(' ')
                            ->view('filament.components.icon-preview')
                            ->columnSpan(1), // Take 1/3 of the width
                    ]),
                    Grid::make(2)->schema([
                        RichEditor::make('description')->columnSpan(2)->extraAttributes(['style' => 'min-height: 350px;'])->required(),
                    ]),

                    Textarea::make('keywords')->rows(2)->nullable(),
                    Textarea::make('meta_description')->rows(2)->nullable(),

                    TextInput::make('order')->numeric()->default(0),
                    Toggle::make('status')->label('Active')->default(true),
                ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('image')->disk('public')->circular(),
                TextColumn::make('short_title')->sortable()->searchable(),
                TextColumn::make('title')->sortable()->searchable(),
                TextColumn::make('slug')->copyable(),
                ToggleColumn::make('status')->label('Active'),
                TextColumn::make('order')->sortable(),
            ])
            ->filters([])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
            ])
            ->bulkActions([Tables\Actions\DeleteBulkAction::make()]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListServices::route('/'),
            'create' => Pages\CreateService::route('/create'),
            'edit' => Pages\EditService::route('/{record}/edit'),
        ];
    }
}
