<?php

namespace App\Http\Controllers;

use App\Models\StaffUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class DemoAuthController extends Controller
{
    public function showLogin(Request $request)
    {
        $accounts = [];

        if (Schema::hasTable('staff_users')) {
            $accounts = StaffUser::query()
                ->where('is_active', true)
                ->orderBy('role')
                ->get()
                ->mapWithKeys(function (StaffUser $user) {
                    return [
                        $user->username => [
                            'name' => $user->name,
                            'role' => $user->role,
                            'role_label' => $user->role_label,
                            'department' => $user->department,
                            'password' => 'pharmacy-demo',
                            'permissions' => $user->permissions ?? [],
                        ],
                    ];
                })
                ->all();
        }

        if (! $accounts) {
            $accounts = config('demo_staff.accounts', []);
        }

        return view('auth.pharmacy-login', [
            'accounts' => $accounts,
        ]);
    }

    public function login(Request $request)
    {
        $validated = $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        $username = $validated['username'];
        $password = $validated['password'];

        $user = Schema::hasTable('staff_users')
            ? StaffUser::query()
                ->where('username', $username)
                ->where('is_active', true)
                ->first()
            : null;

        if ($user && Hash::check($password, $user->password)) {
            $request->session()->regenerate();

            $request->session()->put('staff', [
                'staff_user_id' => $user->id,
                'username' => $user->username,
                'name' => $user->name,
                'role' => $user->role,
                'role_label' => $user->role_label,
                'department' => $user->department,
                'permissions' => $user->permissions ?? [],
                'signed_in_at' => now()->toDateTimeString(),
            ]);

            return redirect()
                ->intended($this->landingPageForRole($user->role))
                ->with('status', 'Signed in as ' . $user->role_label . '.');
        }

        return back()
            ->withErrors(['username' => 'Invalid pharmacy staff credentials.'])
            ->onlyInput('username');
    }

    public function logout(Request $request)
    {
        $request->session()->forget('staff');
        $request->session()->regenerateToken();

        return redirect('/login')->with('status', 'Signed out successfully.');
    }

    private function landingPageForRole(string $role): string
    {
        return match ($role) {
            'admin' => '/admin/content',
            'pharmacist' => '/audit/latest',
            'supervisor' => '/history',
            default => '/',
        };
    }
}
