<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ForceChangeDefaultPassword
{
    const DEFAULT_PASSWORD = 'DefaultP@ssword';

    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        if (
            $user &&
            $user->type === 'bujp' &&
            Hash::check(self::DEFAULT_PASSWORD, $user->password)
        ) {
            // Izinkan akses ke route ubah password agar tidak redirect loop
            if ($request->routeIs('profile.edit') || $request->routeIs('profile.update')) {
                return $next($request);
            }

            return redirect()->route('profile.edit')
                ->with('warning', 'Anda wajib mengganti password default sebelum melanjutkan.');
        }

        return $next($request);
    }
}
