<?php

namespace Tests\Feature;

use App\Models\StaffUser;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class StaffPasswordResetTest extends TestCase
{
    use RefreshDatabase;

    public function test_staff_password_reset_link_can_be_generated(): void
    {
        StaffUser::create([
            'name' => 'Dr. Lena Hofmann',
            'username' => 'pharmacist.demo',
            'email' => 'pharmacist.demo@example.test',
            'role' => 'pharmacist',
            'role_label' => 'Pharmacist',
            'department' => 'Quality Review',
            'password' => Hash::make('pharmacy-demo'),
            'permissions' => ['Review process evidence'],
            'is_active' => true,
        ]);

        $this->from('/password/reset')
            ->post('/password/email', [
                'email' => 'pharmacist.demo@example.test',
            ])
            ->assertRedirect('/password/reset')
            ->assertSessionHas('reset_link')
            ->assertSessionHas('status');
    }
}
