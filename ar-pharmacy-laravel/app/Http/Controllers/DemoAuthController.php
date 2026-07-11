<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DemoAuthController extends Controller
{
    public function showLogin(Request $request)
    {
        return view('auth.pharmacy-login', [
            'accounts' => config('demo_staff.accounts', []),
        ]);
    }

    public function login(Request $request)
    {
        $validated = $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        $accounts = config('demo_staff.accounts', []);
        $username = $validated['username'];
        $password = $validated['password'];

        if (! isset($accounts[$username]) || ! hash_equals($accounts[$username]['password'], $password)) {
            return back()
                ->withErrors(['username' => 'Invalid pharmacy staff credentials.'])
                ->onlyInput('username');
        }

        $account = $accounts[$username];

        $request->session()->regenerate();

        $request->session()->put('staff', [
            'username' => $username,
            'name' => $account['name'],
            'role' => $account['role'],
            'role_label' => $account['role_label'],
            'department' => $account['department'],
            'permissions' => $account['permissions'],
            'signed_in_at' => now()->toDateTimeString(),
        ]);

        return redirect()
            ->intended($account['landing_page'] ?? '/')
            ->with('status', 'Signed in as ' . $account['role_label'] . '.');
    }

    public function logout(Request $request)
    {
        $request->session()->forget('staff');
        $request->session()->regenerateToken();

        return redirect('/login')->with('status', 'Signed out successfully.');
    }
}
