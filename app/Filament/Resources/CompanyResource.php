<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CompanyResource\Pages;
use App\Filament\Resources\CompanyResource\RelationManagers;
use App\Mail\EngagementLetter;
use App\Models\Company;
use App\Models\EngagementLetterDetails;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Forms\Components\Textarea;
use Illuminate\Support\Facades\Mail;
use Filament\Forms\Components\Actions\Action as FormAction;
use Filament\Forms\Components\Actions;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Get;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;


class CompanyResource extends Resource
{
    protected static ?string $model = Company::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-office';
    protected static ?string $navigationGroup = 'Tax Management';

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
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('incorporation_date')
                    ->label('Incorporation Date')
                    ->date()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('company_email')
                    ->label('Email')
                    ->searchable(),

                Tables\Columns\TextColumn::make('company_telephone')
                    ->label('Telephone')
                    ->searchable(),

                Tables\Columns\IconColumn::make('engagement')
                    ->boolean()
                    ->label('Engagement'),

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

                Action::make('sendEngagementLetter')
                    ->label('Send Engagement Letter')
                    ->icon('heroicon-o-envelope')
                    ->color('primary')
                    ->form([
                        RichEditor::make('engagement_letter')
                            ->label('Engagement Letter')
                            ->toolbarButtons([
                                'bold',
                                'italic',
                                'underline',
                                'h2',
                                'h3',
                                'bulletList',
                                'orderedList',
                                'redo',
                                'undo',
                                'link',
                                'strike',
                            ])
                            ->default(fn(Company $record) => self::getDefaultEngagementLetter($record))
                            ->required()
                            ->columnSpanFull(),
                        Actions::make([
                            FormAction::make('download')
                                ->label('Download PDF')
                                ->icon('heroicon-o-arrow-down-tray')
                                ->color('gray')
                                ->action(function (Company $record, Get $get) {
                                    $letterContent = $get('engagement_letter');

                                    $pdf = Pdf::loadView('pdfs.engagement-letter', [
                                        'letterContent' => $letterContent
                                    ]);

                                    return response()->streamDownload(
                                        fn() => print($pdf->output()),
                                        'engagement-letter-' . str_replace(' ', '-', strtolower($record->company_name)) . '.pdf'
                                    );
                                }),
                        ]),
                    ])
                    // ->action(function (Company $record, array $data) {
                    //     // Send email with PDF attachment
                    //     if ($record->company_email) {
                    //         Mail::to($record->company_email)->send(
                    //             new EngagementLetter(
                    //                 $data['engagement_letter'],
                    //                 $record->company_name
                    //             )
                    //         );
                    //     }



