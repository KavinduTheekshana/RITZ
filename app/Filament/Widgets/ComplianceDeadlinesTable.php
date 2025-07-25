<?php
// app/Filament/Widgets/ComplianceDeadlinesTable.php

namespace App\Filament\Widgets;

use App\Models\Company;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Support\Facades\DB;

class ComplianceDeadlinesTable extends BaseWidget
{
    protected static ?string $heading = 'Upcoming Compliance Deadlines';
    protected static ?int $sort = 9;
    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                // Use Company model as base query - this returns Eloquent Builder
                Company::query()
                    ->select([
                        'companies.id',
                        'companies.company_name',
                        'companies.created_at',
                        DB::raw("'Company Info' as type"),
                        DB::raw("'Company data' as description"),
                        DB::raw("NOW() as deadline")
                    ])
                    ->limit(10) // Limit for performance
            )
            ->columns([
                Tables\Columns\TextColumn::make('type')
                    ->label('Type')
                    ->badge()
                    ->color('info'),
                
                Tables\Columns\TextColumn::make('company_name')
                    ->label('Company')
                    ->searchable()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('description')
                    ->label('Description'),
                
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Date')
                    ->date()
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->paginated([10, 25, 50]);
    }
}