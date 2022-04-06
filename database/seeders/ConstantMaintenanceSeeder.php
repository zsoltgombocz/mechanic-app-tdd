<?php

namespace Database\Seeders;

use App\Models\Maintenance;
use Illuminate\Database\Seeder;

class ConstantMaintenanceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $default_maintenances = [
            ['name' => 'Olajcsere'],
            ['name' => 'Fékfolyadék csere'],
            ['name' => 'Izzó csere'],
        ];
        Maintenance::insert($default_maintenances);
    }
}
