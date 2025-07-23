<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SelfAssessmentResource\Pages;
use App\Filament\Resources\SelfAssessmentResource\RelationManagers;
use App\Models\SelfAssessmentChatList;
use App\Models\SelfAssessment;
use App\Models\Client;
use App\Models\EngagementLetterSelfAssessment;
use App\Mail\SelfAssessmentEngagementLetter;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Actions\Action;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Actions;
use Filament\Forms\Components\Actions\Action as FormAction;
use Filament\Forms\Get;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Barryvdh\DomPDF\Facade\Pdf;

class SelfAssessmentResource extends Resource
{
    protected static ?string $model = SelfAssessment::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationGroup = 'Tax Management';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Tabs::make('Self Assessment Information')
                    ->tabs([
                        // Tab 1: Main Information
                        Forms\Components\Tabs\Tab::make('Main Information')
                            ->schema([
                                Forms\Components\Section::make('Self Assessment Details')
                                    ->schema([
                                        Forms\Components\Select::make('client_id')
    ->label('Client')
    ->options(Client::all()->pluck('full_name', 'id'))
    ->searchable()
    ->required()
    ->reactive()
    ->disabled(fn(?SelfAssessment $record) => $record !== null) // Disable on edit, allow on create
    ->afterStateUpdated(function ($state, callable $set) {
        if ($state) {
            $client = Client::find($state);
            if ($client) {
                // Pre-fill fields from client data
                $set('self_assessment_telephone', $client->mobile_number ?? $client->telephone_number);
                $set('self_assessment_email', $client->email);
                $set('assessment_name', $client->full_name . ' - Self Assessment');
            }
        }
    })
    ->helperText(fn(?SelfAssessment $record) => 
        $record !== null 
            ? 'Client cannot be changed after creation.' 
            : 'Each client can only have one self assessment.'
    ),

                                        Forms\Components\TextInput::make('assessment_name')
                                            ->label('Assessment Name')
                                            ->maxLength(255),
                                        Forms\Components\TextInput::make('self_assessment_telephone')
                                            ->label('Telephone')
                                            ->tel()
                                            ->maxLength(255),
                                        Forms\Components\TextInput::make('self_assessment_email')
                                            ->label('Email')
                                            ->email()
                                            ->maxLength(255),
                                    ])->columns(2),
                            ]),

                        // Tab 2: Internal Details
                        Forms\Components\Tabs\Tab::make('Internal Details')
                            ->schema([
                                Forms\Components\Section::make('Internal Information')
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
                                            ->label('Urgent')
                                            ->rows(3),
                                    ])->columns(2),
                            ]),

                        // Tab 3: Business Details
                        Forms\Components\Tabs\Tab::make('Business Details')
                            ->schema([
                                Forms\Components\Section::make('Business Information')
                                    ->relationship('businessDetails')
                                    ->schema([
                                        Forms\Components\TextInput::make('trading_as')
                                            ->label('Trading As')
                                            ->maxLength(255),
                                        Forms\Components\Textarea::make('trading_address')
                                            ->label('Trading Address')
                                            ->rows(3),
                                        Forms\Components\DatePicker::make('commenced_trading')
                                            ->label('Commenced Trading'),
                                        Forms\Components\DatePicker::make('cassed_trading')
                                            ->label('Ceased Trading'),
                                        Forms\Components\DatePicker::make('registerd_for_sa')
                                            ->label('Registered for Self Assessment'),
                                        Forms\Components\TextInput::make('turnover')
                                            ->label('Turnover')
                                            ->prefix('£')
                                            ->numeric()
                                            ->maxValue(999999999.99),
                                        Forms\Components\TextInput::make('nature_of_business')
                                            ->label('Nature of Business')
                                            ->maxLength(255),
                                    ])->columns(2),
                            ]),

