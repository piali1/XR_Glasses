<?php

namespace Database\Seeders;

use App\Models\StaffUser;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class StaffUserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            [
                'name' => 'Mia Keller',
                'username' => 'pta.demo',
                'email' => 'pta.demo@example.test',
                'role' => 'pta',
                'role_label' => 'PTA',
                'department' => 'Rezeptur / Compounding',
                'permissions' => [
                    'Run guided pharmacy workflows',
                    'Scan QR-coded materials',
                    'Complete checklists',
                    'Report process issues',
                ],
            ],
            [
                'name' => 'Dr. Lena Hofmann',
                'username' => 'pharmacist.demo',
                'email' => 'pharmacist.demo@example.test',
                'role' => 'pharmacist',
                'role_label' => 'Pharmacist',
                'department' => 'Quality Review',
                'permissions' => [
                    'Review process evidence',
                    'Inspect material validation',
                    'Check QM documentation',
                    'Prepare professional release decision',
                ],
            ],
            [
                'name' => 'Jonas Weber',
                'username' => 'supervisor.demo',
                'email' => 'supervisor.demo@example.test',
                'role' => 'supervisor',
                'role_label' => 'Supervisor',
                'department' => 'Pharmacy Supervision',
                'permissions' => [
                    'Review completed batches',
                    'Approve or reject documented workflows',
                    'Inspect audit timeline',
                    'Evaluate reported issues',
                ],
            ],
            [
                'name' => 'Admin User',
                'username' => 'admin.demo',
                'email' => 'admin.demo@example.test',
                'role' => 'admin',
                'role_label' => 'Admin',
                'department' => 'XR Hub Administration',
                'permissions' => [
                    'Maintain SOP content',
                    'Maintain checklist and training content',
                    'Update approval status',
                    'Manage content metadata',
                ],
            ],
        ];

        foreach ($users as $user) {
            StaffUser::updateOrCreate(
                ['username' => $user['username']],
                array_merge($user, [
                    'password' => Hash::make('pharmacy-demo'),
                    'is_active' => true,
                ])
            );
        }
    }
}
