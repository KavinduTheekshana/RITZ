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
    protected static ?string $navigationGroup = 'Content Management';
    protected static ?string $navigationIcon = 'heroicon-o-briefcase';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Grid::make(2)->schema([
                    TextInput::make('short_title')->required(),
                    TextInput::make('title')->required(),
                    TextInput::make('slug')
                    ->disabled()
                    ->unique(ignoreRecord: true),
                    TextInput::make('sub_title')->nullable(),


                    FileUpload::make('image')
                    ->image()
                    ->directory('services') // Store logos in the "brands" directory
                    ->maxSize(2048) // 2MB max file size

                    ->required(),

                    FileUpload::make('icon')
                    ->image()
                    ->directory('icon') // Store logos in the "brands" directory
                    ->maxSize(2048) // 2MB max file size

                    ->helperText('Upload your custom SVG icon file from ( https://iconscout.com ) Always Use Black Color Icons')
                    ->required(),

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
