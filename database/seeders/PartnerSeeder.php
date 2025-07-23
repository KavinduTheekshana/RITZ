<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Partner;

class PartnerSeeder extends Seeder
{
    public function run(): void
    {
        $partners = [
            [
                'logo' => 'partner-logos/quickbooks.png',
                'name' => 'QuickBooks',
                'status' => 1,
            ],
            [
                'logo' => 'partner-logos/xero.png',
                'name' => 'Xero',
                'status' => 1,
            ],
            [
                'logo' => 'partner-logos/sage.png',
                'name' => 'Sage',
                'status' => 1,
            ],
            [
                'logo' => 'partner-logos/icasl.png',
                'name' => 'Institute of Chartered Accountants of Sri Lanka',
                'status' => 1,
            ],
            [
                'logo' => 'partner-logos/aat.png',
                'name' => 'Association of Accounting Technicians',
                'status' => 1,
            ],
            [
                'logo' => 'partner-logos/cima.png',
                'name' => 'CIMA Sri Lanka',
                'status' => 1,
            ],
            [
                'logo' => 'partner-logos/acca.png',
                'name' => 'ACCA Sri Lanka',
                'status' => 1,
            ],
            [
                'logo' => 'partner-logos/microsoft.png',
                'name' => 'Microsoft Partner',
                'status' => 1,
            ],
            [
                'logo' => 'partner-logos/chamber.png',
                'name' => 'Ceylon Chamber of Commerce',
                'status' => 1,
            ],
            [
                'logo' => 'partner-logos/slasscom.png',
                'name' => 'SLASSCOM',
                'status' => 1,
            ],
        ];

        foreach ($partners as $partner) {
            Partner::create($partner);
        }
    }
}