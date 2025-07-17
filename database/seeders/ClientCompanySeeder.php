<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Client;
use App\Models\Company;
use Illuminate\Support\Facades\DB;

class ClientCompanySeeder extends Seeder
{
    public function run(): void
    {
        $relationships = [
            ['client_id' => 1, 'company_id' => 1],
            ['client_id' => 1, 'company_id' => 7],
            ['client_id' => 2, 'company_id' => 2],
            ['client_id' => 3, 'company_id' => 3],
            ['client_id' => 4, 'company_id' => 4],
            ['client_id' => 5, 'company_id' => 5],
            ['client_id' => 6, 'company_id' => 6],
            ['client_id' => 2, 'company_id' => 7],
        ];

        foreach ($relationships as $relationship) {
            DB::table('client_company')->insert([
                'client_id' => $relationship['client_id'],
                'company_id' => $relationship['company_id'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}