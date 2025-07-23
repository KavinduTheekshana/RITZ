<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Faq;

class FaqSeeder extends Seeder
{
    public function run(): void
    {
        $faqs = [
            [
                'question' => 'What documents do I need to register a company in Sri Lanka?',
                'answer' => 'To register a company in Sri Lanka, you typically need: proposed company name, details of directors and shareholders, registered office address, Articles of Association, and identification documents for all directors. We can guide you through the entire process and ensure all documentation is properly prepared.',
                'status' => 1,
            ],
            [
                'question' => 'When is the deadline for filing corporate tax returns?',
                'answer' => 'Corporate tax returns in Sri Lanka must be filed within 6 months after the end of the financial year. For companies with a March 31 year-end, the deadline is September 30. We help ensure timely filing to avoid penalties.',
                'status' => 1,
            ],
            [
                'question' => 'Do I need to register for VAT?',
                'answer' => 'VAT registration is mandatory if your taxable supplies exceed Rs. 3 million per quarter or Rs. 12 million per annum. Voluntary registration is also possible if you expect to exceed these thresholds. We can assess your situation and handle the registration process.',
                'status' => 1,
            ],
            [
                'question' => 'How often do I need to submit PAYE returns?',
                'answer' => 'PAYE returns must be submitted monthly by the 15th of the following month. We can manage your entire payroll process, ensuring accurate calculations and timely submissions to avoid penalties.',
                'status' => 1,
            ],
            [
                'question' => 'What accounting software do you work with?',
                'answer' => 'We work with various accounting platforms including QuickBooks, Sage, Xero, and can also manage manual bookkeeping systems. Our team is trained to work with your preferred system or recommend the best solution for your business needs.',
                'status' => 1,
            ],
            [
                'question' => 'How can I reduce my business tax liability legally?',
                'answer' => 'There are several legal ways to optimize your tax position, including claiming all allowable business expenses, utilizing available tax incentives, proper timing of income and expenses, and structured tax planning. We provide personalized tax planning strategies for each client.',
                'status' => 1,
            ],
            [
                'question' => 'What is the difference between a Private Limited Company and a Sole Proprietorship?',
                'answer' => 'A Private Limited Company is a separate legal entity with limited liability protection, while a Sole Proprietorship is owned and operated by one person with unlimited personal liability. Companies have more compliance requirements but offer better protection and credibility. We can help you choose the right structure.',
                'status' => 1,
            ],
            [
                'question' => 'How long does it take to register a new company?',
                'answer' => 'Company registration in Sri Lanka typically takes 3-5 business days once all documents are properly prepared and submitted. We handle the entire process efficiently and keep you updated at every stage.',
                'status' => 1,
            ],
            [
                'question' => 'What are the penalties for late tax filing?',
                'answer' => 'Penalties vary by tax type. For corporate tax, late filing can result in penalties starting from Rs. 50,000. VAT late filing penalties can be 5% of the tax due per month. We ensure timely filing to avoid these unnecessary costs.',
                'status' => 1,
            ],
            [
                'question' => 'Do you provide audit services?',
                'answer' => 'Yes, we provide comprehensive audit services including statutory audits, internal audits, and special purpose audits. Our qualified team ensures compliance with Sri Lankan Accounting Standards and provides valuable insights for business improvement.',
                'status' => 1,
            ],
        ];

        foreach ($faqs as $faq) {
            Faq::create($faq);
        }
    }
}