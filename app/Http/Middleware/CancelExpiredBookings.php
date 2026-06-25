<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Booking;
use Illuminate\Support\Facades\Schema;

class CancelExpiredBookings
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next)
    {
        try {
            Booking::cancelExpiredBookings();
            Booking::autoCancelPendingCancellations();
        } catch (\Illuminate\Database\QueryException $e) {
            // Ignore if tables don't exist yet (e.g., during migrations)
        }

        return $next($request);
    }
}