                    //     Notification::make()
                    //         ->title('Engagement letter sent successfully')
                    //         ->success()
                    //         ->send();
                    // })
                    ->action(function (Company $record, array $data) {
                        // Generate PDF
                        $letterContent = $data['engagement_letter'];
                        $pdf = Pdf::loadView('pdfs.engagement-letter', [
                            'letterContent' => $letterContent
                        ]);

                        // Save PDF to storage
                        $fileName = 'engagement-letter-' . str_replace(' ', '-', strtolower($record->company_name)) . '-' . now()->format('Y-m-d') . '.pdf';
                        $filePath = 'engagement-letters/' . $fileName;
                        Storage::disk('public')->put($filePath, $pdf->output());

                        // Save to database
                        EngagementLetterDetails::create([
                            'company_id' => $record->id,
                            'content' => $letterContent,
                            'file_path' => $filePath,
                            'sent_at' => now(),
                            'sent_by' => Auth::id() ?? 'system',
                        ]);

                        // Update company to mark engagement letter as sent
                        $record->engagement = true;
                        $record->save();

                        // Send email with PDF attachment if email is available
                        if ($record->company_email) {
                            Mail::to($record->company_email)->send(
                                new EngagementLetter(
                                    $letterContent,
                                    $record->company_name
                                )
                            );
                        }

                        Notification::make()
                            ->title('Engagement letter sent successfully')
                            ->success()
                            ->send();
                    })
                    ->modalHeading('Send Engagement Letter')
                    ->modalButton('Send as PDF')
                    ->modalWidth('7xl')
                    ->hidden(fn(Company $record) => $record->engagement),

                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }


    protected static function getDefaultEngagementLetter(Company $company,): string
    {
        $today = now()->format('d F Y');
        // $directorName = $company->company_name;
        $yearEnd = '31/10/' . now()->year;

        $client = $company->clients()->first();
        $directorName = $client->first_name;


        $services = $company->servicesRequired;
        $scopeOfServices = '';

        if ($services) {
            $serviceList = [];

            // Check each service and add to list if price is greater than 0
            if ($services->accounts > 0) $serviceList[] = 'Accounts';
            if ($services->bookkeeping > 0) $serviceList[] = 'Bookkeeping';
            if ($services->ct600_return > 0) $serviceList[] = 'CT600 Return';
            if ($services->payroll > 0) $serviceList[] = 'Payroll';
            if ($services->auto_enrolment > 0) $serviceList[] = 'Auto Enrolment';
            if ($services->vat_returns > 0) $serviceList[] = 'VAT Returns';
            if ($services->management_accounts > 0) $serviceList[] = 'Management Accounts';
            if ($services->confirmation_statement > 0) $serviceList[] = 'Confirmation Statement';
            if ($services->cis > 0) $serviceList[] = 'CIS';
            if ($services->p11d > 0) $serviceList[] = 'P11D';
            if ($services->fee_protection_service > 0) $serviceList[] = 'Fee Protection Service';
            if ($services->registered_address > 0) $serviceList[] = 'Registered Address';
            if ($services->bill_payment > 0) $serviceList[] = 'Bill Payment';
            if ($services->consultation_advice > 0) $serviceList[] = 'Consultation & Advice';
            if ($services->software > 0) $serviceList[] = 'Software';

            // Convert to HTML unordered list
            if (!empty($serviceList)) {
                $scopeOfServices = '<ul>';
                foreach ($serviceList as $service) {
                    $scopeOfServices .= '<li>' . $service . '</li>';
                }
                $scopeOfServices .= '</ul>';
            } else {
                $scopeOfServices = '<p>No services configured for this company.</p>';
            }
        } else {
            $scopeOfServices = '<p>Please configure services for this company.</p>';
        }

        return <<<HTML
<div class="company-header"><h2>NTE ACCOUNTING LTD</h2></div>

<p>The director of <strong>{$company->company_name}</strong><br>
{$today}</p>

<p>Dear {$directorName},</p>

<p class="section-title"><strong>Engagement Letter</strong></p>

<p>Thank you for engaging us to act on your behalf. I will be your main point of contact and will have primary responsibility for this assignment; the manager responsible for the ongoing work will be <strong>NTE ACCOUNTING LTD</strong>.</p>

<p>This letter, including the attached schedules of services, and our standard terms and conditions, sets out the basis on which we will act. These documents contain the terms on which we will deliver the work for you so <strong>please read these carefully</strong>.</p>

<p class="sub-section"><strong>Who we are acting for</strong></p>
<p>We are acting for <strong>{$company->company_name}</strong> only. Where you would like us to act for anyone else such as your spouse/a partnership/a limited/group company we will issue a separate engagement letter to them.</p>

<p class="sub-section"><strong>Period of engagement</strong></p>
<p>This engagement will start on <strong>{$today}</strong>.</p>

<p class="sub-section"><strong>Scope of services</strong></p>
{$scopeOfServices}

<p>Full details of the work that you have instructed us to carry out are in the attached schedule and listed at the end of this paragraph. The schedule confirms the scope of the services to be provided and each party's responsibilities in relation to the work to be carried out. If we agree to carry out additional services for you, we will provide you with a new or amended engagement letter. Only the services that are listed in the attached schedule are included within the scope of our instructions. If there is additional work that you wish us to carry out which is not listed in the schedule, please let us know and we will discuss with you whether it can be included in the existing scope of work.</p>

<p class="sub-section"><strong>Fees</strong></p>
<p>Our fees will be charged in accordance with our proposal and our standard terms and conditions.</p>

<p class="sub-section"><strong>Limitation of liability</strong></p>
<p>We specifically draw your attention to our standard terms and conditions and the relevant clause in each schedule of service which set out the basis on which we limit our liability to you and to others. These should be read in conjunction with the relevant paragraph of our standard terms and conditions which excludes liability to third parties. <strong>These are important clauses, please read them and ensure you are happy with them.</strong></p>

<p class="sub-section">Requirements of the Data Protection Act (DPA) 2018 and the UK General Data Protection Regulation (UK GDPR)</p>
<p>The DPA 2018 and the UK GDPR set out a number of requirements in relation to the processing of personal data.</p>

<p>Here at <strong>NTE ACCOUNTING LTD</strong> we take your privacy and the privacy of the information we process seriously. We will only use your personal information and the personal information you give us access to under this contract to administer your account and to provide the services you have requested from us.</p>

<p>We attach our privacy notice setting out our approach to handling your information. In signing one copy of this letter you will be indicating that you have received and read our privacy notice. Please note the following:</p>

<p class="sub-section"><strong>(a) Continuity arrangements</strong></p>
<p class="indent">Please note that we have arrangements in place for an alternate to deal with matters in the event of permanent incapacity or illness. This provides protection to you in the event that I cannot act on your behalf. Where necessary, an alternate shall have access to all of the information I hold in order to make initial contact with you and agree the work to be undertaken during my incapacity. You can choose to appoint an alternative individual at that stage if you wish.</p>

<p class="sub-section"><strong>(b) Secure communications and transfer of data</strong></p>
<p class="indent">We will communicate or transfer data using the following:</p>
<ul>
    <li>Post/hard-copy documents</li>
    <li>Encrypted emails</li>
    <li>Portals [BrightManager]</li>
    <li>Cloud-based software [BrightManager]</li>
    <li>Emails *</li>
</ul>

<p class="italic">*if you require us to correspond with you by email that is not encrypted or password protected, you also accept the risks associated with this form of communication. We shall accept no liability for any loss or damage to any data resulting from the transfer of information via email or other communication network.</p>

<p class="sub-section extra-space"><strong>Your agreement</strong></p>
<p>Please confirm your acceptance of:</p>
<ul>
    <li>the terms of this letter</li>
    <li>the attached schedule of services</li>
    <li>the privacy notice</li>
    <li>the standard terms and conditions</li>
</ul>
<p>by signing and returning one copy of this letter.</p>

 <p><strong>Acceptance</strong></p>
    <p>I acknowledge receipt of and accept the terms of your letter dated {$today}, the attached schedule of services, the privacy notice and standard terms and conditions, which fully record the agreement between us concerning your appointment to carry out the work described in the schedule of services.</p>

   <p> I also confirm the following in relation to data protection:</p>
   <p> I have received and read the privacy notice which sets out how my information will be processed.</p>
   <p> I agree to your appointed alternate having access to my records in the event of your illness or permanent incapacity.</p>
   <p> I understand that you will communicate with me or transfer data to me using the following methods:</p>
   <ul>
    <li>Post/hard-copy documents</li>
    <li>Encrypted emails</li>
    <li>Portals [BrightManager]</li>
    <li>Cloud-based software [BrightManager]</li>
    <li>Emails *</li>
   </ul>
  

   <p>* I accept the risks of you corresponding with me by email that is not encrypted or password protected.</p>

    <h3>ANNUAL ACCOUNTS – LIMITED COMPANIES</h3>

        <p><strong>Responsibilities of Directors</strong></p>
        <p>As director of the company, under the Companies Acts you are responsible for ensuring that the company maintains proper accounting records and you are ultimately responsible for preparing accounts.</p>

        <p>You undertake to keep records of sales invoices, purchase invoices, receipts and payments, together with any other documents relating to the company's transactions and activities. It will also be necessary for you to provide a record of stock at the company's year end.</p>

        <p>A private company is usually required to file its accounts at Companies House within 9 months of the year end. The company will be liable to fines if it fails to do so. In order to avoid this we will produce statutory accounts, suitable for filing, within the required period, provided all your records are complete and presented to us within five months of the year end, and all our queries are promptly and satisfactorily answered.</p>

        <p>You will complete all other returns required by law, for example, confirmation statements and notifications of changes in directors and Persons of Significant Control (PSC's), unless you have asked us specifically to deal with these for you. We shall, of course, be pleased to advise you on these and any other company matters if requested.</p>

        <p><strong>Responsibility of the accountants</strong></p>
        <p>We will prepare the company's accounts on the basis of the information that is provided to us. We will also draft the accounts in accordance with the provisions of the Companies Act, and related Accounting Standards for approval by the Board.</p>

        <p>Should our work lead us to conclude that the company is not entitled to exemption from an audit of the accounts, or should we be unable to reach a conclusion on this matter, then we will advise you of this.</p>

        <p>You have instructed us to prepare your financial statements for the year(s) ended {$yearEnd} and subsequent years. It was agreed that we should carry out the following accounting and other services:</p>
        <ol>
            <li>write up the accounting records of the company insofar as they are incomplete when presented to us;</li>
            <li>complete the postings to the nominal ledger; and</li>
            <li>prepare the accounts for approval by yourselves.</li>
        </ol>
     

        <p>You agree that you will arrange to:</p>
        <ol>
            <li>keep the records of receipts and balances;</li>
            <li>reconcile the balances monthly with the bank statements;</li>
            <li>post and balance the purchase and sales ledgers;</li>
            <li>extract a detailed list of ledger balances; and</li>
            <li>prepare details of the annual stocktaking, including prices and in a form which will enable us to verify the prices readily by reference to suppliers' invoices.</li>
            <li>prepare details of work-in-progress at the accounting date and make available to us the documents and other information from which the statement is compiled.</li>
        </ol>
  

        <p>You are responsible for the detection of irregularities and fraud. We do not undertake to discover any shortcomings in your systems or any irregularities on the part of your employees or others, although we will advise you of any that we encounter in preparing your accounts, unless prohibited from doing so by the Anti Money Laundering Legislation.</p>

        <p>We will report that in accordance with your instructions and in order to assist you to fulfil your responsibilities, we have compiled, without carrying out an audit, the accounts from your accounting records and from the information and explanations supplied to us.</p>

        <p>We have a professional duty to compile accounts which conform with generally accepted accounting principles and which comply with the Companies Acts and applicable accounting standards.</p>

        <h3>BOOKKEEPING</h3>
        <p>It is agreed that we should carry out the following accounting and other services:</p>
        <ol>
            <li>keep the records of receipts, payments and balances;</li>
            <li>reconcile the balances monthly with the bank statements;</li>
            <li>post and balance the purchase and sales ledgers;</li>
            <li>extract a detailed list of ledger balances;</li>
            <li>prepare details of the annual stocktaking and work in progress, suitably priced and extended in a form which will enable us to verify the prices readily by reference to suppliers' invoices;</li>
            <li>complete the postings to the nominal ledger;</li>
        </ol>
        

       <p>You are responsible for the detection of irregularities and fraud. We would emphasise that we cannot undertake to discover any shortcomings in your systems or any irregularities on the part of your employees or others, although we will advise you of any that we encounter.</p>

        <h3>CORPORATION TAX</h3>
        <p>We will prepare a computation for corporation tax purposes adjusted in accordance with the provisions of the Taxes Acts for {$yearEnd} and all subsequent years. We will also prepare and file the corporation tax return (form CT600) required under the Corporation Tax Self Assessment regulations within 12 months of the year end. The corporation tax return, together with the supporting corporation tax computations, will be sent to you for approval and signature prior to submission to the Inspector of Taxes.</p>

        <p>You accept that in law a taxpayer cannot contract out of his fiscal responsibilities and that computations and return forms are prepared by us as agent for the company. You also accept that you are legally responsible for making correct returns and for payment of tax on time. If we ask you for information to complete the tax return and it is not provided within the time-scale requested, so that the preparation and submission of the return are delayed, we accept no responsibility for any penalty or interest that may arise.</p>

        <p>We will advise you of the corporation tax payments to which the company will be liable, together with the due date of payment. You must inform us immediately if the company pays or receives any interest, or transfers any asset to any shareholder.</p>

        <p>Where necessary we will deal with any queries raised by the HM Revenue & Customs and negotiate with HM Revenue & Customs on any question of taxation interest or penalties which may arise.</p>

        <p>Any time we need to spend over and above answering straightforward queries raised by the HM Revenue & Customs is additional work for which we will need to charge separately. We will inform you before undertaking any extra work in respect of HM Revenue & Customs enquiries.</p>

        <p>To enable us to carry out our work you agree:</p>
        <ol>
            <li> to make a full disclosure to us of all sources of income, charges, allowances and capital transactions and to provide full information necessary for dealing with the company's affairs. We will rely on the information and documents being true, correct and complete;</li>
            <li>to respond quickly and fully to our requests for information and to other communications from us;</li>
            <li>to provide us with information in sufficient time for the company's self-assessment tax return to be completed and submitted by the due date. In order to do this, we need to receive all relevant information within 5 months of the year end; and</li>
            <li>to forward to us on receipt copies of all statements of account, letters and other communications received from HM Revenue & Customs and Companies House to enable us to deal with them as may be necessary within the statutory time limits.</li>
            <li>we can approach such third parties as may be appropriate for information that we consider necessary to deal with your affairs and undertake to authorise such third parties to communicate directly with us.</li>
        </ol>
   

        <p><strong>PRIVACY NOTICE issued by</strong> NTE ACCOUNTING LTD</p>

         <p><strong>Introduction</strong></p>
        <p>The Data Protection Act 2018 ("DPA 2018") and the UK General Data Protection Regulation ("UK GDPR") impose certain legal obligations in connection with the processing of personal data.</p>

        <p>NTE ACCOUNTING LTD is a controller within the meaning of the UK GDPR. The firm's contact details are as follows:</p>

      <p class="indent">
<strong>NTE ACCOUNTING LTD</strong><br>
77 Aragon Drive<br>
Ilford<br>
IG6 2TJ
</p>

        <p>We may amend this privacy notice from time to time. If we do so, we will supply you with and/or otherwise make available to you a copy of the amended privacy notice.</p>

        <p>Where we act as a processor on behalf of a controller (for example, when processing payroll), we provide an additional schedule setting out required information. That additional schedule should be read in conjunction with this privacy notice.</p>

        <p><strong>The purposes for which we process personal data</strong></p>
        <p>We process personal data for the following purposes:</p>
        <ul>
            <li>to enable us to supply professional services to you as our client</li>
            <li>to fulfil our obligations under relevant laws in force from time to time (e.g. the Money Laundering and Terrorist Financing (Amendment) Regulations 2019 (MLR 2019))</li>
            <li>to comply with professional obligations to which we are subject as a member of the Association of Chartered Certified Accountants</li>
            <li>to use in the investigation and/or defence of potential complaints, disciplinary proceedings and legal proceedings</li>
            <li>to enable us to invoice you for our services and investigate/address any attendant fee disputes that may have arisen</li>
            <li>to contact you about other services we provide which may be of interest to you if you have consented to us doing so</li>
        </ul>


                <p><strong>The legal bases for our intended processing of personal data</strong></p>
        <p>We rely on the following legal bases in order to process your personal data:</p>
        <ul>
            <li>occasionally we will rely on your consent to process your personal data but only if we have contacted you beforehand and asked you to agree;</li>
            <li>the processing is necessary for the performance of our contract with you so that we can deliver our services to you;</li>
            <li>the processing is necessary for compliance with legal obligations to which we are subject (e.g. MLR 2019);</li>
            <li>the processing is necessary for our legitimate interests, such as: investigating/defending legal claims, recovering debts owed to us, keeping our client records up to date and to develop our services and grow our business.</li>
        </ul>


       <p>If you do not provide the information that we request, we may not be able to provide professional services to you. If this is the case, we will not be able to commence acting or will need to cease to act.</p>

           <p><strong>Persons/organisations to whom we may give personal data</strong></p>
        <p>We may share your personal data with:</p>
        <ul>
            <li>HMRC</li>
            <li>any third parties with whom you require or permit us to correspond subcontractors</li>
            <li>an alternate appointed by us in the event of incapacity or death tax insurance providers</li>
            <li>professional indemnity insurers</li>
            <li>our professional body (the Association of Chartered Certified Accountants) and/or the Office of Professional Body Anti-Money Laundering Supervisors (OPBAS) in relation to practice assurance and/or the requirements of MLR 2019 (or any similar legislation)</li>
            <li>other professional consultants and service providers</li>
        </ul>


      <p>If the law allows or requires us to do so, we may share your personal data with:</p>
      <ul>
        <li>the police and law enforcement agencies</li>
        <li>courts and tribunals</li>
        <li>the Information Commissioner's Office ("ICO").</li>
      </ul>

       <p>We may need to share your personal data with the third parties identified above in order to comply with our legal obligations, including our legal obligations to you. If you ask us not to share your personal data with such third parties we may need to cease to act.</p>

         <p><strong>Transfers of personal data outside the UK</strong></p>
        <p>Your personal data will be processed in the UK only.</p>

              <p><strong>Retention of personal data</strong></p>

        <p>When acting as a data controller and in accordance with recognised good practice within the tax and accountancy sector we will retain all of our records relating to you as follows:</p>
        <ul>
            <li>where tax returns have been prepared it is our policy to retain information for six years from the end of the tax year to which the information relates</li>
            <li>where ad hoc advisory work has been undertaken it is our policy to retain information for six years from the date the business relationship ceased</li>
            <li>where we have an ongoing client relationship, data which is needed for more than one year's tax compliance (e.g. capital gains base costs and claims and elections submitted to HMRC) is retained throughout the period of the relationship, but will be deleted four years after the end of the business relationship unless you as our client ask us to retain it for a longer period.</li>
        </ul>


       <p>Our contractual terms provide for the destruction of documents after four years and therefore agreement to the contractual terms is taken as agreement to the retention of records for this period, and to their destruction thereafter.</p>

       <p>You are responsible for retaining information that we send to you (including details of capital gains base costs and claims and elections submitted) and this will be supplied in the form agreed between us. Documents and records relevant to your tax affairs are required by law to be retained by you as follows:</p>

         <p><strong>Individuals, trustees and partnerships</strong></p>
         <ul>
            <li>with trading or rental income: five years and 10 months after the end of the tax year</li>
            <li>otherwise: 22 months after the end of the tax year.</li>
         </ul>

         <p><strong>Companies, LLPs and other corporate entities</strong></p>
         <ul>
            <li>six years from the end of the accounting period.</li>
         </ul>

       <p>Where we act as a processor as defined in DPA 2018, we will delete or return all personal data to the controller as agreed with the controller at the termination of the contract.</p>

          <p><strong>Requesting personal data we hold about you (subject access requests)</strong></p>
        <p>You have a right to request access to your personal data that we hold. Such requests are known as 'subject access requests' ("SARs").</p>

       <p>Please provide all SARs in writing.</p>

        <p>To help us provide the information you want and deal with your request quickly, you should include enough details to enable us to verify your identity and locate the relevant information. For example, you should tell us:</p>
        <ul>
            <li>your date of birth</li>
            <li>previous or other name(s) you have used your previous addresses in the past five years</li>
            <li>personal reference number(s) that we may have given you, for example your national insurance number, your tax reference number or your VAT registration number</li>
            <li>what type of information you want to know</li>
        </ul>

        <p>If you do not have a national insurance number, you must send a copy of:</p>
        <ul>
            <li>the back page of your passport or a copy of your driving licence</li>
            <li>a recent utility bill.</li>
        </ul>

        <p>DPA 2018 requires that we comply with a SAR promptly and in any event within one month of receipt. There are, however, some circumstances in which the law allows us to refuse to provide access to personal data in response to a SAR (e.g. if you have previously made a similar request and there has been little or no change to the data since we complied with the original request).</p>

        <p>You can ask someone else to request information on your behalf – for example, a friend, relative or solicitor. We must have your authority to respond to a SAR made on your behalf. You can provide such authority by signing a letter which states that you authorise the person concerned to write to us for information about you, and/or receive our reply.</p>

        <p>Where you are a controller and we act for you as a processor (e.g. by processing payroll), we will assist you with SARs on the same basis as is set out above.</p>

               <p><strong>Putting things right (the right to rectification)</strong></p>
        <p>You have a right to obtain the rectification of any inaccurate personal data concerning you that we hold. You also have a right to have any incomplete personal data that we hold about you completed. Should you become aware that any personal data that we hold about you is inaccurate and/or incomplete, please inform us immediately so we can correct and/or complete it.</p>

          <p><strong>Deleting your records (the right to erasure)</strong></p>
        <p>In certain circumstances you have a right to have the personal data that we hold about you erased. Further information is available on the ICO website (www.ico.org.uk). If you would like your personal data to be erased, please inform us immediately and we will consider your request. In certain circumstances we have the right to refuse to comply with a request for erasure. If applicable, we will supply you with the reasons for refusing your request.</p>

        <p><strong>The right to restrict processing and the right to object</strong></p>
        In certain circumstances you have the right to 'block' or suppress the processing of personal data or to object to the processing of that information. Further information is available on the ICO website (www.ico.org.uk). Please inform us immediately if you want us to cease to process your information or you object to processing so that we can consider what action, if any, is appropriate.

        <p><strong>Obtaining and reusing personal data (the right to data portability)</strong></p>
        <p>In certain circumstances you have the right to be provided with the personal data that we hold about you in a machine-readable format, e.g. so that the data can easily be provided to a new professional adviser. Further information is available on the ICO website (www.ico.org.uk).</p>

         <p>The right to data portability only applies:</p>
        - to personal data an individual has provided to a controller
        - where the processing is based on the individual's consent or for the performance of a contract
        - when processing is carried out by automated means

        <p> We will respond to any data portability requests made to us without undue delay and within one month. We may extend the period by a further two months where the request is complex or a number of requests are received but we will inform you within one month of the receipt of the request and explain why the extension is necessary.</p>

        <p><strong>Withdrawal of consent</strong></p>
         <p>Where you have consented to our processing of your personal data, you have the right to withdraw that consent at any time. Please inform us immediately if you wish to withdraw your consent.</p>

         <p>Please note:</p>
         <ul>
            <li>the withdrawal of consent does not affect the lawfulness of earlier processing</li>
            <li>if you withdraw your consent, we may not be able to continue to provide services to you where we have previously relied on your consent to do so</li>
            <li>even if you withdraw your consent, it may remain lawful for us to process your data on another legal basis (e.g. because we have a legal obligation to continue to process your data).</li>
         </ul>

        <p><strong>Automated decision-making and profiling</strong></p>
         <p>We do not use automated decision-making and profiling in relation to your personal data.</p>

        <p><strong>Complaints</strong></p>
         <p>If you have requested details of the information we hold about you and you are not happy with our response, or you think we have not complied with the GDPR or DPA 2018 in some other way, you can complain to us using the contact details provided at the start of this notice.</p>

         <p>If you are not happy with our response, you have a right to lodge a complaint with the ICO (www.ico.org.uk).</p>

    
    <p>Signed By: _______________________</p>
    <p>Name: ___________________________</p>
    <p>Date: ___________________________</p>
    <p>Printed Name: ____________________</p>
</div>
HTML;
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
