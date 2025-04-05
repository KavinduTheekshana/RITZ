<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ServiceResource\Pages;
use App\Models\Service;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Filters\TernaryFilter;

class ServiceResource extends Resource
{
    protected static ?string $model = Service::class;

    protected static ?string $navigationIcon = 'heroicon-o-wrench-screwdriver';

    protected static ?string $navigationGroup = 'Content Management';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Grid::make(['default' => 12])->schema([
                    Card::make()->schema([
                        Grid::make(['default' => 12])->schema([
                            TextInput::make('short_title')
                                ->required()
                                ->maxLength(255)
                                ->columnSpan(['default' => 6]),

                            TextInput::make('title')
                                ->required()
                                ->maxLength(255)
                                ->reactive()
                                ->afterStateUpdated(function ($state, callable $set) {
                                    $set('slug', Str::slug($state));
                                })
                                ->columnSpan(['default' => 6]),

                            TextInput::make('sub_title')
                                ->maxLength(255)
                                ->columnSpan(['default' => 6]),



                            TextInput::make('slug')
                                ->required()
                                ->maxLength(255)
                                ->unique(Service::class, 'slug', fn($record) => $record)
                                ->disabled() // Makes the field non-editable
                                ->dehydrated() // Ensures the value still saves to database despite being disabled
                                ->columnSpan(['default' => 6]),

                            FileUpload::make('icon')
                                ->image()
                                ->directory('icon') // Store icons in the "icon" directory
                                ->maxSize(2048) // 2MB max file size
                                ->helperText('Upload your custom SVG icon file')
                                ->required()
                                ->columnSpan(['default' => 6]),

                            TextInput::make('order')
                                ->integer()
                                ->default(0)
                                ->helperText('Lower numbers appear first')
                                ->columnSpan(['default' => 6]),

                            RichEditor::make('description')
                                ->columnSpan(['default' => 12]),
                        ])
                    ])->columnSpan(['default' => 8]),

                    Card::make()->schema([
                        Toggle::make('status')
                            ->label('Active')
                            ->default(true),

                        Section::make('SEO Settings')->schema([
                            Textarea::make('meta_description')
                                ->rows(3)
                                ->maxLength(160)
                                ->helperText('Maximum 160 characters recommended for SEO'),

                            Textarea::make('meta_keywords')
                                ->rows(3)
                                ->helperText('Comma separated keywords')
                        ]),
                    ])->columnSpan(['default' => 4]),
                ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->sortable()
                    ->label('ID'),

                ImageColumn::make('icon')->disk('public')->circular(),

                TextColumn::make('short_title')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('title')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('order')
                    ->sortable()
                    ->label('Order'),

                IconColumn::make('status')
                    ->boolean()
                    ->sortable()
                    ->label('Active')
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle'),

                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TernaryFilter::make('status')
                    ->label('Active')
                    ->trueLabel('Active Services')
                    ->falseLabel('Inactive Services')
                    ->placeholder('All Services')
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('order', 'asc');
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
            'view' => Pages\ViewService::route('/{record}'),
        ];
    }
}
