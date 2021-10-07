<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IsAdmin
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
        // if(Auth::user() && Auth::user()->roles == 'ADMIN')
        // {
        //     return $next($request);
        // } else {
        //     return response()->json([
        //         'message' => 'Access  Denied! as you are not Admin.',
        //         'status' => 403
        //     ], 403);
        // }

        if (Auth::check()) {
            if ($request->user()->where('roles', 'ADMIN')) {
                return $next($request);
            } else {
                return response()->json([
                    'message' => 'Access  Denied! as you are not Admin.',
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
