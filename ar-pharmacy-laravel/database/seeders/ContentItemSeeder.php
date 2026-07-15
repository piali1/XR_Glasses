<?php

namespace Database\Seeders;

use App\Models\ContentItem;
use Illuminate\Database\Seeder;

class ContentItemSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            [
                'type' => 'workflow_template',
                'title' => 'NRF-style ointment preparation workflow',
                'version' => 'v1.0',
                'valid_from' => '2026-01-01',
                'valid_until' => '2026-12-31',
                'area' => 'Compounding / Rezeptur',
                'responsible_role' => 'Pharmacist / Supervisor',
                'approval_status' => 'approved training template',
                'process' => 'ointment',
                'step_number' => null,
                'display_context' => 'hub',
                'content' => 'Structured workflow template for ointment preparation with material validation, checklists, warnings, required process times and digital documentation.',
            ],
            [
                'type' => 'sop',
                'title' => 'Workspace preparation SOP',
                'version' => 'v1.0',
                'valid_from' => '2026-01-01',
                'valid_until' => '2026-12-31',
                'area' => 'Compounding / Rezeptur',
                'responsible_role' => 'Pharmacist',
                'approval_status' => 'approved training template',
                'process' => 'ointment',
                'step_number' => 1,
                'display_context' => 'workflow',
                'content' => 'Before starting the preparation, verify the prescription, clean the workspace and prepare all required tools and containers.',
            ],
            [
                'type' => 'checklist',
                'title' => 'Pre-preparation checklist',
                'version' => 'v1.0',
                'valid_from' => '2026-01-01',
                'valid_until' => '2026-12-31',
                'area' => 'Compounding / Rezeptur',
                'responsible_role' => 'PTA / Pharmacist',
                'approval_status' => 'approved training template',
                'process' => 'ointment',
                'step_number' => 1,
                'display_context' => 'workflow',
                'content' => 'Confirm prescription check, clean workspace, required materials and preparation tray before continuing.',
            ],
            [
                'type' => 'sop',
                'title' => 'Weighing accuracy SOP',
                'version' => 'v1.0',
                'valid_from' => '2026-01-01',
                'valid_until' => '2026-12-31',
                'area' => 'Compounding / Rezeptur',
                'responsible_role' => 'Pharmacist',
                'approval_status' => 'approved training template',
                'process' => 'ointment',
                'step_number' => 2,
                'display_context' => 'workflow',
                'content' => 'Calibrate the scale, verify the ingredient identity and document the measured value before proceeding.',
            ],
            [
                'type' => 'training',
                'title' => 'Mixing technique training',
                'version' => 'v1.0',
                'valid_from' => '2026-01-01',
                'valid_until' => '2026-12-31',
                'area' => 'Compounding / Rezeptur',
                'responsible_role' => 'Training Supervisor',
                'approval_status' => 'approved training template',
                'process' => 'ointment',
                'step_number' => 3,
                'display_context' => 'workflow',
                'content' => 'Training placeholder: Demonstrates how to mix a semisolid base until a homogeneous consistency is reached.',
            ],
            [
                'type' => 'training',
                'title' => 'Active ingredient distribution training',
                'version' => 'v1.0',
                'valid_from' => '2026-01-01',
                'valid_until' => '2026-12-31',
                'area' => 'Compounding / Rezeptur',
                'responsible_role' => 'Training Supervisor',
                'approval_status' => 'approved training template',
                'process' => 'ointment',
                'step_number' => 4,
                'display_context' => 'workflow',
                'content' => 'Training placeholder: Explains gradual addition and even distribution of the active ingredient.',
            ],
            [
                'type' => 'qm',
                'title' => 'Final label and release evidence',
                'version' => 'v1.0',
                'valid_from' => '2026-01-01',
                'valid_until' => '2026-12-31',
                'area' => 'Quality Management',
                'responsible_role' => 'Supervisor',
                'approval_status' => 'approved training template',
                'process' => 'ointment',
                'step_number' => 5,
                'display_context' => 'workflow',
                'content' => 'The final container, label, expiry date and storage instruction must be checked and documented before release.',
            ],
            [
                'type' => 'compliance',
                'title' => 'No patient data in prototype',
                'version' => 'v1.0',
                'valid_from' => '2026-01-01',
                'valid_until' => '2026-12-31',
                'area' => 'Data Protection',
                'responsible_role' => 'Admin / Pharmacist',
                'approval_status' => 'concept only',
                'process' => null,
                'step_number' => null,
                'display_context' => 'hub',
                'content' => 'The prototype uses demo data only and does not store real patient data, prescriptions or licensed NRF content.',
            ],
            [
                'type' => 'role_permission',
                'title' => 'Role and permission concept',
                'version' => 'v1.0',
                'valid_from' => '2026-01-01',
                'valid_until' => '2026-12-31',
                'area' => 'Access Control',
                'responsible_role' => 'Admin',
                'approval_status' => 'concept only',
                'process' => null,
                'step_number' => null,
                'display_context' => 'hub',
                'content' => 'PTA users run workflows, pharmacists review process data, supervisors approve or reject batches and admins maintain content templates.',
            ],
        ];

        foreach ($items as $item) {
            ContentItem::updateOrCreate(
                [
                    'type' => $item['type'],
                    'title' => $item['title'],
                    'process' => $item['process'],
                    'step_number' => $item['step_number'],
                ],
                $item
            );
        }
    }
}
