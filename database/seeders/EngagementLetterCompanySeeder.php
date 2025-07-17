<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Company;
use Illuminate\Support\Facades\DB;

class EngagementLetterCompanySeeder extends Seeder
{
    public function run(): void
    {
        $companies = Company::where('engagement', 1)->limit(5)->get();
        
        foreach ($companies as $company) {
            $engagementLetter = [
                'company_id' => $company->id,
                'content' => $this->generateEngagementLetterContent($company),
                'file_name' => 'Engagement_Letter_' . str_replace(' ', '_', $company->company_name) . '.pdf',
                'file_path' => 'engagement-letters/unsigned/engagement_letter_' . $company->id . '.pdf',
                'sent_at' => now()->subDays(rand(30, 60)),
                'sent_by' => 'admin@ritzaccounting.com',
                'signer_full_name' => null,
                'signer_print_name' => null,
                'signer_email' => null,
                'ip' => null,
                'browser_data' => null,
                'signed_date' => null,
                'signed_at' => null,
                'signed_file_path' => null,
                'is_signed' => 0,
                'created_at' => now()->subDays(rand(30, 60)),
                'updated_at' => now()->subDays(rand(30, 60)),
            ];

            // Make some letters signed
            if ($company->id % 2 == 0) {
                $client = $company->clients()->first();
                if ($client) {
                    $engagementLetter['signer_full_name'] = $client->first_name . ' ' . $client->last_name;
                    $engagementLetter['signer_print_name'] = strtoupper($client->first_name . ' ' . $client->last_name);
                    $engagementLetter['signer_email'] = $client->email;
                    $engagementLetter['ip'] = '192.168.1.' . rand(100, 200);
                    $engagementLetter['browser_data'] = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36';
                    $engagementLetter['signed_date'] = now()->subDays(rand(20, 29));
                    $engagementLetter['signed_at'] = now()->subDays(rand(20, 29));
                    $engagementLetter['signed_file_path'] = 'engagement-letters/signed/engagement_letter_signed_' . $company->id . '.pdf';
                    $engagementLetter['is_signed'] = 1;
                }
            }

            DB::table('engagement_letter_companies')->insert($engagementLetter);
        }
    }

    private function generateEngagementLetterContent($company)
    {
        return "
<h1>ENGAGEMENT LETTER</h1>

<p>Date: " . now()->format('d F Y') . "</p>

<p>To: The Directors<br>
{$company->company_name}<br>
{$company->registered_address}</p>

<p>Dear Sirs/Madams,</p>

<h2>ENGAGEMENT FOR PROFESSIONAL SERVICES</h2>

<p>We are pleased to confirm our acceptance and understanding of this engagement to provide professional accounting and tax services to {$company->company_name}.</p>

<h3>1. SCOPE OF SERVICES</h3>
<p>We will provide the following services:</p>
<ul>
<li>Preparation of annual financial statements</li>
<li>Corporate tax computation and filing</li>
<li>VAT return preparation and submission</li>
<li>Payroll processing and PAYE compliance</li>
<li>General accounting and bookkeeping support</li>
<li>Business advisory services as requested</li>
</ul>

<h3>2. RESPONSIBILITIES</h3>
<p>Our responsibilities include providing professional services with due care and diligence in accordance with applicable professional standards.</p>

<p>Your responsibilities include:</p>
<ul>
<li>Providing complete and accurate information</li>
<li>Maintaining proper books and records</li>
<li>Timely provision of documents requested</li>
<li>Review and approval of prepared documents before submission</li>
</ul>

<h3>3. FEES</h3>
<p>Our fees will be based on the time spent and complexity of work involved. We will provide detailed invoices for our services.</p>

<h3>4. CONFIDENTIALITY</h3>
<p>We confirm that all information provided will be treated with strict confidentiality.</p>

<h3>5. LIMITATION OF LIABILITY</h3>
<p>Our liability is limited to the fees paid for the specific service in question.</p>

<h3>6. TERM</h3>
<p>This engagement will continue until terminated by either party with 30 days written notice.</p>

<p>Please confirm your acceptance of these terms by signing and returning a copy of this letter.</p>

<p>Yours faithfully,</p>
<p>RITZ ACCOUNTING SERVICES</p>

<br><br>
<p>ACCEPTANCE OF TERMS</p>
<p>We acknowledge and accept the terms of engagement as set out above.</p>

<p>For and on behalf of {$company->company_name}</p>

<p>_______________________________<br>
Signature</p>

<p>_______________________________<br>
Name (Please Print)</p>

<p>_______________________________<br>
Position</p>

<p>_______________________________<br>
Date</p>
";
    }
}