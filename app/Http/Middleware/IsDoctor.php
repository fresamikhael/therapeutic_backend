<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IsDoctor
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check()) {
            if ($request->user()->roles('THERAPIST')) {
                return $next($request);
            } else {
                return response()->json([
                    'message' => 'Access  Denied! as you are not Therapist.',
                    'status' => 403
                ], 403);
            }
        } else {
            return response()->json([
                'message' => 'Please login first',
                'status' => 401
            ]);
        }
    }
}
