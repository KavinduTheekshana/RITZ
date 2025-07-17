<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Company;
use App\Models\Client;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class CompanyChatListSeeder extends Seeder
{
    public function run(): void
    {
        $companies = Company::limit(5)->get();
        $users = User::where('is_admin', 1)->get();
        $clients = Client::limit(5)->get();

        foreach ($companies as $index => $company) {
            $messages = [
                [
                    'company_id' => $company->id,
                    'user_id' => $users->random()->id,
                    'client_id' => null,
                    'sender_type' => 'admin',
                    'sender_name' => $users->random()->name,
                    'sender_email' => $users->random()->email,
                    'message' => 'Welcome to Ritz Accounting! We have received your registration. Our team will review your documents and get back to you shortly.',
                    'is_read' => 1,
                    'sent_at' => now()->subDays(rand(20, 30)),
                    'created_at' => now()->subDays(rand(20, 30)),
                    'updated_at' => now()->subDays(rand(20, 30)),
                ],
                [
                    'company_id' => $company->id,
                    'user_id' => null,
                    'client_id' => $clients->get($index % $clients->count())->id,
                    'sender_type' => 'client',
                    'sender_name' => $clients->get($index % $clients->count())->first_name . ' ' . $clients->get($index % $clients->count())->last_name,
                    'sender_email' => $clients->get($index % $clients->count())->email,
                    'message' => 'Thank you for the welcome. I have a question about the documents required for company registration.',
                    'is_read' => 1,
                    'sent_at' => now()->subDays(rand(18, 19)),
                    'created_at' => now()->subDays(rand(18, 19)),
                    'updated_at' => now()->subDays(rand(18, 19)),
                ],
                [
                    'company_id' => $company->id,
                    'user_id' => $users->random()->id,
                    'client_id' => null,
                    'sender_type' => 'admin',
                    'sender_name' => $users->random()->name,
                    'sender_email' => $users->random()->email,
                    'message' => 'Of course! For company registration, you will need: 1) Proposed company name, 2) Director details with IDs, 3) Registered office address proof, 4) Share allocation details. Would you like me to send you a detailed checklist?',
                    'is_read' => 1,
                    'sent_at' => now()->subDays(rand(17, 18)),
                    'created_at' => now()->subDays(rand(17, 18)),
                    'updated_at' => now()->subDays(rand(17, 18)),
                ],
                [
                    'company_id' => $company->id,
                    'user_id' => null,
                    'client_id' => null,
                    'sender_type' => 'system',
                    'sender_name' => 'System',
                    'sender_email' => 'system@ritzaccounting.com',
                    'message' => 'Document uploaded: Company_Registration_Form_' . $company->company_name . '.pdf',
                    'file_path' => 'uploads/documents/registration_form_' . $company->id . '.pdf',
                    'file_name' => 'Company_Registration_Form.pdf',
                    'file_size' => rand(100000, 500000),
                    'file_type' => 'application/pdf',
                    'is_read' => 1,
                    'sent_at' => now()->subDays(rand(15, 16)),
                    'created_at' => now()->subDays(rand(15, 16)),
                    'updated_at' => now()->subDays(rand(15, 16)),
                ],
                [
                    'company_id' => $company->id,
                    'user_id' => null,
                    'client_id' => $clients->get($index % $clients->count())->id,
                    'sender_type' => 'client',
                    'sender_name' => $clients->get($index % $clients->count())->first_name . ' ' . $clients->get($index % $clients->count())->last_name,
                    'sender_email' => $clients->get($index % $clients->count())->email,
                    'message' => 'I need help with my VAT return submission. The deadline is approaching.',
                    'is_read' => $index % 2 == 0,
                    'sent_at' => now()->subDays(rand(1, 5)),
                    'created_at' => now()->subDays(rand(1, 5)),
                    'updated_at' => now()->subDays(rand(1, 5)),
                ],
                [
                    'company_id' => $company->id,
                    'user_id' => $users->random()->id,
                    'client_id' => null,
                    'sender_type' => 'admin',
                    'sender_name' => $users->random()->name,
                    'sender_email' => $users->random()->email,
                    'message' => 'I understand your concern about the VAT deadline. Please upload your sales and purchase records for this quarter, and we will prepare your VAT return immediately.',
                    'is_read' => $index % 2 == 0,
                    'sent_at' => now()->subDays(rand(1, 4)),
                    'created_at' => now()->subDays(rand(1, 4)),
                    'updated_at' => now()->subDays(rand(1, 4)),
                ],
                [
                    'company_id' => $company->id,
                    'user_id' => null,
                    'client_id' => $clients->get($index % $clients->count())->id,
                    'sender_type' => 'client',
                    'sender_name' => $clients->get($index % $clients->count())->first_name . ' ' . $clients->get($index % $clients->count())->last_name,
                    'sender_email' => $clients->get($index % $clients->count())->email,
                    'message' => 'Can we schedule a meeting to discuss tax planning for next year?',
                    'is_read' => 0,
                    'sent_at' => now()->subHours(rand(12, 48)),
                    'created_at' => now()->subHours(rand(12, 48)),
                    'updated_at' => now()->subHours(rand(12, 48)),
                ],
                [
                    'company_id' => $company->id,
                    'user_id' => null,
                    'client_id' => null,
                    'sender_type' => 'system',
                    'sender_name' => 'System',
                    'sender_email' => 'system@ritzaccounting.com',
                    'message' => 'Monthly invoice generated: Invoice #INV-' . str_pad($company->id, 5, '0', STR_PAD_LEFT) . '-' . now()->format('Y-m'),
                    'file_path' => 'uploads/invoices/invoice_' . $company->id . '_' . now()->format('Y-m') . '.pdf',
                    'file_name' => 'Invoice_' . now()->format('F_Y') . '.pdf',
                    'file_size' => rand(50000, 150000),
                    'file_type' => 'application/pdf',
                    'is_read' => 1,
                    'sent_at' => now()->startOfMonth(),
                    'created_at' => now()->startOfMonth(),
                    'updated_at' => now()->startOfMonth(),
                ],
            ];

            foreach ($messages as $message) {
                DB::table('company_chat_lists')->insert($message);
            }
        }
    }
}