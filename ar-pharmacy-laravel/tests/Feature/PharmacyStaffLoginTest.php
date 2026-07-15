<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PharmacyStaffLoginTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_page_displays_demo_accounts(): void
    {
        $this->get('/login')
            ->assertOk()
            ->assertSee('Pharmacy Staff Login')
            ->assertSee('pta.demo')
            ->assertSee('pharmacist.demo')
            ->assertSee('supervisor.demo')
            ->assertSee('admin.demo');
    }

    public function test_pharmacist_can_sign_in_with_demo_credentials(): void
    {
        $response = $this->post('/login', [
            'username' => 'pharmacist.demo',
            'password' => 'pharmacy-demo',
        ]);

        $response->assertRedirect('/audit/latest');
        $response->assertSessionHas('staff');

        $this->assertSame('pharmacist', session('staff.role'));
        $this->assertSame('Dr. Lena Hofmann', session('staff.name'));
    }

    public function test_invalid_login_is_rejected(): void
    {
        $this->from('/login')
            ->post('/login', [
                'username' => 'pharmacist.demo',
                'password' => 'wrong-password',
            ])
            ->assertRedirect('/login')
            ->assertSessionHasErrors('username');
    }
}
