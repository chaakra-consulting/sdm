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
     */
    public function handle(Request $request, Closure $next, $roleSlug): Response
    {
        // Pastikan user sudah login
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Please login first.');
        }

        $role = Role::where('id', Auth::user()->role_id)->first();

        if (!$role || $role->slug !== $roleSlug) {
            return redirect()->back()->with('error', 'You do not have access to this page.');
        }

        return $next($request);
    }
}

