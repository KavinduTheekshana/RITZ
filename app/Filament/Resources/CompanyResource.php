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
                        Forms\Components\Tabs\Tab::make('Company Information')
                            ->schema([
                                Forms\Components\Section::make('Company Information')
                                    ->schema([
                                        Forms\Components\TextInput::make('company_name')
                                            ->label('Company Name'),
                                        Forms\Components\TextInput::make('company_number')
                                            ->label('Company Number'),
                                        Forms\Components\Select::make('company_status')
                                            ->label('Company Status')
                                            ->options([
                                                'Active' => 'Active',
                                                'Dissolved' => 'Dissolved',
                                                'Liquidation' => 'Liquidation',
                                                'Dormant' => 'Dormant',
                                            ]),
                                        Forms\Components\Select::make('company_type')
                                            ->label('Company Type')
                                            ->options([
                                                'Private Limited Company' => 'Private Limited Company',
                                                'Sole Trader' => 'Sole Trader',
                                                'Partnership' => 'Partnership',
                                                'LLP' => 'LLP',
                                                'Other' => 'Other',
                                            ])
                                            ->required(),
                                        Forms\Components\DatePicker::make('incorporation_date')
                                            ->label('Incorporation Date'),
                                        Forms\Components\TextInput::make('company_trading_as')
                                            ->label('Trading As'),
                                        Forms\Components\DatePicker::make('date_of_trading')
                                            ->label('Date of Trading'),
                                    ])->columns(2),

                                Forms\Components\Section::make('Business Details')
                                    ->schema([
                                        Forms\Components\TextInput::make('sic_code')
                                            ->label('SIC Code'),
                                        Forms\Components\TextInput::make('nature_of_business')
                                            ->label('Nature of Business'),
                                        Forms\Components\TextInput::make('turnover')
                                            ->label('Turnover')
                                            ->numeric()
                                            ->prefix('£'),
                                    ])->columns(2),

                                Forms\Components\Section::make('Contact Information')
                                    ->schema([
                                        Forms\Components\Textarea::make('registered_address')
                                            ->label('Registered Address')
                                            ->rows(3),
                                        Forms\Components\Textarea::make('company_postal_address')
                                            ->label('Postal Address')
                                            ->rows(3),
                                        Forms\Components\Select::make('invoice_address_type')
                                            ->label('Invoice Address Type')
                                            ->options([
                                                'Registered Address' => 'Registered Address',
                                                'Postal Address' => 'Postal Address',
                                            ])
                                            ->default('Registered Address'),
                                        Forms\Components\TextInput::make('company_email')
                                            ->label('Company Email')
                                            ->email(),
                                        Forms\Components\TextInput::make('company_email_domain')
                                            ->label('Email Domain'),
                                        Forms\Components\TextInput::make('company_telephone')
                                            ->label('Company Telephone')
                                            ->tel(),
                                    ])->columns(2),

                                Forms\Components\Section::make('Tax Information')
                                    ->schema([
                                        Forms\Components\TextInput::make('corporation_tax_office')
                                            ->label('Corporation Tax Office'),
                                        Forms\Components\TextInput::make('company_utr')
                                            ->label('Company UTR'),
                                        Forms\Components\TextInput::make('companies_house_authentication_code')
                                            ->label('Companies House Authentication Code'),
                                    ])->columns(2),
                            ]),

                        // Tab 2: Internal Details
                        Forms\Components\Tabs\Tab::make('Internal Details')
                            ->schema([
                                Forms\Components\Section::make('Internal Details')
                                    ->relationship('internalDetails')
                                    ->schema([
                                        Forms\Components\TextInput::make('internal_reference')
                                            ->label('Internal Reference')
                                            ->maxLength(255),
                                        Forms\Components\Select::make('allocated_office')
                                            ->label('Allocated Office')
                                            ->options([
                                                'London' => 'London',
                                                'Manchester' => 'Manchester',
                                                'Birmingham' => 'Birmingham',
                                                'Edinburgh' => 'Edinburgh',
                                                'Cardiff' => 'Cardiff',
                                            ])
                                            ->searchable(),
                                        Forms\Components\Select::make('client_grade')
                                            ->label('Client Grade')
                                            ->options([
                                                'A' => 'A',
                                                'B' => 'B',
                                                'C' => 'C',
                                            ]),
                                        Forms\Components\Select::make('client_risk_level')
                                            ->label('Client Risk Level')
                                            ->options([
                                                'Low' => 'Low',
                                                'Medium' => 'Medium',
                                                'High' => 'High',
                                            ]),
                                        Forms\Components\Textarea::make('notes')
                                            ->label('Notes')
                                            ->rows(3),
                                        Forms\Components\Textarea::make('urgent')
                                            ->label('Urgent Notes')
                                            ->rows(3),
                                    ])->columns(2),

                            ]),

                        // Tab 3: Income Details
                        Forms\Components\Tabs\Tab::make('Income Details')
                            ->schema([
                                Forms\Components\Section::make('Income Details')
                                    ->relationship('incomeDetails')
                                    ->schema([
                                        Forms\Components\Textarea::make('previous')
                                            ->label('Previous Income Details')
                                            ->rows(4),
                                        Forms\Components\Textarea::make('current')
                                            ->label('Current Income Details')
                                            ->rows(4),
                                        Forms\Components\Textarea::make('ir_35_notes')
                                            ->label('IR35 Notes')
                                            ->rows(4)
                                            ->helperText('Enter any IR35 related information here'),
                                    ])->columns(1),
                            ]),

                        // Tab 4: Previous Accountant
                        Forms\Components\Tabs\Tab::make('Previous Accountant')
                            ->schema([
                                Forms\Components\Section::make('Previous Accountant Details')
                                    ->relationship('previousAccountantDetails')
                                    ->schema([
                                        Forms\Components\TextInput::make('accountant_email_address')
                                            ->label('Accountant Email Address')
                                            ->email()
                                            ->maxLength(255),
                                        Forms\Components\Textarea::make('accountant_details')
                                            ->label('Accountant Details')
                                            ->rows(3),
                                        Forms\Components\DatePicker::make('send_first_clearance_email')
                                            ->label('Send First Clearance Email'),
                                        Forms\Components\Select::make('automatically_request_every')
                                            ->label('Automatically Request Every')
                                            ->options([
                                                '7 days' => '7 days',
                                                '14 days' => '14 days',
                                                '30 days' => '30 days',
                                                '60 days' => '60 days',
                                                'Never' => 'Never',
                                            ]),
                                        Forms\Components\DatePicker::make('last_requested')
                                            ->label('Last Requested')
                                            ->disabled(),
                                        Forms\Components\Toggle::make('clearance_required')
                                            ->label('Clearance Required')
                                            ->default(false),
                                        Forms\Components\Toggle::make('information_received')
                                            ->label('Information Received')
                                            ->default(false),
                                    ])->columns(2),
                            ]),

                        // Tab 5: Services Required
                        Forms\Components\Tabs\Tab::make('Services Required')
                            ->schema([
                                Forms\Components\Section::make('Services Required Details')
                                    ->relationship('servicesRequired')
                                    ->schema([
                                        Forms\Components\Section::make('Services Required')
                                            ->schema([
                                                Forms\Components\TextInput::make('accounts')
                                                    ->label('Accounts')
                                                    ->prefix('£')
                                                    ->numeric()
                                                    ->maxValue(999999999.99),
                                                Forms\Components\TextInput::make('bookkeeping')
                                                    ->label('Bookkeeping')
                                                    ->prefix('£')
                                                    ->numeric()
                                                    ->maxValue(999999999.99),
                                                Forms\Components\TextInput::make('ct600_return')
                                                    ->label('CT600 Return')
                                                    ->prefix('£')
                                                    ->numeric()
                                                    ->maxValue(999999999.99),
                                                Forms\Components\TextInput::make('payroll')
                                                    ->label('Payroll')
                                                    ->prefix('£')
                                                    ->numeric()
                                                    ->maxValue(999999999.99),
                                                Forms\Components\TextInput::make('auto_enrolment')
                                                    ->label('Auto Enrolment')
                                                    ->prefix('£')
                                                    ->numeric()
                                                    ->maxValue(999999999.99),
                                                Forms\Components\TextInput::make('vat_returns')
                                                    ->label('VAT Returns')
                                                    ->prefix('£')
                                                    ->numeric()
                                                    ->maxValue(999999999.99),
                                                Forms\Components\TextInput::make('management_accounts')
                                                    ->label('Management Accounts')
                                                    ->prefix('£')
                                                    ->numeric()
                                                    ->maxValue(999999999.99),
                                                Forms\Components\TextInput::make('confirmation_statement')
                                                    ->label('Confirmation Statement')
                                                    ->prefix('£')
                                                    ->numeric()
                                                    ->maxValue(999999999.99),
                                                Forms\Components\TextInput::make('cis')
                                                    ->label('CIS')
                                                    ->prefix('£')
                                                    ->numeric()
                                                    ->maxValue(999999999.99),
                                                Forms\Components\TextInput::make('p11d')
                                                    ->label('P11D')
                                                    ->prefix('£')
                                                    ->numeric()
                                                    ->maxValue(999999999.99),
                                                Forms\Components\TextInput::make('fee_protection_service')
                                                    ->label('Fee Protection Service')
                                                    ->prefix('£')
                                                    ->numeric()
                                                    ->maxValue(999999999.99),
                                                Forms\Components\TextInput::make('registered_address')
                                                    ->label('Registered Address')
                                                    ->prefix('£')
                                                    ->numeric()
                                                    ->maxValue(999999999.99),
                                                Forms\Components\TextInput::make('bill_payment')
                                                    ->label('Bill Payment')
                                                    ->prefix('£')
                                                    ->numeric()
                                                    ->maxValue(999999999.99),
                                                Forms\Components\TextInput::make('consultation_advice')
                                                    ->label('Consultation/Advice')
                                                    ->prefix('£')
                                                    ->numeric()
                                                    ->maxValue(999999999.99),
                                                Forms\Components\TextInput::make('software')
                                                    ->label('Software')
                                                    ->prefix('£')
                                                    ->numeric()
                                                    ->maxValue(999999999.99),
                                            ])->columns(3),

                                        Forms\Components\Section::make('Totals')
                                            ->schema([
                                                Forms\Components\TextInput::make('annual_charge')
                                                    ->label('Annual Charge')
                                                    ->prefix('£')
                                                    ->numeric()
                                                    ->maxValue(999999999.99),
                                                Forms\Components\TextInput::make('monthly_charge')
                                                    ->label('Monthly Charge')
                                                    ->prefix('£')
                                                    ->numeric()
                                                    ->maxValue(999999999.99),
                                            ])->columns(2),

                                    ]),
                            ]),

                        // Tab 6: Accounts and Returns
                        Forms\Components\Tabs\Tab::make('Accounts & Returns')
                            ->schema([
                                Forms\Components\Section::make('Accounts and Returns Details')
                                    ->relationship('accountsAndReturnsDetails')
                                    ->schema([


                                        Forms\Components\Section::make('Key Dates')
                                            ->schema([
                                                Forms\Components\DatePicker::make('accounts_period_end')
                                                    ->label('Accounts Period End'),
                                                Forms\Components\DatePicker::make('ch_year_end')
                                                    ->label('Companies House Year End'),
                                                Forms\Components\DatePicker::make('hmrc_year_end')
                                                    ->label('HMRC Year End'),
                                                Forms\Components\DatePicker::make('ch_accounts_next_due')
                                                    ->label('CH Accounts Next Due'),
                                                Forms\Components\DatePicker::make('ct600_due')
                                                    ->label('CT600 Due Date'),
                                                Forms\Components\DatePicker::make('tax_due_hmrc_year_end')
                                                    ->label('Tax Due (HMRC Year End)'),
                                                Forms\Components\DatePicker::make('accounts_records_received')
                                                    ->label('Accounts Records Received'),
                                            ])->columns(2),

                                        Forms\Components\Section::make('Tax Information')
                                            ->schema([
                                                Forms\Components\TextInput::make('corporation_tax_amount_due')
                                                    ->label('Corporation Tax Amount Due')
                                                    ->prefix('£')
                                                    ->numeric()
                                                    ->maxValue(999999999.99),
                                                Forms\Components\TextInput::make('ct_payment_reference')
                                                    ->label('CT Payment Reference')
                                                    ->maxLength(255),
                                                Forms\Components\TextInput::make('tax_office')
                                                    ->label('Tax Office')
                                                    ->maxLength(255),
                                            ])->columns(2),

                                        Forms\Components\Section::make('Status & Progress')
                                            ->schema([
                                                Forms\Components\Toggle::make('companies_house_email_reminder')
                                                    ->label('Companies House Email Reminder')
                                                    ->default(false),
                                                Forms\Components\TextInput::make('accounts_latest_action')
                                                    ->label('Accounts Latest Action')
                                                    ->maxLength(255),
                                                Forms\Components\DatePicker::make('accounts_latest_action_date')
                                                    ->label('Accounts Latest Action Date'),
                                                Forms\Components\Textarea::make('accounts_progress_note')
                                                    ->label('Accounts Progress Notes')
                                                    ->rows(3),
                                            ])->columns(2),
                                    ]),
                            ]),

                        // Tab 7: Confirmation Statement
                        Forms\Components\Tabs\Tab::make('Confirmation Statement')
                            ->schema([
                                Forms\Components\Section::make('Confirmation Statement Details')
                                    ->relationship('confirmationStatementDetails')
                                    ->schema([


                                        Forms\Components\Section::make('Confirmation Statement Dates')
                                            ->schema([
                                                Forms\Components\DatePicker::make('confirmation_statement_date')
                                                    ->label('Confirmation Statement Date'),
                                                Forms\Components\DatePicker::make('confirmation_statement_due')
                                                    ->label('Confirmation Statement Due'),
                                                Forms\Components\DatePicker::make('records_received')
                                                    ->label('Records Received'),
                                            ])->columns(3),

                                        Forms\Components\Section::make('Status & Progress')
                                            ->schema([
                                                Forms\Components\TextInput::make('latest_action')
                                                    ->label('Latest Action')
                                                    ->maxLength(255),
                                                Forms\Components\DatePicker::make('latest_action_date')
                                                    ->label('Latest Action Date'),
                                                Forms\Components\Textarea::make('progress_note')
                                                    ->label('Progress Notes')
                                                    ->rows(3),
                                            ])->columns(2),

                                        Forms\Components\Section::make('Company Details')
                                            ->schema([
                                                Forms\Components\Textarea::make('officers')
                                                    ->label('Officers')
                                                    ->rows(3),
                                                Forms\Components\Textarea::make('share_capital')
                                                    ->label('Share Capital')
                                                    ->rows(3),
                                                Forms\Components\Textarea::make('shareholders')
                                                    ->label('Shareholders')
                                                    ->rows(3),
                                                Forms\Components\Textarea::make('people_with_significant_control')
                                                    ->label('People with Significant Control (PSC)')
                                                    ->rows(3),
                                            ])->columns(2),
                                    ]),
                            ]),

                        // Tab 8: VAT Details
                        Forms\Components\Tabs\Tab::make('VAT Details')
                            ->schema([
                                Forms\Components\Section::make('VAT Details')
                                    ->relationship('vatDetails')
                                    ->schema([


                                        Forms\Components\Section::make('VAT Registration Details')
                                            ->schema([
                                                Forms\Components\TextInput::make('vat_number')
                                                    ->label('VAT Number')
                                                    ->maxLength(255),
                                                Forms\Components\TextInput::make('vat_member_state')
                                                    ->label('VAT Member State')
                                                    ->maxLength(255),
                                                Forms\Components\Textarea::make('vat_address')
                                                    ->label('VAT Address')
                                                    ->rows(3),
                                                Forms\Components\DatePicker::make('date_of_registration')
                                                    ->label('Date of Registration'),
                                                Forms\Components\DatePicker::make('effective_date')
                                                    ->label('Effective Date'),
                                                Forms\Components\TextInput::make('estimated_turnover')
                                                    ->label('Estimated Turnover')
                                                    ->prefix('£')
                                                    ->numeric()
                                                    ->maxValue(999999999.99),
                                            ])->columns(2),

                                        Forms\Components\Section::make('VAT Return Information')
                                            ->schema([
                                                Forms\Components\Select::make('vat_frequency')
                                                    ->label('VAT Frequency')
                                                    ->options([
                                                        'Monthly' => 'Monthly',
                                                        'Quarterly' => 'Quarterly',
                                                        'Annual' => 'Annual',
                                                    ]),
                                                Forms\Components\DatePicker::make('vat_period_end')
                                                    ->label('VAT Period End'),
                                                Forms\Components\DatePicker::make('next_return_due')
                                                    ->label('Next Return Due'),
                                                Forms\Components\TextInput::make('vat_bill_amount')
                                                    ->label('VAT Bill Amount')
                                                    ->prefix('£')
                                                    ->numeric()
                                                    ->maxValue(999999999.99),
                                                Forms\Components\DatePicker::make('vat_bill_due')
                                                    ->label('VAT Bill Due'),
                                                Forms\Components\TextInput::make('month_of_last_quarter_submitted')
                                                    ->label('Month of Last Quarter Submitted')
                                                    ->maxLength(255),
                                                Forms\Components\TextInput::make('box_5_of_last_quarter_submitted')
                                                    ->label('Box 5 of Last Quarter Submitted')
                                                    ->maxLength(255),
                                            ])->columns(2),

                                        Forms\Components\Section::make('VAT Schemes')
                                            ->schema([
                                                Forms\Components\Toggle::make('standard_scheme')
                                                    ->label('Standard Scheme')
                                                    ->default(false),
                                                Forms\Components\Toggle::make('cash_accounting_scheme')
                                                    ->label('Cash Accounting Scheme')
                                                    ->default(false),
                                                Forms\Components\Toggle::make('retail_scheme')
                                                    ->label('Retail Scheme')
                                                    ->default(false),
                                                Forms\Components\Toggle::make('margin_scheme')
                                                    ->label('Margin Scheme')
                                                    ->default(false),
                                                Forms\Components\Toggle::make('flat_rate')
                                                    ->label('Flat Rate')
                                                    ->default(false),
                                                Forms\Components\TextInput::make('flat_rate_category')
                                                    ->label('Flat Rate Category')
                                                    ->maxLength(255),
                                            ])->columns(3),

                                        Forms\Components\Section::make('Making Tax Digital')
                                            ->schema([
                                                Forms\Components\DatePicker::make('applied_for_mtd')
                                                    ->label('Applied for MTD'),
                                                Forms\Components\Toggle::make('mtd_ready')
                                                    ->label('MTD Ready')
                                                    ->default(false),
                                            ])->columns(2),

                                        Forms\Components\Section::make('Additional Information')
                                            ->schema([
                                                Forms\Components\Toggle::make('transfer_of_going_concern')
                                                    ->label('Transfer of Going Concern')
                                                    ->default(false),
                                                Forms\Components\Toggle::make('involved_in_other_businesses')
                                                    ->label('Involved in Other Businesses')
                                                    ->default(false),
                                                Forms\Components\Toggle::make('direct_debit')
                                                    ->label('Direct Debit')
                                                    ->default(false),
                                            ])->columns(3),

                                        Forms\Components\Section::make('Progress Tracking')
                                            ->schema([
                                                Forms\Components\TextInput::make('latest_action')
                                                    ->label('Latest Action')
                                                    ->maxLength(255),
                                                Forms\Components\DatePicker::make('latest_action_date')
                                                    ->label('Latest Action Date'),
                                                Forms\Components\DatePicker::make('records_received')
                                                    ->label('Records Received'),
                                                Forms\Components\Textarea::make('progress_note')
                                                    ->label('Progress Notes')
                                                    ->rows(2),
                                                Forms\Components\Textarea::make('general_notes')
                                                    ->label('General Notes')
                                                    ->rows(3),
                                            ])->columns(2),
                                    ]),
                            ]),

                        // Tab 9: PAYE Details
                        Forms\Components\Tabs\Tab::make('PAYE Details')
                            ->schema([
                                Forms\Components\Section::make('PAYE Details')
                                    ->relationship('payeDetails')
                                    ->schema([


                                        Forms\Components\Section::make('PAYE References')
                                            ->schema([
                                                Forms\Components\TextInput::make('employers_reference')
                                                    ->label('Employer\'s Reference')
                                                    ->maxLength(255),
                                                Forms\Components\TextInput::make('accounts_office_reference')
                                                    ->label('Accounts Office Reference')
                                                    ->maxLength(255),
                                            ])->columns(2),

                                        Forms\Components\Section::make('Payroll Configuration')
                                            ->schema([
                                                Forms\Components\Select::make('paye_frequency')
                                                    ->label('PAYE Frequency')
                                                    ->options([
                                                        'Weekly' => 'Weekly',
                                                        'Fortnightly' => 'Fortnightly',
                                                        'Monthly' => 'Monthly',
                                                        'Quarterly' => 'Quarterly',
                                                        'Annual' => 'Annual',
                                                    ]),
                                                Forms\Components\TextInput::make('years_required')
                                                    ->label('Years Required')
                                                    ->maxLength(255),
                                                Forms\Components\Toggle::make('irregular_monthly_pay')
                                                    ->label('Irregular Monthly Pay')
                                                    ->default(false),
                                                Forms\Components\Toggle::make('nil_eps')
                                                    ->label('Nil EPS')
                                                    ->default(false),
                                                Forms\Components\TextInput::make('number_of_employees')
                                                    ->label('Number of Employees')
                                                    ->numeric()
                                                    ->integer(),
                                                Forms\Components\Textarea::make('salary_details')
                                                    ->label('Salary Details')
                                                    ->rows(3),
                                            ])->columns(2),

                                        Forms\Components\Section::make('Key Dates')
                                            ->schema([
                                                Forms\Components\DatePicker::make('first_pay_date')
                                                    ->label('First Pay Date'),
                                                Forms\Components\DatePicker::make('rti_deadline')
                                                    ->label('RTI Deadline'),
                                                Forms\Components\DatePicker::make('paye_scheme_ceased')
                                                    ->label('PAYE Scheme Ceased'),
                                            ])->columns(3),

                                        Forms\Components\Section::make('Progress Tracking')
                                            ->schema([
                                                Forms\Components\TextInput::make('paye_latest_action')
                                                    ->label('Latest Action')
                                                    ->maxLength(255),
                                                Forms\Components\DatePicker::make('paye_latest_action_date')
                                                    ->label('Latest Action Date'),
                                                Forms\Components\DatePicker::make('paye_records_received')
                                                    ->label('Records Received'),
                                                Forms\Components\Textarea::make('paye_progress_note')
                                                    ->label('Progress Notes')
                                                    ->rows(3),
                                                Forms\Components\Textarea::make('general_notes')
                                                    ->label('General Notes')
                                                    ->rows(3),
                                            ])->columns(2),
                                    ]),
                            ]),

                        // Tab 10: Auto-Enrolment Details
                        Forms\Components\Tabs\Tab::make('Auto-Enrolment')
                            ->schema([
                                Forms\Components\Section::make('Auto-Enrolment Details')
                                    ->relationship('autoEnrolmentDetails')
                                    ->schema([


                                        Forms\Components\Section::make('Pension Provider Information')
                                            ->schema([
                                                Forms\Components\TextInput::make('pension_provider')
                                                    ->label('Pension Provider')
                                                    ->maxLength(255),
                                                Forms\Components\TextInput::make('pension_id')
                                                    ->label('Pension ID')
                                                    ->maxLength(255),
                                            ])->columns(2),

                                        Forms\Components\Section::make('Key Dates')
                                            ->schema([
                                                Forms\Components\DatePicker::make('staging')
                                                    ->label('Staging Date'),
                                                Forms\Components\DatePicker::make('postponement_date')
                                                    ->label('Postponement Date'),
                                                Forms\Components\DatePicker::make('tpr_opt_out_date')
                                                    ->label('TPR Opt Out Date'),
                                                Forms\Components\DatePicker::make('re_enrolment_date')
                                                    ->label('Re-enrolment Date'),
                                                Forms\Components\DatePicker::make('declaration_of_compliance_due')
                                                    ->label('Declaration of Compliance Due'),
                                                Forms\Components\DatePicker::make('declaration_of_compliance_submission')
                                                    ->label('Declaration of Compliance Submission'),
                                            ])->columns(2),

                                        Forms\Components\Section::make('Progress Tracking')
                                            ->schema([
                                                Forms\Components\TextInput::make('latest_action')
                                                    ->label('Latest Action')
                                                    ->maxLength(255),
                                                Forms\Components\DatePicker::make('latest_action_date')
                                                    ->label('Latest Action Date'),
                                                Forms\Components\DatePicker::make('records_received')
                                                    ->label('Records Received'),
                                                Forms\Components\Textarea::make('progress_note')
                                                    ->label('Progress Notes')
                                                    ->rows(3),
                                            ])->columns(2),
                                    ]),
                            ]),

                        // Tab 11: P11D Details
                        Forms\Components\Tabs\Tab::make('P11D Details')
                            ->schema([
                                Forms\Components\Section::make('P11D Details')
                                    ->relationship('p11dDetails')
                                    ->schema([


                                        Forms\Components\Section::make('P11D Information')
                                            ->schema([
                                                Forms\Components\DatePicker::make('next_p11d_return_due')
                                                    ->label('Next P11D Return Due'),
                                                Forms\Components\DatePicker::make('latest_p11d_submitted')
                                                    ->label('Latest P11D Submitted'),
                                            ])->columns(2),

                                        Forms\Components\Section::make('Progress Tracking')
                                            ->schema([
                                                Forms\Components\TextInput::make('latest_action')
                                                    ->label('Latest Action')
                                                    ->maxLength(255),
                                                Forms\Components\DatePicker::make('latest_action_date')
                                                    ->label('Latest Action Date'),
                                                Forms\Components\DatePicker::make('records_received')
                                                    ->label('Records Received'),
                                                Forms\Components\Textarea::make('progress_note')
                                                    ->label('Progress Notes')
                                                    ->rows(3),
                                            ])->columns(2),
                                    ]),
                            ]),

                        // Tab 12: Registration Details
                        Forms\Components\Tabs\Tab::make('Registration')
                            ->schema([
                                Forms\Components\Section::make('Registration Details')
                                    ->relationship('registrationDetails')
                                    ->schema([


                                        Forms\Components\Section::make('Registration Status')
                                            ->schema([
                                                Forms\Components\Toggle::make('terms_signed_registration_fee_paid')
                                                    ->label('Terms Signed & Registration Fee Paid')
                                                    ->default(false),
                                                Forms\Components\TextInput::make('fee')
                                                    ->label('Registration Fee')
                                                    ->prefix('£')
                                                    ->numeric()
                                                    ->maxValue(999999999.99),
                                                Forms\Components\DatePicker::make('letter_of_engagement_signed')
                                                    ->label('Letter of Engagement Signed'),
                                                Forms\Components\Toggle::make('money_laundering_complete')
                                                    ->label('Money Laundering Complete')
                                                    ->default(false),
                                                Forms\Components\DatePicker::make('registration_64_8')
                                                    ->label('Registration 64-8 Date'),
                                            ])->columns(2),
                                    ]),
                            ]),

                        // Tab 13: Other Details
                        Forms\Components\Tabs\Tab::make('Other Details')
                            ->schema([
                                Forms\Components\Section::make('Other Details')
                                    ->relationship('otherDetails')
                                    ->schema([


                                        Forms\Components\Section::make('Referral & Onboarding')
                                            ->schema([
                                                Forms\Components\TextInput::make('referred_by')
                                                    ->label('Referred By')
                                                    ->maxLength(255),
                                                Forms\Components\DatePicker::make('initial_contact')
                                                    ->label('Initial Contact Date'),
                                                Forms\Components\DatePicker::make('proposal_email_sent')
                                                    ->label('Proposal Email Sent'),
                                                Forms\Components\DatePicker::make('welcome_email')
                                                    ->label('Welcome Email Sent'),
                                            ])->columns(2),

                                        Forms\Components\Section::make('Business Information')
                                            ->schema([
                                                Forms\Components\TextInput::make('accounting_system')
                                                    ->label('Accounting System')
                                                    ->maxLength(255),
                                                Forms\Components\TextInput::make('profession')
                                                    ->label('Profession')
                                                    ->maxLength(255),
                                                Forms\Components\TextInput::make('website')
                                                    ->label('Website')
                                                    ->url()
                                                    ->maxLength(255),
                                            ])->columns(3),

                                        Forms\Components\Section::make('Social Media')
                                            ->schema([
                                                Forms\Components\TextInput::make('twitter_handle')
                                                    ->label('Twitter Handle')
                                                    ->maxLength(255),
                                                Forms\Components\TextInput::make('facebook_url')
                                                    ->label('Facebook URL')
                                                    ->url()
                                                    ->maxLength(255),
                                                Forms\Components\TextInput::make('linkedin_url')
                                                    ->label('LinkedIn URL')
                                                    ->url()
                                                    ->maxLength(255),
                                                Forms\Components\TextInput::make('instagram_handle')
                                                    ->label('Instagram Handle')
                                                    ->maxLength(255),
                                            ])->columns(2),
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
