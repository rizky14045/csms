<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use RealRashid\SweetAlert\Facades\Alert;

class VendorMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $encryptedUnit = $request->query('unit');
        if (!$encryptedUnit) {
            Alert::error('Error', 'Unit wajib dipilih');
            return redirect()->back();
        }


        try {
            $unitId = Crypt::decryptString($encryptedUnit);

            $request->merge([
                'unit_id' => $unitId
            ]);

        } catch (\Exception $e) {
            Alert::error('Error', 'Unit tidak valid');
            return redirect()->back();
        }

        return $next($request);
    }
}
