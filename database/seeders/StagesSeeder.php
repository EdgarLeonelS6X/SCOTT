<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Stage;

class StagesSeeder extends Seeder
{
    public function run(): void
    {
        $stages = [
            ['name' => 'ORIGIN', 'status' => true],
            ['name' => 'ORIGIN CEF', 'status' => false],
            ['name' => 'CDN TELMEX', 'status' => true],
            ['name' => 'CDN CEF+', 'status' => true],
            ['name' => 'CDN TELMEX/CEF+', 'status' => true],
            ['name' => 'DTH', 'status' => true],
            ['name' => 'OVERON', 'status' => true],
        ];

        foreach ($stages as $stage) {
            Stage::create([
                'name' => $stage['name'],
                'status' => $stage['status'],
            ]);
        }

        $this->command->info('Stages inserted correctly.');
    }
}
