<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            // UserSeeder::class,
            // ClientSeeder::class,
            // CompanySeeder::class,
            // ClientCompanySeeder::class,
            // CompanyDetailsSeeder::class,
            // SelfAssessmentSeeder::class,
            // BlogSeeder::class,
            // ServiceSeeder::class,
            FaqSeeder::class,
            TestimonialSeeder::class,
            TeamSeeder::class,
            PartnerSeeder::class,
            ContactSeeder::class,
            CompanyChatListSeeder::class,
            EngagementLetterCompanySeeder::class,
        ]);
    }
}