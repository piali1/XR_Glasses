<?php

return [
    'accounts' => [
        'pta.demo' => [
            'name' => 'Mia Keller',
            'role' => 'pta',
            'role_label' => 'PTA',
            'department' => 'Rezeptur / Compounding',
            'password' => 'pharmacy-demo',
            'landing_page' => '/',
            'permissions' => [
                'Run guided pharmacy workflows',
                'Scan QR-coded materials',
                'Complete checklists',
                'Report process issues',
            ],
        ],
        'pharmacist.demo' => [
            'name' => 'Dr. Lena Hofmann',
            'role' => 'pharmacist',
            'role_label' => 'Pharmacist',
            'department' => 'Quality Review',
            'password' => 'pharmacy-demo',
            'landing_page' => '/audit/latest',
            'permissions' => [
                'Review process evidence',
                'Inspect material validation',
                'Check QM documentation',
                'Prepare professional release decision',
            ],
        ],
        'supervisor.demo' => [
            'name' => 'Jonas Weber',
            'role' => 'supervisor',
            'role_label' => 'Supervisor',
            'department' => 'Pharmacy Supervision',
            'password' => 'pharmacy-demo',
            'landing_page' => '/history',
            'permissions' => [
                'Review completed batches',
                'Approve or reject documented workflows',
                'Inspect audit timeline',
                'Evaluate reported issues',
            ],
        ],
        'admin.demo' => [
            'name' => 'Admin User',
            'role' => 'admin',
            'role_label' => 'Admin',
            'department' => 'XR Hub Administration',
            'password' => 'pharmacy-demo',
            'landing_page' => '/admin/content',
            'permissions' => [
                'Maintain SOP content',
                'Maintain checklist and training content',
                'Update approval status',
                'Manage content metadata',
            ],
        ],
    ],
];
