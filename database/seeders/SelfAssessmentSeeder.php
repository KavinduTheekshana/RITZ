<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Client;
use Illuminate\Support\Facades\DB;

class SelfAssessmentSeeder extends Seeder
{
    public function run(): void
    {
        // Create self assessments for clients who have create_self_assessment_client = 1
        $clients = Client::where('create_self_assessment_client', 1)->get();

        foreach ($clients as $index => $client) {
            $selfAssessment = DB::table('self_assessments')->insertGetId([
                'client_id' => $client->id,
                'assessment_name' => $client->first_name . ' ' . $client->last_name . ' Self Assessment',
                'self_assessment_telephone' => $client->mobile_number,
                'self_assessment_email' => $client->email,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Self Business Details
            DB::table('self_business_details')->insert([
                'self_assessment_id' => $selfAssessment,
                'trading_as' => $client->first_name . ' ' . $client->last_name . ' Consultancy',
                'trading_address' => $client->postal_address,
                'commenced_trading' => now()->subYears(rand(1, 5)),
                'registerd_for_sa' => now()->subYears(rand(1, 5)),
                'turnover' => rand(50000, 500000),
                'nature_of_business' => ['IT Consulting', 'Business Advisory', 'Professional Services', 'Freelance Writer', 'Photography'][$index % 5],
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Self Internal Details
            DB::table('self_internal_details')->insert([
                'self_assessment_id' => $selfAssessment,
                'internal_reference' => 'SA' . str_pad($selfAssessment, 6, '0', STR_PAD_LEFT),
                'allocated_office' => ['Colombo', 'Negombo', 'Galle', 'Kandy'][$index % 4],
                'client_grade' => ['A', 'B', 'C'][$index % 3],
                'client_risk_level' => ['Low', 'Medium', 'High'][$index % 3],
                'notes' => 'Self assessment client',
                'urgent' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Self Services Required
            DB::table('self_services_requireds')->insert([
                'self_assessment_id' => $selfAssessment,
                'accounts' => rand(500, 2000),
                'bookkeeping' => rand(100, 500),
                'annual_charge' => rand(1000, 5000),
                'monthly_charge' => rand(100, 500),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Self Registration Details
            DB::table('self_registration_details')->insert([
                'self_assessment_id' => $selfAssessment,
                'terms_signed_registration_fee_paid' => 1,
                'fee' => rand(200, 500),
                'letter_of_engagement_signed' => now()->subDays(rand(30, 180)),
                'money_laundering_complete' => 1,
                'registration_64_8' => now()->subDays(rand(30, 180)),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Self Accounts and Returns Details
            DB::table('self_accounts_and_returns_details')->insert([
                'self_assessment_id' => $selfAssessment,
                'accounts_period_end' => now()->addMonths(rand(1, 12)),
                'hmrc_year_end' => now()->addMonths(rand(1, 12)),
                'accounts_latest_action' => 'Preparing self assessment',
                'accounts_latest_action_date' => now()->subDays(rand(1, 30)),
                'accounts_records_received' => now()->subDays(rand(30, 60)),
                'accounts_progress_note' => 'Self assessment in progress',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Self Income Details
            DB::table('self_income_details')->insert([
                'self_assessment_id' => $selfAssessment,
                'previous' => json_encode(['employment' => rand(20000, 80000), 'self_employment' => rand(10000, 50000)]),
                'current' => json_encode(['employment' => rand(25000, 90000), 'self_employment' => rand(15000, 60000)]),
                'ir_35_notes' => 'Not applicable',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Self Other Details
            DB::table('self_other_details')->insert([
                'self_assessment_id' => $selfAssessment,
                'referred_by' => ['Client Referral', 'Website', 'Social Media'][$index % 3],
                'initial_contact' => now()->subDays(rand(30, 180)),
                'proposal_email_sent' => now()->subDays(rand(25, 175)),
                'welcome_email' => now()->subDays(rand(20, 170)),
                'accounting_system' => ['Excel', 'QuickBooks', 'Manual'][$index % 3],
                'profession' => 'Self Employed',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}