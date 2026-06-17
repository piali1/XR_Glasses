<?php

namespace Database\Seeders;

use App\Models\Material;
use Illuminate\Database\Seeder;

class MaterialSeeder extends Seeder
{
    public function run(): void
    {
        $materials = [
            ['ointment', 1, 'Prescription document', 'MAT-PRESCRIPTION-DOCUMENT'],
            ['ointment', 1, 'Clean workspace', 'MAT-CLEAN-WORKSPACE'],
            ['ointment', 1, 'Preparation tray', 'MAT-PREPARATION-TRAY'],

            ['ointment', 2, 'Digital scale', 'MAT-DIGITAL-SCALE'],
            ['ointment', 2, 'Active ingredient', 'MAT-ACTIVE-INGREDIENT'],
            ['ointment', 2, 'Weighing paper', 'MAT-WEIGHING-PAPER'],

            ['ointment', 3, 'Ointment base', 'MAT-OINTMENT-BASE'],
            ['ointment', 3, 'Mixing bowl', 'MAT-MIXING-BOWL'],
            ['ointment', 3, 'Spatula', 'MAT-SPATULA'],

            ['ointment', 4, 'Verified active ingredient', 'MAT-VERIFIED-ACTIVE-INGREDIENT'],
            ['ointment', 4, 'Prepared base', 'MAT-PREPARED-BASE'],
            ['ointment', 4, 'Mixing tool', 'MAT-MIXING-TOOL'],

            ['ointment', 5, 'Final container', 'MAT-FINAL-CONTAINER'],
            ['ointment', 5, 'Printed label', 'MAT-PRINTED-LABEL'],
            ['ointment', 5, 'Storage instruction', 'MAT-STORAGE-INSTRUCTION'],
        ];

        foreach ($materials as [$process, $stepNumber, $name, $code]) {
            Material::updateOrCreate(
                ['code' => $code],
                [
                    'process' => $process,
                    'step_number' => $stepNumber,
                    'name' => $name,
                    'is_required' => true,
                ]
            );
        }
    }
}
