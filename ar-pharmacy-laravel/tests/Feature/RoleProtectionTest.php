<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoleProtectionTest extends TestCase
{
    use RefreshDatabase;

    private function staffSession(string $role, string $name = 'Demo User'): array
    {
        $labels = [
            'pta' => 'PTA',
            'pharmacist' => 'Pharmacist',
            'supervisor' => 'Supervisor',
            'admin' => 'Admin',
        ];

        return [
            'staff' => [
                'username' => $role . '.demo',
                'name' => $name,
                'role' => $role,
                'role_label' => $labels[$role],
                'department' => 'Demo Department',
                'permissions' => ['Demo permission'],
                'signed_in_at' => now()->toDateTimeString(),
            ],
        ];
    }

    public function test_guest_is_redirected_from_admin_content_management(): void
    {
        $this->get('/admin/content')
            ->assertRedirect('/login')
            ->assertSessionHas('error');
    }

    public function test_pta_cannot_access_admin_content_management(): void
    {
        $this->withSession($this->staffSession('pta', 'Mia Keller'))
            ->get('/admin/content')
            ->assertRedirect('/login')
            ->assertSessionHas('error');
    }

    public function test_admin_can_access_content_management(): void
    {
        $this->withSession($this->staffSession('admin', 'Admin User'))
            ->get('/admin/content')
            ->assertOk()
            ->assertSee('Content Management');
    }

    public function test_pharmacist_can_enter_release_area_but_pta_cannot(): void
    {
        $this->withSession($this->staffSession('pharmacist', 'Dr. Lena Hofmann'))
            ->get('/pharmacist/release/latest')
            ->assertRedirect('/history');

        $this->withSession($this->staffSession('pta', 'Mia Keller'))
            ->get('/pharmacist/release/latest')
            ->assertRedirect('/login')
            ->assertSessionHas('error');
    }
}