                        // Tab 4: Income Details
                        Forms\Components\Tabs\Tab::make('Income Details')
                            ->schema([
                                Forms\Components\Section::make('Income Information')
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

                        // Tab 5: Previous Accountant
                        Forms\Components\Tabs\Tab::make('Previous Accountant')
                            ->schema([
                                Forms\Components\Section::make('Previous Accountant Details')
                                    ->relationship('previousAccountantDetails')
                                    ->schema([
                                        Forms\Components\Toggle::make('clearance_required')
                                            ->label('Clearance Required')
                                            ->default(false),
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
                                        Forms\Components\Toggle::make('information_received')
                                            ->label('Information Received')
                                            ->default(false),
                                    ])->columns(2),
                            ]),

                        // Tab 6: Services Required
                        Forms\Components\Tabs\Tab::make('Services Required')
                            ->schema([
                                Forms\Components\Section::make('Services Required')
                                    ->relationship('servicesRequired')
                                    ->schema([
                                        Forms\Components\Section::make('Services')
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

                        // Tab 7: Accounts and Returns
                        Forms\Components\Tabs\Tab::make('Accounts & Returns')
                            ->schema([
                                Forms\Components\Section::make('Accounts and Returns')
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

                        // Tab 10: Auto-Enrolment
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

                        // Tab 12: Registration
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
                Tables\Columns\TextColumn::make('client.full_name')
                    ->label('Client Name')
                    ->searchable(['first_name', 'middle_name', 'last_name'])
                    ->sortable()
                    ->formatStateUsing(function ($record) {
                        return $record->client ? $record->client->full_name : 'No Client';
                    }),

                Tables\Columns\TextColumn::make('assessment_name')
                    ->label('Assessment Name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('self_assessment_email')
                    ->label('Email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('self_assessment_telephone')
                    ->label('Telephone')
                    ->searchable(),
                Tables\Columns\TextColumn::make('internalDetails.internal_reference')
                    ->label('Internal Reference')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('internalDetails.client_grade')
                    ->label('Client Grade')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('internalDetails.client_risk_level')
                    ->label('Risk Level')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('businessDetails.turnover')
                    ->label('Turnover')
                    ->money('GBP')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
                //
            ])
            // Replace the actions() method in app/Filament/Resources/SelfAssessmentResource.php

->actions([
    // View Engagement Letter (when engagement is true)
    Action::make('viewEngagementLetters')
        ->label('View Engagement Letter')
        ->icon('heroicon-o-clipboard-document-list')
        ->color('info')
        ->modalHeading(fn(SelfAssessment $record) => "Engagement Letters for {$record->assessment_name}")
        ->modalContent(function (SelfAssessment $record) {
            $engagementLetters = $record->engagementLetters()
                ->orderBy('sent_at', 'desc')
                ->get();

            return view('filament.self-assessment.engagement-letters-list', [
                'selfAssessment' => $record,
                'engagementLetters' => $engagementLetters,
            ]);
        })
        ->modalWidth('7xl')
        ->slideOver()
        ->visible(fn(SelfAssessment $record) => $record->engagement), // Show only when engagement is true

    // Send Engagement Letter (when engagement is false)
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
                ->default(fn(SelfAssessment $record) => self::getDefaultEngagementLetter($record))
                ->required()
                ->columnSpanFull(),
            Actions::make([
                FormAction::make('download')
                    ->label('Download PDF')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->color('gray')
                    ->action(function (SelfAssessment $record, Get $get) {
                        $letterContent = $get('engagement_letter');
                        
                        $pdf = Pdf::loadView('pdfs.engagement-letter', [
                            'letterContent' => $letterContent
                        ]);
                        
                        return response()->streamDownload(
                            fn() => print($pdf->output()),
                            'engagement-letter-self-assessment-' . $record->id . '.pdf'
                        );
                    }),
            ])->alignEnd()->columnSpanFull(),
        ])
        ->action(function (SelfAssessment $record, array $data) {
            $letterContent = $data['engagement_letter'];
            
            // Generate PDF
            $pdf = Pdf::loadView('pdfs.engagement-letter', [
                'letterContent' => $letterContent
            ]);
            
            // Save PDF to storage
            $fileName = 'self-assessment-engagement-letter-' . $record->id . '-' . time() . '.pdf';
            $filePath = 'engagement-letters/self-assessments/' . $fileName;
            Storage::disk('public')->put($filePath, $pdf->output());
            
            // Save to database
            EngagementLetterSelfAssessment::create([
                'self_assessment_id' => $record->id,
                'content' => $letterContent,
                'file_path' => $filePath,
                'file_name' => $fileName,
                'sent_at' => now(),
                'sent_by' => Auth::id() ?? 'system',
            ]);
            
            // Update the record to mark engagement letter as sent
            $record->update(['engagement' => true]);
            
            // Send email if available
            if ($record->self_assessment_email) {
                Mail::to($record->self_assessment_email)->send(
                    new SelfAssessmentEngagementLetter(
                        $letterContent,
                        $record->assessment_name,
                        Storage::disk('public')->get($filePath)
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
        ->visible(fn(SelfAssessment $record) => !$record->engagement), // Show only when engagement is false

    // Chat/Messages
    Action::make('chat')
        ->label('Chat')
        ->icon('heroicon-o-chat-bubble-left-right')
        ->color('danger')
        ->url(fn(SelfAssessment $record) => route('filament.admin.resources.self-assessments.chat', $record))
        ->badge(fn(SelfAssessment $record) => 
            SelfAssessmentChatList::where('self_assessment_id', $record->id)
                ->where('sender_type', 'client')
                ->where('is_read', false)
                ->count()
        )
        ->badgeColor('danger'),

    // View
    Tables\Actions\ViewAction::make(),

    // Edit
    Tables\Actions\EditAction::make(),
])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    protected static function getDefaultEngagementLetter(SelfAssessment $selfAssessment): string
    {
        $today = now()->format('d F Y');
        $client = $selfAssessment->client;
        $clientName = $client ? $client->full_name : '[Client Name]';
        $yearEnd = '5 April ' . now()->year;

        $services = $selfAssessment->servicesRequired;
        $scopeOfServices = '';

        if ($services) {
            $serviceList = [];

            // Check each service and add to list if price is greater than 0
            if ($services->accounts > 0) $serviceList[] = 'Personal Tax Return Preparation';
            if ($services->bookkeeping > 0) $serviceList[] = 'Personal Bookkeeping';
            if ($services->vat_returns > 0) $serviceList[] = 'VAT Returns';
            if ($services->consultation_advice > 0) $serviceList[] = 'Tax Planning & Consultation';
            if ($services->fee_protection_service > 0) $serviceList[] = 'Fee Protection Service';

            // Convert to HTML unordered list
            if (!empty($serviceList)) {
                $scopeOfServices = '<ul>';
                foreach ($serviceList as $service) {
                    $scopeOfServices .= '<li>' . $service . '</li>';
                }
                $scopeOfServices .= '</ul>';
            } else {
                $scopeOfServices = '<p>Self Assessment Tax Return preparation and submission.</p>';
            }
        } else {
            $scopeOfServices = '<p>Self Assessment Tax Return preparation and submission.</p>';
        }

        return <<<HTML
<div class="company-header"><h2>NTE ACCOUNTING LTD</h2></div>

<p>{$clientName}<br>
{$client?->address1}<br>
{$client?->address2}<br>
{$client?->city} {$client?->state} {$client?->postal_code}<br>
{$today}</p>

<p>Dear {$clientName},</p>

<p class="section-title"><strong>Engagement Letter - Self Assessment Services</strong></p>

<p>Thank you for engaging us to act on your behalf for your personal tax affairs. I will be your main point of contact and will have primary responsibility for this assignment.</p>

<p>This letter, including the attached schedules of services, and our standard terms and conditions, sets out the basis on which we will act. These documents contain the terms on which we will deliver the work for you so <strong>please read these carefully</strong>.</p>

<p class="sub-section"><strong>Who we are acting for</strong></p>
<p>We are acting for you <strong>{$clientName}</strong> only in relation to your personal tax affairs. Where you would like us to act for anyone else such as your spouse/partner, we will issue a separate engagement letter to them.</p>

<p class="sub-section"><strong>Period of engagement</strong></p>
<p>This engagement will start on <strong>{$today}</strong> and will continue for subsequent tax years unless terminated by either party.</p>

<p class="sub-section"><strong>Scope of services</strong></p>
{$scopeOfServices}

<p>Full details of the work that you have instructed us to carry out are in the attached schedule. The schedule confirms the scope of the services to be provided and each party's responsibilities in relation to the work to be carried out.</p>

<p class="sub-section"><strong>Your responsibilities</strong></p>
<p>You are legally responsible for:</p>
<ul>
    <li>Ensuring that your tax returns are correct and complete</li>
    <li>Filing any returns by the due date</li>
    <li>Paying tax on time</li>
</ul>

<p>Failure to do any of the above may lead to penalties and/or interest. We will use our reasonable care and skill when preparing your self assessment tax return but the ultimate responsibility remains with you.</p>

<p>You agree to provide us with complete and accurate information necessary to enable us to prepare your tax return, including details of all sources of income, capital gains, claims for allowances and deductions.</p>

<p class="sub-section"><strong>Fees</strong></p>
<p>Our fees will be charged in accordance with our proposal and our standard terms and conditions. We will bill you after the completion of your tax return unless we have agreed a different arrangement.</p>

<p class="sub-section"><strong>Limitation of liability</strong></p>
<p>We specifically draw your attention to our standard terms and conditions which set out the basis on which we limit our liability to you and to others. <strong>These are important clauses, please read them and ensure you are happy with them.</strong></p>

<p class="sub-section">Requirements of the Data Protection Act (DPA) 2018 and the UK General Data Protection Regulation (UK GDPR)</p>
<p>The DPA 2018 and the UK GDPR set out a number of requirements in relation to the processing of personal data.</p>

<p>Here at <strong>NTE ACCOUNTING LTD</strong> we take your privacy and the privacy of the information we process seriously. We will only use your personal information and the personal information you give us access to under this contract to administer your account and to provide the services you have requested from us.</p>

<p>We attach our privacy notice setting out our approach to handling your information. In signing one copy of this letter you will be indicating that you have received and read our privacy notice.</p>

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

<h3>SELF ASSESSMENT TAX RETURN SERVICES</h3>

<p><strong>Responsibilities of the taxpayer</strong></p>
<p>As a taxpayer, you are responsible for:</p>
<ul>
    <li>Keeping records of all your income and capital gains</li>
    <li>Retaining receipts and evidence for expenses and tax deductions claimed</li>
    <li>Providing us with all information necessary to complete your return</li>
    <li>Reviewing and approving your tax return before submission</li>
    <li>Meeting the filing deadline of 31 January following the tax year end</li>
    <li>Paying any tax due by the payment deadline</li>
</ul>

<p><strong>Our responsibilities</strong></p>
<p>We will:</p>
<ul>
    <li>Prepare your self assessment tax return based on the information you provide</li>
    <li>Calculate your tax liability</li>
    <li>Advise you of key tax saving opportunities</li>
    <li>Submit your return to HMRC once approved by you</li>
    <li>Provide you with a copy of your tax return and tax calculation</li>
    <li>Deal with routine HMRC queries</li>
</ul>

<p><strong>Timescales</strong></p>
<p>To ensure we can submit your tax return by the deadline, we need to receive all your information by <strong>30 November</strong>. If information is received after this date, we cannot guarantee submission by the 31 January deadline and you may incur late filing penalties.</p>

<p><strong>Records</strong></p>
<p>You are required by law to keep records to support your tax return for at least 22 months after the end of the tax year (5 years and 10 months if you have business or rental income).</p>

<p><strong>PRIVACY NOTICE</strong></p>
<p>The full privacy notice is as detailed in the company engagement letter format and applies equally to our handling of your personal tax information.</p>

<div style="margin-top: 50px;">
    <p>_______________________________<br>
    Signature</p>

    <p>_______________________________<br>
    Name (Please Print)</p>

    <p>_______________________________<br>
    Date</p>
</div>
HTML;
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
            'index' => Pages\ListSelfAssessments::route('/'),
            'create' => Pages\CreateSelfAssessment::route('/create'),
            'edit' => Pages\EditSelfAssessment::route('/{record}/edit'),
            'chat' => Pages\SelfAssessmentChat::route('/{record}/chat'),
        ];
    }
}
