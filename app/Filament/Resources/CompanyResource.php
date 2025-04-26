<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CompanyResource\Pages;
use App\Filament\Resources\CompanyResource\RelationManagers;
use App\Models\Company;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CompanyResource extends Resource
{
    protected static ?string $model = Company::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-office';
    protected static ?string $navigationGroup = 'Administration';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Tabs::make('Company Information')
                    ->tabs([
                        // Tab 1: Company Details
                        Forms\Components\Tabs\Tab::make('Company Details')
                            ->schema([
                                Forms\Components\Section::make('Company Details')
                                    ->schema([
                                        Forms\Components\TextInput::make('company_number')
                                            ->maxLength(255),
                                        Forms\Components\TextInput::make('company_name')
                                            ->maxLength(255),
                                        Forms\Components\TextInput::make('company_status')
                                            ->maxLength(255),
                                        Forms\Components\Select::make('company_type')
                                            ->options([
                                                'Private Limited Company' => 'Private Limited Company',
                                                'Sole Trader' => 'Sole Trader',
                                                'Partnership' => 'Partnership',
                                                'LLP' => 'LLP',
                                                'Other' => 'Other',
                                            ])
                                            ->required(),
                                        Forms\Components\DatePicker::make('incorporation_date'),
                                        Forms\Components\TextInput::make('company_trading_as')
                                            ->maxLength(255),
                                        Forms\Components\Textarea::make('registered_address')
                                            ->rows(3),
                                        Forms\Components\Textarea::make('company_postal_address')
                                            ->rows(3),
                                        Forms\Components\Select::make('invoice_address_type')
                                            ->options([
                                                'Registered Address' => 'Registered Address',
                                                'Postal Address' => 'Postal Address',
                                            ])
                                            ->default('Registered Address'),
                                        Forms\Components\TextInput::make('company_email')
                                            ->email()
                                            ->maxLength(255),
                                        Forms\Components\TextInput::make('company_email_domain')
                                            ->maxLength(255),
                                        Forms\Components\TextInput::make('company_telephone')
                                            ->tel()
                                            ->maxLength(255),
                                        Forms\Components\TextInput::make('turnover')
                                            ->numeric()
                                            ->prefix('£'),
                                        Forms\Components\DatePicker::make('date_of_trading'),
                                        Forms\Components\TextInput::make('sic_code')
                                            ->maxLength(255),
                                        Forms\Components\TextInput::make('nature_of_business')
                                            ->maxLength(255),
                                        Forms\Components\TextInput::make('corporation_tax_office')
                                            ->maxLength(255),
                                        Forms\Components\TextInput::make('company_utr')
                                            ->maxLength(255),
                                        Forms\Components\TextInput::make('companies_house_authentication_code')
                                            ->maxLength(255),
                                    ]),
                            ]),

                        // Tab 2: Internal Details
                        Forms\Components\Tabs\Tab::make('Internal Details')
                            ->schema([
                                Forms\Components\Section::make('Internal Details')
                                    ->relationship('internalDetails')
                                    ->schema([
                                        Forms\Components\TextInput::make('internal_reference')
                                            ->maxLength(255),
                                        Forms\Components\TextInput::make('allocated_office')
                                            ->maxLength(255),
                                        Forms\Components\TextInput::make('client_grade')
                                            ->maxLength(255),
                                        Forms\Components\TextInput::make('client_risk_level')
                                            ->maxLength(255),
                                        Forms\Components\Textarea::make('notes')
                                            ->rows(3),
                                        Forms\Components\Textarea::make('urgent')
                                            ->rows(3),
                                    ]),

                            ]),

                        // Tab 3: Income Details
                        Forms\Components\Tabs\Tab::make('Income Details')
                            ->schema([
                                Forms\Components\Section::make('Income Details')
                                    ->relationship('incomeDetails')
                                    ->schema([
                                        Forms\Components\Textarea::make('previous')
                                            ->rows(3),
                                        Forms\Components\Textarea::make('current')
                                            ->rows(3),
                                        Forms\Components\Textarea::make('ir_35_notes')
                                            ->rows(3),
                                    ]),
                            ]),

                        // Tab 4: Previous Accountant
                        Forms\Components\Tabs\Tab::make('Previous Accountant')
                            ->schema([
                                Forms\Components\Section::make('Previous Accountant Details')
                                    ->relationship('previousAccountantDetails')
                                    ->schema([
                                        Forms\Components\Toggle::make('clearance_required')
                                            ->default(false),
                                        Forms\Components\TextInput::make('accountant_email_address')
                                            ->email()
                                            ->maxLength(255),
                                        Forms\Components\Textarea::make('accountant_details')
                                            ->rows(3),
                                        Forms\Components\DatePicker::make('send_first_clearance_email'),
                                        Forms\Components\TextInput::make('automatically_request_every')
                                            ->maxLength(255),
                                        Forms\Components\DatePicker::make('last_requested'),
                                        Forms\Components\Toggle::make('information_received')
                                            ->default(false),
                                    ]),
                            ]),

                        // Tab 5: Services Required
                        Forms\Components\Tabs\Tab::make('Services Required')
                            ->schema([
                                Forms\Components\Section::make('Services Required')
                                    ->relationship('servicesRequired')
                                    ->schema([
                                        Forms\Components\TextInput::make('accounts')
                                            ->numeric()
                                            ->prefix('£'),
                                        Forms\Components\TextInput::make('bookkeeping')
                                            ->numeric()
                                            ->prefix('£'),
                                        Forms\Components\TextInput::make('ct600_return')
                                            ->numeric()
                                            ->prefix('£'),
                                        Forms\Components\TextInput::make('payroll')
                                            ->numeric()
                                            ->prefix('£'),
                                        Forms\Components\TextInput::make('auto_enrolment')
                                            ->numeric()
                                            ->prefix('£'),
                                        Forms\Components\TextInput::make('vat_returns')
                                            ->numeric()
                                            ->prefix('£'),
                                        Forms\Components\TextInput::make('management_accounts')
                                            ->numeric()
                                            ->prefix('£'),
                                        Forms\Components\TextInput::make('confirmation_statement')
                                            ->numeric()
                                            ->prefix('£'),
                                        Forms\Components\TextInput::make('cis')
                                            ->numeric()
                                            ->prefix('£'),
                                        Forms\Components\TextInput::make('p11d')
                                            ->numeric()
                                            ->prefix('£'),
                                        Forms\Components\TextInput::make('fee_protection_service')
                                            ->numeric()
                                            ->prefix('£'),
                                        Forms\Components\TextInput::make('registered_address')
                                            ->numeric()
                                            ->prefix('£'),
                                        Forms\Components\TextInput::make('bill_payment')
                                            ->numeric()
                                            ->prefix('£'),
                                        Forms\Components\TextInput::make('consultation_advice')
                                            ->numeric()
                                            ->prefix('£'),
                                        Forms\Components\TextInput::make('software')
                                            ->numeric()
                                            ->prefix('£'),
                                        Forms\Components\TextInput::make('annual_charge')
                                            ->numeric()
                                            ->prefix('£'),
                                        Forms\Components\TextInput::make('monthly_charge')
                                            ->numeric()
                                            ->prefix('£'),
                                    ]),
                            ]),

                        // Tab 6: Accounts and Returns
                        Forms\Components\Tabs\Tab::make('Accounts & Returns')
                            ->schema([
                                Forms\Components\Section::make('Accounts and Returns Details')
                                    ->relationship('accountsAndReturnsDetails')
                                    ->schema([
                                        Forms\Components\DatePicker::make('accounts_period_end'),
                                        Forms\Components\DatePicker::make('ch_year_end'),
                                        Forms\Components\DatePicker::make('hmrc_year_end'),
                                        Forms\Components\DatePicker::make('ch_accounts_next_due'),
                                        Forms\Components\DatePicker::make('ct600_due'),
                                        Forms\Components\TextInput::make('corporation_tax_amount_due')
                                            ->numeric()
                                            ->prefix('£'),
                                        Forms\Components\DatePicker::make('tax_due_hmrc_year_end'),
                                        Forms\Components\TextInput::make('ct_payment_reference')
                                            ->maxLength(255),
                                        Forms\Components\TextInput::make('tax_office')
                                            ->maxLength(255),
                                        Forms\Components\Toggle::make('companies_house_email_reminder')
                                            ->default(false),
                                        Forms\Components\TextInput::make('accounts_latest_action')
                                            ->maxLength(255),
                                        Forms\Components\DatePicker::make('accounts_latest_action_date'),
                                        Forms\Components\DatePicker::make('accounts_records_received'),
                                        Forms\Components\Textarea::make('accounts_progress_note')
                                            ->rows(3),
                                    ]),
                            ]),

                        // Tab 7: Confirmation Statement
                        Forms\Components\Tabs\Tab::make('Confirmation Statement')
                            ->schema([
                                Forms\Components\Section::make('Confirmation Statement Details')
                                    ->relationship('confirmationStatementDetails')
                                    ->schema([
                                        Forms\Components\DatePicker::make('confirmation_statement_date'),
                                        Forms\Components\DatePicker::make('confirmation_statement_due'),
                                        Forms\Components\TextInput::make('latest_action')
                                            ->maxLength(255),
                                        Forms\Components\DatePicker::make('latest_action_date'),
                                        Forms\Components\DatePicker::make('records_received'),
                                        Forms\Components\Textarea::make('progress_note')
                                            ->rows(3),
                                        Forms\Components\Textarea::make('officers')
                                            ->rows(3),
                                        Forms\Components\Textarea::make('share_capital')
                                            ->rows(3),
                                        Forms\Components\Textarea::make('shareholders')
                                            ->rows(3),
                                        Forms\Components\Textarea::make('people_with_significant_control')
                                            ->rows(3),
                                    ]),
                            ]),

                        // Tab 8: VAT Details
                        Forms\Components\Tabs\Tab::make('VAT Details')
                            ->schema([
                                Forms\Components\Section::make('VAT Details')
                                    ->relationship('vatDetails')
                                    ->schema([
                                        Forms\Components\TextInput::make('vat_frequency')
                                            ->maxLength(255),
                                        Forms\Components\DatePicker::make('vat_period_end'),
                                        Forms\Components\DatePicker::make('next_return_due'),
                                        Forms\Components\TextInput::make('vat_bill_amount')
                                            ->numeric()
                                            ->prefix('£'),
                                        Forms\Components\DatePicker::make('vat_bill_due'),
                                        Forms\Components\TextInput::make('latest_action')
                                            ->maxLength(255),
                                        Forms\Components\DatePicker::make('latest_action_date'),
                                        Forms\Components\DatePicker::make('records_received'),
                                        Forms\Components\Textarea::make('progress_note')
                                            ->rows(3),
                                        Forms\Components\TextInput::make('vat_member_state')
                                            ->maxLength(255),
                                        Forms\Components\TextInput::make('vat_number')
                                            ->maxLength(255),
                                        Forms\Components\Textarea::make('vat_address')
                                            ->rows(3),
                                        Forms\Components\DatePicker::make('date_of_registration'),
                                        Forms\Components\DatePicker::make('effective_date'),
                                        Forms\Components\TextInput::make('estimated_turnover')
                                            ->numeric()
                                            ->prefix('£'),
                                        Forms\Components\DatePicker::make('applied_for_mtd'),
                                        Forms\Components\Toggle::make('mtd_ready')
                                            ->default(false),
                                        Forms\Components\Toggle::make('transfer_of_going_concern')
                                            ->default(false),
                                        Forms\Components\Toggle::make('involved_in_other_businesses')
                                            ->default(false),
                                        Forms\Components\Toggle::make('direct_debit')
                                            ->default(false),
                                        Forms\Components\Toggle::make('standard_scheme')
                                            ->default(false),
                                        Forms\Components\Toggle::make('cash_accounting_scheme')
                                            ->default(false),
                                        Forms\Components\Toggle::make('retail_scheme')
                                            ->default(false),
                                        Forms\Components\Toggle::make('margin_scheme')
                                            ->default(false),
                                        Forms\Components\Toggle::make('flat_rate')
                                            ->default(false),
                                        Forms\Components\TextInput::make('flat_rate_category')
                                            ->maxLength(255),
                                        Forms\Components\TextInput::make('month_of_last_quarter_submitted')
                                            ->maxLength(255),
                                        Forms\Components\TextInput::make('box_5_of_last_quarter_submitted')
                                            ->maxLength(255),
                                        Forms\Components\Textarea::make('general_notes')
                                            ->rows(3),
                                    ]),
                            ]),

                        // Tab 9: PAYE Details
                        Forms\Components\Tabs\Tab::make('PAYE Details')
                            ->schema([
                                Forms\Components\Section::make('PAYE Details')
                                    ->relationship('payeDetails')
                                    ->schema([
                                        Forms\Components\TextInput::make('employers_reference')
                                            ->maxLength(255),
                                        Forms\Components\TextInput::make('accounts_office_reference')
                                            ->maxLength(255),
                                        Forms\Components\TextInput::make('years_required')
                                            ->maxLength(255),
                                        Forms\Components\TextInput::make('paye_frequency')
                                            ->maxLength(255),
                                        Forms\Components\Toggle::make('irregular_monthly_pay')
                                            ->default(false),
                                        Forms\Components\Toggle::make('nil_eps')
                                            ->default(false),
                                        Forms\Components\TextInput::make('number_of_employees')
                                            ->numeric(),
                                        Forms\Components\Textarea::make('salary_details')
                                            ->rows(3),
                                        Forms\Components\DatePicker::make('first_pay_date'),
                                        Forms\Components\DatePicker::make('rti_deadline'),
                                        Forms\Components\DatePicker::make('paye_scheme_ceased'),
                                        Forms\Components\TextInput::make('paye_latest_action')
                                            ->maxLength(255),
                                        Forms\Components\DatePicker::make('paye_latest_action_date'),
                                        Forms\Components\DatePicker::make('paye_records_received'),
                                        Forms\Components\Textarea::make('paye_progress_note')
                                            ->rows(3),
                                        Forms\Components\Textarea::make('general_notes')
                                            ->rows(3),
                                    ]),
                            ]),

                        // Tab 10: Auto-Enrolment Details
                        Forms\Components\Tabs\Tab::make('Auto-Enrolment')
                            ->schema([
                                Forms\Components\Section::make('Auto-Enrolment Details')
                                    ->relationship('autoEnrolmentDetails')
                                    ->schema([
                                        Forms\Components\TextInput::make('latest_action')
                                            ->maxLength(255),
                                        Forms\Components\DatePicker::make('latest_action_date'),
                                        Forms\Components\DatePicker::make('records_received'),
                                        Forms\Components\Textarea::make('progress_note')
                                            ->rows(3),
                                        Forms\Components\DatePicker::make('staging'),
                                        Forms\Components\DatePicker::make('postponement_date'),
                                        Forms\Components\DatePicker::make('tpr_opt_out_date'),
                                        Forms\Components\DatePicker::make('re_enrolment_date'),
                                        Forms\Components\TextInput::make('pension_provider')
                                            ->maxLength(255),
                                        Forms\Components\TextInput::make('pension_id')
                                            ->maxLength(255),
                                        Forms\Components\DatePicker::make('declaration_of_compliance_due'),
                                        Forms\Components\DatePicker::make('declaration_of_compliance_submission'),
                                    ]),
                            ]),

                        // Tab 11: P11D Details
                        Forms\Components\Tabs\Tab::make('P11D Details')
                            ->schema([
                                Forms\Components\Section::make('P11D Details')
                                    ->relationship('p11dDetails')
                                    ->schema([
                                        Forms\Components\DatePicker::make('next_p11d_return_due'),
                                        Forms\Components\DatePicker::make('latest_p11d_submitted'),
                                        Forms\Components\TextInput::make('latest_action')
                                            ->maxLength(255),
                                        Forms\Components\DatePicker::make('latest_action_date'),
                                        Forms\Components\DatePicker::make('records_received'),
                                        Forms\Components\Textarea::make('progress_note')
                                            ->rows(3),
                                    ]),
                            ]),

                        // Tab 12: Registration Details
                        Forms\Components\Tabs\Tab::make('Registration')
                            ->schema([
                                Forms\Components\Section::make('Registration Details')
                                    ->relationship('registrationDetails')
                                    ->schema([
                                        Forms\Components\Toggle::make('terms_signed_registration_fee_paid')
                                            ->default(false),
                                        Forms\Components\TextInput::make('fee')
                                            ->numeric()
                                            ->prefix('£'),
                                        Forms\Components\DatePicker::make('letter_of_engagement_signed'),
                                        Forms\Components\Toggle::make('money_laundering_complete')
                                            ->default(false),
                                        Forms\Components\DatePicker::make('registration_64_8'),
                                    ]),
                            ]),

                        // Tab 13: Other Details
                        Forms\Components\Tabs\Tab::make('Other Details')
                            ->schema([
                                Forms\Components\Section::make('Other Details')
                                    ->relationship('otherDetails')
                                    ->schema([
                                        Forms\Components\TextInput::make('referred_by')
                                            ->maxLength(255),
                                        Forms\Components\DatePicker::make('initial_contact'),
                                        Forms\Components\DatePicker::make('proposal_email_sent'),
                                        Forms\Components\DatePicker::make('welcome_email'),
                                        Forms\Components\TextInput::make('accounting_system')
                                            ->maxLength(255),
                                        Forms\Components\TextInput::make('profession')
                                            ->maxLength(255),
                                        Forms\Components\TextInput::make('website')
                                            ->url()
                                            ->maxLength(255),
                                        Forms\Components\TextInput::make('twitter_handle')
                                            ->maxLength(255),
                                        Forms\Components\TextInput::make('facebook_url')
                                            ->url()
                                            ->maxLength(255),
                                        Forms\Components\TextInput::make('linkedin_url')
                                            ->url()
                                            ->maxLength(255),
                                        Forms\Components\TextInput::make('instagram_handle')
                                            ->maxLength(255),
                                    ]),
                            ]),
                    ])
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('company_name')
                    ->label('Company Name')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('company_number')
                    ->label('Company Number')
                    ->searchable(),

                Tables\Columns\TextColumn::make('company_status')
                    ->label('Status')
                    ->sortable(),

                Tables\Columns\TextColumn::make('company_type')
                    ->label('Type')
                    ->sortable(),

                Tables\Columns\TextColumn::make('incorporation_date')
                    ->label('Incorporation Date')
                    ->date()
                    ->sortable(),

                Tables\Columns\TextColumn::make('company_email')
                    ->label('Email')
                    ->searchable(),

                Tables\Columns\TextColumn::make('company_telephone')
                    ->label('Telephone')
                    ->searchable(),

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
                Tables\Filters\SelectFilter::make('company_type')
                    ->options([
                        'Private Limited Company' => 'Private Limited Company',
                        'Sole Trader' => 'Sole Trader',
                        'Partnership' => 'Partnership',
                        'LLP' => 'LLP',
                        'Other' => 'Other',
                    ]),

                Tables\Filters\SelectFilter::make('company_status')
                    ->options([
                        'Active' => 'Active',
                        'Dormant' => 'Dormant',
                        'Dissolved' => 'Dissolved',
                        'Liquidation' => 'Liquidation',
                    ]),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
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
            RelationManagers\ClientsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCompanies::route('/'),
            'create' => Pages\CreateCompany::route('/create'),
            'edit' => Pages\EditCompany::route('/{record}/edit'),
        ];
    }
}
