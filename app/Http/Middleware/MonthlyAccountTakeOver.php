<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Http\Helper\BlockMonthly;

class MonthlyAccountTakeOver
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
        
        $monthlyId = $request->route('monthlyId');
        $redirect = BlockMonthly::accountTakeOver($monthlyId);

        if ($redirect) {
            return $redirect; // Kembalikan redirect response
        }

        return $next($request); // Lanjutkan ke controller

    }
}
