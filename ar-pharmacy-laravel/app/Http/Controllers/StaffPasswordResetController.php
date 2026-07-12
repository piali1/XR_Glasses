<?php

namespace App\Http\Controllers;

use App\Models\StaffUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class StaffPasswordResetController extends Controller
{
    public function requestForm()
    {
        return view('auth.staff-password-request');
    }

    public function sendResetLink(Request $request)
    {
        $validated = $request->validate([
            'email' => ['required', 'email'],
        ]);

        if (! Schema::hasTable('staff_users')) {
            return back()->withErrors(['email' => 'Staff user table is not available.']);
        }

        $user = StaffUser::query()
            ->where('email', $validated['email'])
            ->where('is_active', true)
            ->first();

        if (! $user) {
            return back()->withErrors(['email' => 'No active staff user found for this email.']);
        }

        $token = Str::random(64);

        DB::table('staff_password_reset_tokens')->updateOrInsert(
            ['email' => $user->email],
            [
                'token' => Hash::make($token),
                'created_at' => now(),
            ]
        );

        $resetLink = url('/password/reset/' . $token . '?email=' . urlencode($user->email));

        return back()
            ->with('status', 'Demo reset link generated. In production this would be sent by email.')
            ->with('reset_link', $resetLink);
    }

    public function resetForm(Request $request, string $token)
    {
        return view('auth.staff-password-reset', [
            'token' => $token,
            'email' => $request->query('email'),
        ]);
    }

    public function updatePassword(Request $request)
    {
        $validated = $request->validate([
            'email' => ['required', 'email'],
            'token' => ['required', 'string'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $record = DB::table('staff_password_reset_tokens')
            ->where('email', $validated['email'])
            ->first();

        if (! $record || ! Hash::check($validated['token'], $record->token)) {
            return back()->withErrors(['email' => 'Invalid or expired reset token.']);
        }

        $user = StaffUser::query()
            ->where('email', $validated['email'])
            ->firstOrFail();

        $user->update([
            'password' => Hash::make($validated['password']),
        ]);

        DB::table('staff_password_reset_tokens')
            ->where('email', $validated['email'])
            ->delete();

        return redirect('/login')->with('status', 'Password updated. Please sign in with the new password.');
    }
}
