<?php

namespace App\Http\Middleware;

use App\Models\Role;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $roleSlug): Response
    {
        if(!Auth::check()){
        $role = Role::where('id',Auth::user()->role_id)->first();
            if ($role->slug != $roleSlug) {
                return redirect()->route('login')->with('error', 'You do not have access to this page.');
            }
        }

        return $next($request);
    }
}
