<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Service;

class ServiceSeeder extends Seeder
{
    public function run(): void
    {
        $services = [
            [
                'short_title' => 'Accounting',
                'title' => 'Professional Accounting Services',
                'sub_title' => 'Comprehensive accounting solutions for your business',
                'slug' => 'professional-accounting-services',
                'icon' => '<i class="fas fa-calculator"></i>',
                'status' => 1,
                'meta_keywords' => 'accounting services, bookkeeping, financial reporting, sri lanka accounting',
                'meta_description' => 'Professional accounting services including bookkeeping, financial reporting, and management accounts for businesses in Sri Lanka.',
                'description' => '<p>Our professional accounting services help businesses maintain accurate financial records and ensure compliance with Sri Lankan accounting standards. We provide comprehensive solutions tailored to your business needs.</p><h3>Our Accounting Services Include:</h3><ul><li>Monthly bookkeeping and record maintenance</li><li>Preparation of financial statements</li><li>Management accounts and reporting</li><li>Bank reconciliations</li><li>Accounts receivable and payable management</li><li>Fixed assets register maintenance</li><li>Financial analysis and insights</li></ul><p>Whether you\'re a small startup or an established corporation, our experienced accountants ensure your financial records are accurate, up-to-date, and compliant with all regulations.</p>',
                'order' => 1,
            ],
            [
                'short_title' => 'Tax',
                'title' => 'Tax Planning & Compliance',
                'sub_title' => 'Expert tax advice and filing services',
                'slug' => 'tax-planning-compliance',
                'icon' => '<i class="fas fa-file-invoice-dollar"></i>',
                'status' => 1,
                'meta_keywords' => 'tax planning, tax compliance, corporate tax, VAT, personal tax, sri lanka tax',
                'meta_description' => 'Expert tax planning and compliance services for businesses in Sri Lanka. Corporate tax, VAT, and personal tax solutions.',
                'description' => '<p>Navigate the complexities of Sri Lankan tax law with our expert guidance. We provide strategic tax planning to minimize your tax burden while ensuring full compliance with all regulations.</p><h3>Tax Services We Offer:</h3><ul><li>Corporate income tax planning and filing</li><li>VAT registration and return submissions</li><li>Personal income tax management</li><li>Tax audit support and representation</li><li>International tax advisory</li><li>Transfer pricing documentation</li><li>Tax incentive applications</li></ul><p>Our tax experts stay updated with the latest tax laws and regulations to provide you with accurate advice and ensure timely compliance.</p>',
                'order' => 2,
            ],
            [
                'short_title' => 'Payroll',
                'title' => 'Payroll Management Services',
                'sub_title' => 'Accurate and timely payroll processing',
                'slug' => 'payroll-management-services',
                'icon' => '<i class="fas fa-users"></i>',
                'status' => 1,
                'meta_keywords' => 'payroll services, PAYE, employee management, EPF, ETF, sri lanka payroll',
                'meta_description' => 'Complete payroll management services including PAYE compliance, payslip generation, and statutory deductions in Sri Lanka.',
                'description' => '<p>Let us handle your payroll processing while you focus on growing your business. Our services include salary calculations, PAYE submissions, EPF/ETF management, and employee payslips.</p><h3>Comprehensive Payroll Solutions:</h3><ul><li>Monthly payroll processing</li><li>PAYE calculations and submissions</li><li>EPF/ETF computations and remittances</li><li>Employee payslip generation</li><li>Annual tax certificates (T10)</li><li>Bonus and commission calculations</li><li>Leave management</li><li>Payroll reports and analytics</li></ul><p>We ensure accurate, timely, and compliant payroll processing, giving you peace of mind and your employees confidence in their compensation.</p>',
                'order' => 3,
            ],
            [
                'short_title' => 'Company Formation',
                'title' => 'Company Registration Services',
                'sub_title' => 'Start your business journey with us',
                'slug' => 'company-registration-services',
                'icon' => '<i class="fas fa-building"></i>',
                'status' => 1,
                'meta_keywords' => 'company registration, business formation, incorporation, business registration sri lanka',
                'meta_description' => 'Fast and efficient company registration services in Sri Lanka. We handle all documentation and regulatory requirements.',
                'description' => '<p>Starting a business in Sri Lanka? We make company registration simple and hassle-free. Our team handles all documentation, regulatory filings, and provides ongoing compliance support.</p><h3>Company Formation Services:</h3><ul><li>Name reservation and availability check</li><li>Preparation of incorporation documents</li><li>Articles of Association drafting</li><li>Director and shareholder documentation</li><li>Registration with Registrar of Companies</li><li>Tax registration (TIN, VAT if applicable)</li><li>EPF/ETF registration</li><li>Corporate bank account assistance</li></ul><p>We guide you through every step of the incorporation process, ensuring your business starts on the right legal foundation.</p>',
                'order' => 4,
            ],
            [
                'short_title' => 'Advisory',
                'title' => 'Business Advisory Services',
                'sub_title' => 'Strategic guidance for business growth',
                'slug' => 'business-advisory-services',
                'icon' => '<i class="fas fa-chart-line"></i>',
                'status' => 1,
                'meta_keywords' => 'business advisory, consulting, strategic planning, financial advisory sri lanka',
                'meta_description' => 'Professional business advisory services to help your company grow and succeed in the Sri Lankan market.',
                'description' => '<p>Our experienced advisors provide strategic guidance to help your business thrive. From financial planning to operational efficiency, we offer insights that drive sustainable growth.</p><h3>Advisory Services Include:</h3><ul><li>Business strategy development</li><li>Financial planning and analysis</li><li>Cash flow management</li><li>Business valuations</li><li>Merger and acquisition support</li><li>Risk management strategies</li><li>Performance improvement consulting</li><li>Succession planning</li></ul><p>Partner with us to unlock your business\'s full potential through data-driven insights and strategic recommendations.</p>',
                'order' => 5,
            ],
            [
                'short_title' => 'Audit',
                'title' => 'Audit & Assurance Services',
                'sub_title' => 'Independent verification and compliance',
                'slug' => 'audit-assurance-services',
                'icon' => '<i class="fas fa-search-dollar"></i>',
                'status' => 1,
                'meta_keywords' => 'audit services, statutory audit, internal audit, compliance audit sri lanka',
                'meta_description' => 'Professional audit and assurance services ensuring compliance with Sri Lankan accounting standards and regulations.',
                'description' => '<p>Our audit services provide independent verification of your financial statements, ensuring accuracy, compliance, and stakeholder confidence.</p><h3>Audit Services Offered:</h3><ul><li>Statutory audits</li><li>Internal audits</li><li>Tax audits</li><li>Special purpose audits</li><li>Compliance audits</li><li>Due diligence reviews</li><li>Systems and controls review</li></ul><p>Our qualified auditors follow international auditing standards while ensuring compliance with local regulations.</p>',
                'order' => 6,
            ],
            [
                'short_title' => 'Secretarial',
                'title' => 'Company Secretarial Services',
                'sub_title' => 'Corporate compliance and governance',
                'slug' => 'company-secretarial-services',
                'icon' => '<i class="fas fa-file-contract"></i>',
                'status' => 1,
                'meta_keywords' => 'company secretary, corporate compliance, annual returns, board minutes sri lanka',
                'meta_description' => 'Complete company secretarial services ensuring corporate compliance and good governance in Sri Lanka.',
                'description' => '<p>Ensure your company meets all statutory requirements with our comprehensive secretarial services. We handle all compliance matters, letting you focus on business growth.</p><h3>Secretarial Services:</h3><ul><li>Annual return filings</li><li>Board meeting coordination and minutes</li><li>Shareholder meeting management</li><li>Statutory register maintenance</li><li>Director and shareholder changes</li><li>Share transfers and allotments</li><li>Corporate governance advisory</li><li>Regulatory compliance monitoring</li></ul><p>Stay compliant with all company law requirements through our professional secretarial support.</p>',
                'order' => 7,
            ],
            [
                'short_title' => 'Bookkeeping',
                'title' => 'Bookkeeping Services',
                'sub_title' => 'Accurate financial record keeping',
                'slug' => 'bookkeeping-services',
                'icon' => '<i class="fas fa-book"></i>',
                'status' => 1,
                'meta_keywords' => 'bookkeeping, financial records, accounting entries, cloud bookkeeping sri lanka',
                'meta_description' => 'Professional bookkeeping services to maintain accurate financial records for your business in Sri Lanka.',
                'description' => '<p>Maintain accurate and up-to-date financial records with our professional bookkeeping services. We ensure every transaction is properly recorded and categorized.</p><h3>Bookkeeping Services Include:</h3><ul><li>Daily transaction recording</li><li>Sales and purchase ledger maintenance</li><li>Bank and cash reconciliations</li><li>Expense categorization</li><li>Invoice processing</li><li>Receipt and payment tracking</li><li>Cloud-based bookkeeping solutions</li><li>Monthly financial summaries</li></ul><p>Let our experienced bookkeepers handle your financial record-keeping while you focus on growing your business.</p>',
                'order' => 8,
            ],
        ];

        foreach ($services as $service) {
            Service::create($service);
        }
    }
}