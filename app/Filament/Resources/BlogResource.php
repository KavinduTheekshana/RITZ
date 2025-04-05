<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BlogResource\Pages;
use App\Filament\Resources\BlogResource\RelationManagers;
use App\Models\Blog;
use Filament\Forms;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class BlogResource extends Resource
{
    protected static ?string $model = Blog::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationGroup = 'Content Management';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
        ->schema([
            Grid::make(['default' => 12])->schema([
                Card::make()->schema([
                    Grid::make(['default' => 12])->schema([
                        TextInput::make('title')
                            ->required()
                            ->maxLength(255)
                            ->reactive()
                            ->afterStateUpdated(function ($state, callable $set) {
                                $set('slug', Str::slug($state));
                            })
                            ->columnSpan(['default' => 12]),

                        TextInput::make('slug')
                            ->required()
                            ->maxLength(255)
                            ->unique(Blog::class, 'slug', fn ($record) => $record)
                            ->columnSpan(['default' => 12]),

                        FileUpload::make('image')
                            ->directory('blog')
                            ->image()
                            ->required()
                            ->columnSpan(['default' => 12]),

                        RichEditor::make('content')
                            ->required()
                            ->columnSpan(['default' => 12]),

                        TextInput::make('author')
                            ->required()
                            ->maxLength(255)
                            ->columnSpan(['default' => 6]),

                        Select::make('category')
                            ->options([
                                'Technology' => 'Technology',
                                'Marketing' => 'Marketing',
                                'Design' => 'Design',
                                'Development' => 'Development',
                                'Business' => 'Business',
                                'Other' => 'Other',
                            ])
                            ->required()
                            ->columnSpan(['default' => 6]),

                        TagsInput::make('tags')
                            ->separator(',')
                            ->columnSpan(['default' => 12]),
                    ]),
                ])->columnSpan(['default' => 8]),

                Card::make()->schema([
                    Toggle::make('status')
                        ->label('Published')
                        ->default(true),

                    Section::make('SEO Information')->schema([
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
                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\ImageColumn::make('image'),

                Tables\Columns\TextColumn::make('author')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('category')
                    ->searchable()
                    ->sortable(),

                    Tables\Columns\IconColumn::make('status')
                    ->label('Status')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger'),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Published Date')
                    ->date()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        '1' => 'Published',
                        '0' => 'Draft',
                    ]),

                Tables\Filters\SelectFilter::make('category')
                    ->options([
                        'Technology' => 'Technology',
                        'Marketing' => 'Marketing',
                        'Design' => 'Design',
                        'Development' => 'Development',
                        'Business' => 'Business',
                        'Other' => 'Other',
                    ]),
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
            ->defaultSort('created_at', 'desc');
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
            'index' => Pages\ListBlogs::route('/'),
            'create' => Pages\CreateBlog::route('/create'),
            'edit' => Pages\EditBlog::route('/{record}/edit'),
            'view' => Pages\ViewBlog::route('/{record}'),
        ];
    }
}
