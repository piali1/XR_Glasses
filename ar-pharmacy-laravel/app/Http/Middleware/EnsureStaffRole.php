<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureStaffRole
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $staff = $request->session()->get('staff');

        if (! $staff) {
            return redirect('/login')->with('error', 'Please sign in as pharmacy staff first.');
        }

        if ($roles && ! in_array($staff['role'] ?? null, $roles, true)) {
            return redirect('/login')->with('error', 'This area requires one of these roles: ' . implode(', ', $roles) . '.');
        }

        return $next($request);
    }
}
