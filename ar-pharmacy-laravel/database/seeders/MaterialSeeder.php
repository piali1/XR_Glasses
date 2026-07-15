<?php

namespace Database\Seeders;

use App\Models\Material;
use App\Models\RecipeStep;
use App\Models\RecipeTemplate;
use Illuminate\Database\Seeder;

class MaterialSeeder extends Seeder
{
    public function run(): void
    {
        $template = RecipeTemplate::updateOrCreate(
            ['reference_code' => 'NRF-DEMO-OINTMENT-001'],
            [
                'process' => 'ointment',
                'title' => 'NRF-style Ointment Preparation Demo',
                'reference_source' => 'NRF-compatible preparation template structure',
                'reference_code' => 'NRF-DEMO-OINTMENT-001',
                'dosage_form' => 'Semisolid preparation / ointment',
                'source_note' => 'Demo data only. The structure is designed for NRF-based or training-based preparation instructions. It does not copy protected NRF text.',
                'is_demo' => true,
            ]
        );

        $steps = [
            [
                1,
                'Check materials',
                'Prepare all containers, tools and ingredients before starting the ointment preparation.',
                'Verify prescription, ingredient identity and clean workspace before continuing.',
                'Highlight the material tray and check whether all required items are present.',
                'medium',
                0,
                ['Prescription checked', 'Workspace cleaned', 'All materials available'],
            ],
            [
                2,
                'Weigh ingredients',
                'Weigh each ingredient carefully and compare the values with the process instruction.',
                'Wrong quantities can change the concentration of the final preparation.',
                'Focus on the scale and confirm the displayed weight before continuing.',
                'high',
                0,
                ['Scale calibrated', 'Correct ingredient selected', 'Weight value documented'],
            ],
            [
                3,
                'Mix base substance',
                'Mix the base substance until it reaches an even and stable consistency.',
                'Do not continue if the base is not homogeneous.',
                'The overlay would guide the stirring area and show the required mixing direction.',
                'medium',
                30,
                ['Base substance prepared', 'Mixing tool selected', 'Consistency visually checked'],
            ],
            [
                4,
                'Add active ingredient',
                'Add the active ingredient slowly and continue mixing until evenly distributed.',
                'Uneven distribution can affect dosage accuracy.',
                'The assistant would highlight the active ingredient and the mixing area.',
                'high',
                45,
                ['Active ingredient verified', 'Ingredient added gradually', 'Distribution checked'],
            ],
            [
                5,
                'Fill and label',
                'Fill the preparation into the correct container and apply the required label.',
                'Final label and storage instructions must be checked before dispensing.',
                'The overlay would highlight the final container and label position.',
                'medium',
                0,
                ['Correct container selected', 'Label applied', 'Expiry date and storage checked'],
            ],
        ];

        foreach ($steps as [$number, $title, $description, $warning, $arHint, $risk, $timer, $checklist]) {
            RecipeStep::updateOrCreate(
                [
                    'recipe_template_id' => $template->id,
                    'step_number' => $number,
                ],
                [
                    'title' => $title,
                    'description' => $description,
                    'warning' => $warning,
                    'ar_hint' => $arHint,
                    'risk' => $risk,
                    'timer_seconds' => $timer,
                    'checklist_items' => $checklist,
                ]
            );
        }

        $materials = [
            [1, 'Prescription document', 'MAT-PRESCRIPTION-DOCUMENT'],
            [1, 'Clean workspace', 'MAT-CLEAN-WORKSPACE'],
            [1, 'Preparation tray', 'MAT-PREPARATION-TRAY'],

            [2, 'Digital scale', 'MAT-DIGITAL-SCALE'],
            [2, 'Active ingredient', 'MAT-ACTIVE-INGREDIENT'],
            [2, 'Weighing paper', 'MAT-WEIGHING-PAPER'],

            [3, 'Ointment base', 'MAT-OINTMENT-BASE'],
            [3, 'Mixing bowl', 'MAT-MIXING-BOWL'],
            [3, 'Spatula', 'MAT-SPATULA'],

            [4, 'Verified active ingredient', 'MAT-VERIFIED-ACTIVE-INGREDIENT'],
            [4, 'Prepared base', 'MAT-PREPARED-BASE'],
            [4, 'Mixing tool', 'MAT-MIXING-TOOL'],

            [5, 'Final container', 'MAT-FINAL-CONTAINER'],
            [5, 'Printed label', 'MAT-PRINTED-LABEL'],
            [5, 'Storage instruction', 'MAT-STORAGE-INSTRUCTION'],
        ];

        foreach ($materials as [$stepNumber, $name, $code]) {
            Material::updateOrCreate(
                ['code' => $code],
                [
                    'recipe_template_id' => $template->id,
                    'process' => 'ointment',
                    'step_number' => $stepNumber,
                    'name' => $name,
                    'is_required' => true,
                ]
            );
        }
    }
}
