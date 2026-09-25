<?php

namespace App\Http\Middleware;

use App\Models\ExternalAuditor;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Auditor external hanya boleh membuka halaman Audit SMP untuk auditor.
 * Akun yang sudah lewat tanggal kedaluwarsa (atau datanya sudah dihapus) tidak bisa masuk lagi.
 */
class RestrictExternalAuditor
{
    /** Nama route (atau awalan) yang boleh diakses auditor external. */
    protected $allowed = [
        'auditor.audit-smp-score.',
        'profile.edit',
        'profile.update',
        'logout',
    ];

    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        if (!$user || !$user->hasRole(ExternalAuditor::ROLE)) {
            return $next($request);
        }

        $record = ExternalAuditor::forUser($user);

        if (!$record || $record->isExpired()) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login')->withErrors('Akses auditor external Anda sudah berakhir.');
        }

        $name = optional($request->route())->getName();
        foreach ($this->allowed as $prefix) {
            if ($name !== null && strpos($name, $prefix) === 0) {
                return $next($request);
            }
        }

        if ($request->expectsJson()) {
            abort(403, 'Akses tidak diizinkan.');
        }

        return redirect()->route('auditor.audit-smp-score.index');
    }
}
