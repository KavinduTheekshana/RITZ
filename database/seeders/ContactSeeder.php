<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Contact;
use App\Models\Company;
use App\Models\Client;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class ContactSeeder extends Seeder
{
    public function run(): void
    {
        $contacts = [
            [
                'name' => 'Potential Client One',
                'email' => 'potential1@example.com',
                'message' => 'I am interested in your accounting services for my new startup. Can you please provide information about your packages and pricing?',
                'is_read' => 1,
                'created_at' => now()->subDays(5),
                'updated_at' => now()->subDays(5),
            ],
            [
                'name' => 'Business Owner Two',
                'email' => 'businessowner2@example.com',
                'message' => 'We need help with VAT registration and monthly returns. Our business has grown and we now exceed the threshold. Please contact us urgently.',
                'is_read' => 0,
                'created_at' => now()->subDays(1),
                'updated_at' => now()->subDays(1),
            ],
            [
                'name' => 'Inquiry Three',
                'email' => 'inquiry3@example.com',
                'message' => 'Looking for payroll services for our team of 25 employees. Do you provide end-to-end payroll management including PAYE and EPF/ETF?',
                'is_read' => 1,
                'created_at' => now()->subDays(10),
                'updated_at' => now()->subDays(9),
            ],
            [
                'name' => 'Tax Question',
                'email' => 'taxquestion@example.com',
                'message' => 'I received a notice from the tax department. Can you help me understand and respond to it? I need professional assistance.',
                'is_read' => 0,
                'created_at' => now()->subHours(6),
                'updated_at' => now()->subHours(6),
            ],
            [
                'name' => 'Partnership Inquiry',
                'email' => 'partnership@example.com',
                'message' => 'We are a software company looking to partner with accounting firms. Would you be interested in discussing integration opportunities?',
                'is_read' => 1,
                'created_at' => now()->subDays(15),
                'updated_at' => now()->subDays(14),
            ],
        ];

        foreach ($contacts as $contact) {
            Contact::create($contact);
        }
    }
}