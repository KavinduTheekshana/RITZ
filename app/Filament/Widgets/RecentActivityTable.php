<?php
// app/Filament/Widgets/RecentActivityTable.php

namespace App\Filament\Widgets;

use App\Models\Client;
use App\Models\Company;
use App\Models\CompanyChatList;
use App\Models\User;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Support\Facades\DB;

class RecentActivityTable extends BaseWidget
{
    protected static ?string $heading = 'Recent Activity Feed';
    protected static ?int $sort = 10;
    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                // Fixed: Separate queries and use union differently
                Client::select([
                    'id',
                    'created_at',
                    DB::raw("CONCAT(COALESCE(first_name, ''), ' ', COALESCE(last_name, '')) as name"),
                    DB::raw("'Client Registration' as activity_type"),
                    DB::raw("'client' as type"),
                    'email as details'
                ])
                ->whereDate('created_at', '>=', now()->subDays(14))
            )
            ->columns([
                Tables\Columns\TextColumn::make('activity_type')
                    ->label('Activity')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Client Registration' => 'success',
                        'Company Registration' => 'info',
                        'Message Received' => 'warning',
                        'Blog Published' => 'primary',
                        'User Registration' => 'secondary',
                        default => 'gray',
                    }),
                
                Tables\Columns\TextColumn::make('name')
                    ->label('Name/Title')
                    ->searchable()
                    ->limit(30),
                
                Tables\Columns\TextColumn::make('details')
                    ->label('Details')
                    ->limit(50),
                
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Date & Time')
                    ->dateTime()
                    ->sortable()
                    ->since(),
            ])
            ->defaultSort('created_at', 'desc')
            ->paginated([10, 25])
            ->poll('30s');
    }
}