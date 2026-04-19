<?php

namespace Database\Seeders;

use App\Models\MaintenanceService;
use App\Models\Package;
use Illuminate\Database\Seeder;

class TrialSubscriptionSeeder extends Seeder
{
    public function run(): void
    {
        $acCleaning = MaintenanceService::create(['name' => 'AC Cleaning', 'price' => 50.00]);
        $plumbing = MaintenanceService::create(['name' => 'Plumbing Repair', 'price' => 75.00]);
        $electrical = MaintenanceService::create(['name' => 'Electrical Inspection', 'price' => 60.00]);

        $homeBundle = Package::create(['name' => 'Complete Home Bundle', 'price' => 150.00]);

        $homeBundle->services()->attach([
            $acCleaning->id,
            $plumbing->id,
            $electrical->id
        ]);
    }
}
