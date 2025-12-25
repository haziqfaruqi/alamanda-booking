<?php

namespace Database\Seeders;

use App\Models\Package;
use Illuminate\Database\Seeder;

class PackageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $packages = [
            [
                'name' => 'Standard 2D1N',
                'duration' => '2D1N',
                'price_standard' => 3900.00,
                'price_fullboard_adult' => null,
                'price_fullboard_child' => null,
                'description' => 'Standard package for 2 Days 1 Night',
                'is_active' => true,
            ],
            [
                'name' => 'Standard 3D2N',
                'duration' => '3D2N',
                'price_standard' => 6000.00,
                'price_fullboard_adult' => null,
                'price_fullboard_child' => null,
                'description' => 'Standard package for 3 Days 2 Nights',
                'is_active' => true,
            ],
            [
                'name' => 'Standard 4D3N',
                'duration' => '4D3N',
                'price_standard' => 8900.00,
                'price_fullboard_adult' => null,
                'price_fullboard_child' => null,
                'description' => 'Standard package for 4 Days 3 Nights',
                'is_active' => true,
            ],
            [
                'name' => 'Full Board 2D1N',
                'duration' => '2D1N',
                'price_standard' => null,
                'price_fullboard_adult' => 350.00,
                'price_fullboard_child' => 250.00,
                'description' => 'Full Board package for 2 Days 1 Night - RM350/adult, RM250/child',
                'is_active' => true,
            ],
            [
                'name' => 'Full Board 3D2N',
                'duration' => '3D2N',
                'price_standard' => null,
                'price_fullboard_adult' => 550.00,
                'price_fullboard_child' => 350.00,
                'description' => 'Full Board package for 3 Days 2 Nights - RM550/adult, RM350/child',
                'is_active' => true,
            ],
            [
                'name' => 'Full Board 4D3N',
                'duration' => '4D3N',
                'price_standard' => null,
                'price_fullboard_adult' => 550.00,
                'price_fullboard_child' => 350.00,
                'description' => 'Full Board package for 4 Days 3 Nights - RM550/adult, RM350/child',
                'is_active' => true,
            ],
        ];

        foreach ($packages as $package) {
            Package::create($package);
        }
    }
}
