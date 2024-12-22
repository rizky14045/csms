<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        $vendor = Vendor::find(Auth::guard('vendor')->id());
        if(!empty($vendor)){
            return $next($request);
        } else {
            return redirect()->route('admin.login');
        }
    }
}
