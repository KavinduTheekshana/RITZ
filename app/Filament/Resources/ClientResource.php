<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ClientResource\Pages;
use App\Filament\Resources\ClientResource\RelationManagers;
use App\Models\Client;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\Mail\SendLoginDetails;
use Filament\Notifications\Notification;

class ClientResource extends Resource
{
    protected static ?string $model = Client::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-plus';
    protected static ?string $navigationGroup = 'Tax Management';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Personal Information')
                    ->schema([
                        Forms\Components\Select::make('title')
                            ->options([
                                'Mr' => 'Mr',
                                'Mrs' => 'Mrs',
                                'Miss' => 'Miss',
                                'Ms' => 'Ms',
                                'Dr' => 'Dr',
                                'Prof' => 'Prof',
                            ])
                            ->columnSpan(['default' => 1, 'md' => 1]),
                        Forms\Components\TextInput::make('first_name')
                            ->maxLength(255)
                            ->columnSpan(['default' => 1, 'md' => 1]),
                        Forms\Components\TextInput::make('middle_name')
                            ->maxLength(255)
                            ->columnSpan(['default' => 1, 'md' => 1]),
                        Forms\Components\TextInput::make('last_name')
                            ->maxLength(255)
                            ->columnSpan(['default' => 1, 'md' => 1]),
                        Forms\Components\TextInput::make('preferred_name')
                            ->maxLength(255)
                            ->columnSpan(['default' => 2, 'md' => 2]),
                        Forms\Components\DatePicker::make('date_of_birth')
                            ->columnSpan(['default' => 1, 'md' => 1]),
                        Forms\Components\DatePicker::make('deceased')
                            ->columnSpan(['default' => 1, 'md' => 1]),
                        Forms\Components\TextInput::make('nationality')
                            ->maxLength(255)
                            ->columnSpan(['default' => 1, 'md' => 1]),
                        Forms\Components\Select::make('marital_status')
                            ->options([
                                'Single' => 'Single',
                                'Married' => 'Married',
                                'Divorced' => 'Divorced',
                                'Widowed' => 'Widowed',
                                'Separated' => 'Separated',
                                'Civil Partnership' => 'Civil Partnership',
                            ])
                            ->columnSpan(['default' => 1, 'md' => 1]),
                        Forms\Components\TextInput::make('preferred_language')
                            ->maxLength(255)
                            ->columnSpan(['default' => 1, 'md' => 1]),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Contact Information')
                    ->schema([
                        Forms\Components\TextInput::make('email')
                            ->email()
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true)
                            ->columnSpan(['default' => 1, 'md' => 1]),
                        Forms\Components\TextInput::make('telephone_number')
                            ->tel()
                            ->maxLength(255)
                            ->columnSpan(['default' => 1, 'md' => 1]),
                        Forms\Components\TextInput::make('mobile_number')
                            ->tel()
                            ->maxLength(255)
                            ->columnSpan(['default' => 1, 'md' => 1]),
                        Forms\Components\Textarea::make('postal_address')
                            ->columnSpan(['default' => 2, 'md' => 2])
                            ->rows(3),
                        Forms\Components\Textarea::make('previous_address')
                            ->columnSpan(['default' => 2, 'md' => 2])
                            ->rows(3),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Tax Information')
                    ->schema([
                        Forms\Components\TextInput::make('ni_number')
                            ->label('National Insurance Number')
                            ->maxLength(255)
                            ->columnSpan(['default' => 1, 'md' => 1]),
                        Forms\Components\TextInput::make('personal_utr_number')
                            ->label('UTR Number')
                            ->maxLength(255)
                            ->columnSpan(['default' => 1, 'md' => 1]),
                        Forms\Components\Toggle::make('create_self_assessment_client')
                            ->label('Create Self Assessment Client')
                            ->columnSpan(['default' => 1, 'md' => 1]),
                        Forms\Components\Toggle::make('client_does_their_own_sa')
                            ->label('Client Does Their Own Self Assessment')
                            ->columnSpan(['default' => 1, 'md' => 1]),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Verification')
                    ->schema([
                        Forms\Components\DatePicker::make('terms_signed')
                            ->columnSpan(['default' => 1, 'md' => 1]),
                        Forms\Components\Toggle::make('photo_id_verified')
                            ->columnSpan(['default' => 1, 'md' => 1]),
                        Forms\Components\Toggle::make('address_verified')
                            ->columnSpan(['default' => 1, 'md' => 1]),
                    ])
                    ->columns(3),

                // Forms\Components\Section::make('Account Information')
                //     ->schema([
                //         Forms\Components\TextInput::make('password')
                //             ->password()
                //             ->dehydrateStateUsing(fn(string $state): string => Hash::make($state))
                //             ->dehydrated(fn(?string $state): bool => filled($state))
                //             ->required(fn(string $operation): bool => $operation === 'create')
                //             ->columnSpan(['default' => 1, 'md' => 1]),
                //     ])
                //     ->columns(1)
                //     ->visible(fn(string $operation): bool => $operation === 'create'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('full_name')
                    ->sortable()
                    ->searchable(['first_name', 'last_name'])
                    ->getStateUsing(function ($record) {
                        return "{$record->title} {$record->first_name} {$record->last_name}";
                    }),
                Tables\Columns\TextColumn::make('email')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('mobile_number')
                    ->searchable(),
                Tables\Columns\BooleanColumn::make('password_mail')
                    ->label('Password Mail'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('marital_status')
                    ->options([
                        'Single' => 'Single',
                        'Married' => 'Married',
                        'Divorced' => 'Divorced',
                        'Widowed' => 'Widowed',
                        'Separated' => 'Separated',
                        'Civil Partnership' => 'Civil Partnership',
                    ]),
                Tables\Filters\TernaryFilter::make('photo_id_verified')
                    ->label('ID Verified'),
                Tables\Filters\TernaryFilter::make('address_verified')
                    ->label('Address Verified'),
                Tables\Filters\TernaryFilter::make('create_self_assessment_client')
                    ->label('Self Assessment Client'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make(),
                Tables\Actions\Action::make('send_login_details')
                    ->label('Send Login Details')
                    ->icon('heroicon-o-envelope')
                    ->color('success')
                    ->requiresConfirmation()
                    ->modalHeading('Send Login Details')
                    ->modalDescription('Generate a password and send login details to this client via email.')
                    ->modalSubmitActionLabel('Generate & Send')
                    ->form([
                        Forms\Components\Placeholder::make('email_info')
                            ->content(fn(Client $record): string => "Email will be sent to: {$record->email}"),
                        Forms\Components\TextInput::make('generated_password')
                            ->label('Generated Password')
                            ->default(fn(): string => Str::password(10))
                            ->dehydrated(true) // Make sure this field is included in form data
                            ->readOnly() // Better than disabled for this use case
                            ->helperText('This password will be hashed before saving'),
                    ])
                    ->action(function (Client $record, array $data): void {
                        // Add debugging to check what data is available
                        // dd($data); // Uncomment this line to debug

                        // Make sure generated_password exists
                        if (!isset($data['generated_password'])) {
                            Notification::make()
                                ->title('Error: Password not generated')
                                ->danger()
                                ->send();
                            return;
                        }

                        // Hash the password
                        $record->password = Hash::make($data['generated_password']);
                        $record->save();

                        // Send email with login details
                        Mail::to($record->email)->send(new SendLoginDetails(
                            client: $record,
                            password: $data['generated_password']
                        ));

                        // Update the engagement status
                        $record->update(['password_mail' => true]);


                        Notification::make()
                            ->title('Login details sent successfully')
                            ->success()
                            ->send();
                    })
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\CompaniesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListClients::route('/'),
            'create' => Pages\CreateClient::route('/create'),
            'edit' => Pages\EditClient::route('/{record}/edit'),
            'view' => Pages\ViewClient::route('/{record}'),
        ];
    }
}
