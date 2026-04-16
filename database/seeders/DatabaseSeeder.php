<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $admin = User::firstOrCreate(['email' => 'admin@admin.com'], [
            'name' => 'Admin User',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        $proposer = User::firstOrCreate(['email' => 'proposer@admin.com'], [
            'name' => 'Proposer User',
            'password' => bcrypt('password'),
            'role' => 'proposer',
        ]);

        $farmer = User::firstOrCreate(['email' => 'farmer@admin.com'], [
            'name' => 'Farmer User',
            'password' => bcrypt('password'),
            'role' => 'farmer',
        ]);

        if (!$farmer->farmerProfile) {
            $farmer->farmerProfile()->create([
                'land_size' => '5 Acres',
                'crop_type' => 'Wheat',
                'region' => 'North',
            ]);
        }

        if (!$proposer->proposerProfile) {
            $proposer->proposerProfile()->create([
                'company_name' => 'AgriProtect Insurance Co.',
            ]);
        }

        if ($proposer->insurancePlans()->count() === 0) {
            $proposer->insurancePlans()->create([
                'crop_type' => 'Wheat',
                'region' => 'North',
                'premium' => 500.00,
                'coverage' => 10000.00,
                'duration' => 12,
            ]);
        }
    }
}
