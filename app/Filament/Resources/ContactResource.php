<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ContactResource\Pages;
use App\Models\Contact;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Actions\Action;
use Filament\Tables\Filters\TernaryFilter;

class ContactResource extends Resource
{
    protected static ?string $model = Contact::class;
    protected static ?string $navigationGroup = 'Content Management';
    protected static ?string $navigationIcon = 'heroicon-o-envelope';

    // This will be overridden by getNavigationLabel()
    protected static ?string $navigationLabel = 'Contact Messages';

    protected static ?int $navigationSort = 3;



    // Optional: Add this method to show the badge in the navigation
    public static function getNavigationBadge(): ?string
    {
        $unreadCount = Contact::where('is_read', false)->count();

        return $unreadCount > 0 ? (string) $unreadCount : null;
    }

    // Optional: Add this method to style the badge (red for unread messages)
    public static function getNavigationBadgeColor(): ?string
    {
        return 'danger';
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->sortable(),

                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('email')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('message')
                    ->limit(50)
                    ->tooltip(function (TextColumn $column): ?string {
                        $state = $column->getState();

                        if (strlen($state) <= 50) {
                            return null;
                        }

                        // Ensure UTF-8 encoding before returning
                        return mb_convert_encoding($state, 'UTF-8', 'UTF-8');
                    }),

                IconColumn::make('is_read')
                    ->boolean()
                    ->label('Read')
                    ->sortable()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle'),

                TextColumn::make('created_at')
                    ->label('Received')
                    ->dateTime()
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                TernaryFilter::make('is_read')
                    ->label('Read Status')
                    ->placeholder('All messages')
                    ->trueLabel('Read messages')
                    ->falseLabel('Unread messages'),
            ])
            ->actions([
                Action::make('view')
                    ->label('View')
                    ->icon('heroicon-o-eye')
                    ->modalHeading(fn (Contact $record): string => "Message from {$record->name}")
                    ->modalContent(fn (Contact $record) => view('filament.resources.pages.view-message', [
                        'message' => $record->message,
                        'email' => $record->email,
                        'created_at' => $record->created_at
                    ]))
                    ->modalSubmitAction(false)
                    ->modalCancelAction(false)
                    ->after(function (Contact $record) {
                        // Mark as read when viewed
                        if (!$record->is_read) {
                            $record->update(['is_read' => true]);
                        }
                    }),

                Action::make('toggle_read')
                    ->label(fn (Contact $record): string => $record->is_read ? 'Mark as Unread' : 'Mark as Read')
                    ->icon(fn (Contact $record): string => $record->is_read ? 'heroicon-o-x-circle' : 'heroicon-o-check-circle')
                    ->action(function (Contact $record): void {
                        $record->update(['is_read' => !$record->is_read]);
                    }),

                Action::make('delete')
                    ->label('Delete')
                    ->icon('heroicon-o-trash')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->action(fn (Contact $record) => $record->delete()),
            ])
            ->bulkActions([
                Tables\Actions\BulkAction::make('mark_as_read')
                    ->label('Mark as Read')
                    ->icon('heroicon-o-check-circle')
                    ->action(fn (Tables\Actions\BulkActionGroup $actions) =>
                        Contact::whereIn('id', $actions->getRecordKeys())
                            ->update(['is_read' => true])
                    ),

                Tables\Actions\BulkAction::make('mark_as_unread')
                    ->label('Mark as Unread')
                    ->icon('heroicon-o-x-circle')
                    ->action(fn (Tables\Actions\BulkActionGroup $actions) =>
                        Contact::whereIn('id', $actions->getRecordKeys())
                            ->update(['is_read' => false])
                    ),

                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListContacts::route('/'),
        ];
    }
}