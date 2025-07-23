<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Company;
use Illuminate\Support\Facades\DB;

class CompanyDetailsSeeder extends Seeder
{
    public function run(): void
    {
        $companies = Company::all();

        foreach ($companies as $index => $company) {
            // Company Internal Details
            DB::table('company_internal_details')->insert([
                'company_id' => $company->id,
                'internal_reference' => 'CMP' . str_pad($company->id, 6, '0', STR_PAD_LEFT),
                'allocated_office' => ['Colombo', 'Negombo', 'Galle', 'Kandy'][$index % 4],
                'client_grade' => ['A', 'B', 'A', 'C', 'B', 'A', 'B'][$index % 7],
                'client_risk_level' => ['Low', 'Medium', 'Low', 'High', 'Medium', 'Low', 'Medium'][$index % 7],
                'notes' => 'Client has been with us since ' . $company->created_at->format('Y'),
                'urgent' => $index % 3 == 0 ? 'Pending VAT submission deadline' : null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Services Required
            DB::table('services_requireds')->insert([
                'company_id' => $company->id,
                'accounts' => rand(1, 3) * 1000,
                'bookkeeping' => rand(5, 15) * 100,
                'ct600_return' => rand(3, 8) * 100,
                'payroll' => rand(2, 5) * 100,
                'auto_enrolment' => rand(1, 3) * 100,
                'vat_returns' => rand(2, 6) * 100,
                'management_accounts' => rand(1, 4) * 100,
                'confirmation_statement' => 150,
                'cis' => rand(0, 5) * 100,
                'p11d' => rand(1, 3) * 100,
                'fee_protection_service' => 250,
                'registered_address' => 100,
                'bill_payment' => rand(0, 2) * 100,
                'consultation_advice' => rand(5, 20) * 100,
                'software' => rand(1, 5) * 100,
                'annual_charge' => rand(5000, 20000),
                'monthly_charge' => rand(500, 2000),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Registration Details
            DB::table('registration_details')->insert([
                'company_id' => $company->id,
                'terms_signed_registration_fee_paid' => 1,
                'fee' => rand(500, 2000),
                'letter_of_engagement_signed' => now()->subDays(rand(30, 365)),
                'money_laundering_complete' => 1,
                'registration_64_8' => now()->subDays(rand(30, 365)),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Accounts and Returns Details
            DB::table('accounts_and_returns_details')->insert([
                'company_id' => $company->id,
                'accounts_period_end' => now()->addMonths(rand(1, 12)),
                'ch_year_end' => now()->addMonths(rand(1, 12)),
                'hmrc_year_end' => now()->addMonths(rand(1, 12)),
                'ch_accounts_next_due' => now()->addMonths(rand(1, 9)),
                'ct600_due' => now()->addMonths(rand(1, 12)),
                'corporation_tax_amount_due' => rand(1000, 50000),
                'tax_due_hmrc_year_end' => now()->addMonths(rand(1, 12)),
                'ct_payment_reference' => 'CT' . str_pad($company->id, 8, '0', STR_PAD_LEFT),
                'tax_office' => $company->corporation_tax_office,
                'companies_house_email_reminder' => rand(0, 1),
                'accounts_latest_action' => 'Preparing accounts',
                'accounts_latest_action_date' => now()->subDays(rand(1, 30)),
                'accounts_records_received' => now()->subDays(rand(30, 60)),
                'accounts_progress_note' => 'Records received and processing in progress',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // VAT Details (for companies likely to be VAT registered)
            if ($company->turnover > 3000000) {
                DB::table('vat_details')->insert([
                    'company_id' => $company->id,
                    'vat_frequency' => ['Monthly', 'Quarterly'][$index % 2],
                    'vat_period_end' => now()->addMonths(rand(1, 3)),
                    'next_return_due' => now()->addDays(rand(30, 60)),
                    'vat_bill_amount' => rand(1000, 20000),
                    'vat_bill_due' => now()->addDays(rand(30, 45)),
                    'latest_action' => 'VAT return prepared',
                    'latest_action_date' => now()->subDays(rand(1, 15)),
                    'records_received' => now()->subDays(rand(15, 30)),
                    'progress_note' => 'VAT return ready for submission',
                    'vat_member_state' => 'Sri Lanka',
                    'vat_number' => 'VAT' . str_pad($company->id, 9, '0', STR_PAD_LEFT),
                    'vat_address' => $company->registered_address,
                    'date_of_registration' => $company->date_of_trading,
                    'effective_date' => $company->date_of_trading,
                    'estimated_turnover' => $company->turnover,
                    'applied_for_mtd' => now()->subMonths(rand(1, 6)),
                    'mtd_ready' => 1,
                    'transfer_of_going_concern' => 0,
                    'involved_in_other_businesses' => rand(0, 1),
                    'direct_debit' => rand(0, 1),
                    'standard_scheme' => 1,
                    'cash_accounting_scheme' => 0,
                    'retail_scheme' => 0,
                    'margin_scheme' => 0,
                    'flat_rate' => 0,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            // PAYE Details (for companies with employees)
            if ($company->company_type == 'Private Limited Company' || $company->company_type == 'LLP') {
                DB::table('paye_details')->insert([
                    'company_id' => $company->id,
                    'employers_reference' => 'PAYE' . str_pad($company->id, 8, '0', STR_PAD_LEFT),
                    'accounts_office_reference' => 'AOR' . str_pad($company->id, 8, '0', STR_PAD_LEFT),
                    'years_required' => '2024-2025',
                    'paye_frequency' => 'Monthly',
                    'irregular_monthly_pay' => 0,
                    'nil_eps' => 0,
                    'number_of_employees' => rand(2, 50),
                    'salary_details' => json_encode([
                        'directors' => rand(1, 3),
                        'employees' => rand(1, 47)
                    ]),
                    'first_pay_date' => $company->date_of_trading->addMonths(1),
                    'rti_deadline' => now()->endOfMonth(),
                    'paye_latest_action' => 'RTI submission completed',
                    'paye_latest_action_date' => now()->subDays(rand(1, 7)),
                    'paye_records_received' => now()->subDays(rand(7, 14)),
                    'paye_progress_note' => 'Monthly payroll processing completed',
                    'general_notes' => 'Regular monthly payroll',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            // Confirmation Statement Details (for limited companies)
            if (in_array($company->company_type, ['Private Limited Company', 'LLP'])) {
                DB::table('confirmation_statement_details')->insert([
                    'company_id' => $company->id,
                    'confirmation_statement_date' => $company->incorporation_date->addYear(),
                    'confirmation_statement_due' => $company->incorporation_date->addYear()->addDays(14),
                    'latest_action' => 'Confirmation statement filed',
                    'latest_action_date' => now()->subMonths(rand(1, 6)),
                    'records_received' => now()->subMonths(rand(1, 6)),
                    'progress_note' => 'Annual confirmation statement filed successfully',
                    'officers' => json_encode([
                        'directors' => ['Director 1', 'Director 2'],
                        'secretary' => 'Company Secretary Name'
                    ]),
                    'share_capital' => json_encode([
                        'total_shares' => 1000,
                        'share_value' => 1
                    ]),
                    'shareholders' => json_encode([
                        ['name' => 'Shareholder 1', 'shares' => 600],
                        ['name' => 'Shareholder 2', 'shares' => 400]
                    ]),
                    'people_with_significant_control' => json_encode([
                        ['name' => 'PSC 1', 'nature_of_control' => 'Ownership of shares – more than 25% but not more than 50%']
                    ]),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            // Other Details
            DB::table('other_details')->insert([
                'company_id' => $company->id,
                'referred_by' => ['Client Referral', 'Website', 'Social Media', 'Direct Contact'][$index % 4],
                'initial_contact' => $company->created_at->subDays(rand(7, 30)),
                'proposal_email_sent' => $company->created_at->subDays(rand(5, 25)),
                'welcome_email' => $company->created_at->subDays(rand(1, 5)),
                'accounting_system' => ['QuickBooks', 'Sage', 'Xero', 'Manual'][$index % 4],
                'profession' => $company->nature_of_business,
                'website' => 'https://www.' . strtolower(str_replace(' ', '', $company->company_trading_as)) . '.lk',
                'twitter_handle' => '@' . strtolower(str_replace(' ', '', $company->company_trading_as)),
                'facebook_url' => 'https://facebook.com/' . strtolower(str_replace(' ', '', $company->company_trading_as)),
                'linkedin_url' => 'https://linkedin.com/company/' . strtolower(str_replace(' ', '', $company->company_trading_as)),
                'instagram_handle' => '@' . strtolower(str_replace(' ', '', $company->company_trading_as)),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}