<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\Blog;
use App\Models\Service;
use App\Models\Faq;

class BlogSeeder extends Seeder
{
    public function run(): void
    {
        $blogs = [
            [
                'title' => 'Understanding Tax Deadlines in Sri Lanka for 2025',
                'slug' => 'understanding-tax-deadlines-sri-lanka-2025',
                'image' => 'blog-images/tax-deadlines-2025.jpg',
                'content' => '<p>As we enter 2025, it\'s crucial for businesses in Sri Lanka to stay informed about important tax deadlines. Missing these deadlines can result in penalties and interest charges that could have been avoided with proper planning.</p><p>Key deadlines include: Corporate Tax Returns, VAT Returns, PAYE submissions, and more. This comprehensive guide will help you plan your tax calendar for the year ahead.</p>',
                'author' => 'John Smith',
                'category' => 'Tax Updates',
                'tags' => 'tax, deadlines, sri lanka, 2025',
                'meta_description' => 'Complete guide to tax deadlines in Sri Lanka for 2025. Stay compliant with our comprehensive tax calendar.',
                'meta_keywords' => 'tax deadlines, sri lanka tax, 2025 tax calendar',
                'status' => 1,
            ],
            [
                'title' => 'VAT Registration Requirements: What Every Business Should Know',
                'slug' => 'vat-registration-requirements-business-guide',
                'image' => 'blog-images/vat-registration.jpg',
                'content' => '<p>Value Added Tax (VAT) registration is mandatory for businesses exceeding certain turnover thresholds in Sri Lanka. Understanding when and how to register is essential for compliance.</p><p>This article covers the registration threshold, required documents, registration process, and ongoing compliance requirements for VAT-registered businesses.</p>',
                'author' => 'Sarah Johnson',
                'category' => 'VAT',
                'tags' => 'vat, registration, compliance, business',
                'meta_description' => 'Everything you need to know about VAT registration requirements in Sri Lanka. Step-by-step guide for businesses.',
                'meta_keywords' => 'vat registration, sri lanka vat, business compliance',
                'status' => 1,
            ],
            [
                'title' => 'Digital Transformation in Accounting: Cloud-Based Solutions',
                'slug' => 'digital-transformation-accounting-cloud-solutions',
                'image' => 'blog-images/cloud-accounting.jpg',
                'content' => '<p>The accounting industry is undergoing a significant digital transformation. Cloud-based accounting solutions are revolutionizing how businesses manage their finances.</p><p>Benefits include real-time access to financial data, automated backups, enhanced collaboration, and reduced IT costs. Learn how to make the transition smoothly.</p>',
                'author' => 'Michael Brown',
                'category' => 'Technology',
                'tags' => 'cloud accounting, digital transformation, technology',
                'meta_description' => 'Explore how cloud-based accounting solutions are transforming business finance management in Sri Lanka.',
                'meta_keywords' => 'cloud accounting, digital transformation, accounting software',
                'status' => 1,
            ],
            [
                'title' => 'Small Business Tax Deductions You Might Be Missing',
                'slug' => 'small-business-tax-deductions-guide',
                'image' => 'blog-images/tax-deductions.jpg',
                'content' => '<p>Many small businesses in Sri Lanka miss out on legitimate tax deductions that could significantly reduce their tax burden. Understanding what expenses are deductible is key to optimizing your tax position.</p><p>Common overlooked deductions include home office expenses, professional development costs, business insurance, and more.</p>',
                'author' => 'Emma Wilson',
                'category' => 'Tax Planning',
                'tags' => 'tax deductions, small business, tax planning',
                'meta_description' => 'Discover commonly missed tax deductions for small businesses in Sri Lanka. Maximize your tax savings legally.',
                'meta_keywords' => 'tax deductions, small business tax, sri lanka business',
                'status' => 1,
            ],
            [
                'title' => 'PAYE Compliance: A Complete Guide for Employers',
                'slug' => 'paye-compliance-guide-employers',
                'image' => 'blog-images/paye-guide.jpg',
                'content' => '<p>Pay As You Earn (PAYE) is a critical compliance area for all employers in Sri Lanka. This comprehensive guide covers everything from registration to monthly submissions.</p><p>Topics include employee registration, tax calculations, submission deadlines, penalties for non-compliance, and best practices for maintaining PAYE records.</p>',
                'author' => 'John Smith',
                'category' => 'Payroll',
                'tags' => 'paye, payroll, compliance, employers',
                'meta_description' => 'Complete PAYE compliance guide for employers in Sri Lanka. Learn about requirements, deadlines, and best practices.',
                'meta_keywords' => 'paye compliance, payroll tax, employer guide',
                'status' => 1,
            ],
        ];

        foreach ($blogs as $blog) {
            Blog::create($blog);
        }
    }
}

