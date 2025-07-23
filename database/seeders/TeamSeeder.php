<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Team;

class TeamSeeder extends Seeder
{
    public function run(): void
    {
        $teams = [
            [
                'name' => 'Richard Dawson',
                'image' => 'team-images/richard-dawson.jpg',
                'designation' => 'Managing Partner',
                'status' => 1,
            ],
            [
                'name' => 'Ishara Wickramasinghe',
                'image' => 'team-images/ishara-wickramasinghe.jpg',
                'designation' => 'Senior Tax Consultant',
                'status' => 1,
            ],
            [
                'name' => 'Thilini Rajapaksa',
                'image' => 'team-images/thilini-rajapaksa.jpg',
                'designation' => 'Audit Manager',
                'status' => 1,
            ],
            [
                'name' => 'Kasun Mendis',
                'image' => 'team-images/kasun-mendis.jpg',
                'designation' => 'Payroll Specialist',
                'status' => 1,
            ],
            [
                'name' => 'Anjali Perera',
                'image' => 'team-images/anjali-perera.jpg',
                'designation' => 'Client Relations Manager',
                'status' => 1,
            ],
            [
                'name' => 'Dinesh Kumar',
                'image' => 'team-images/dinesh-kumar.jpg',
                'designation' => 'Senior Accountant',
                'status' => 1,
            ],
            [
                'name' => 'Samantha Fernando',
                'image' => 'team-images/samantha-fernando.jpg',
                'designation' => 'Company Secretary',
                'status' => 1,
            ],
            [
                'name' => 'Ruwan Silva',
                'image' => 'team-images/ruwan-silva.jpg',
                'designation' => 'IT Manager',
                'status' => 1,
            ],
            [
                'name' => 'Nadeeka Jayawardena',
                'image' => 'team-images/nadeeka-jayawardena.jpg',
                'designation' => 'Junior Accountant',
                'status' => 1,
            ],
            [
                'name' => 'Mohamed Rasheed',
                'image' => 'team-images/mohamed-rasheed.jpg',
                'designation' => 'Business Advisory Consultant',
                'status' => 1,
            ],
        ];

        foreach ($teams as $team) {
            Team::create($team);
        }
    }
}