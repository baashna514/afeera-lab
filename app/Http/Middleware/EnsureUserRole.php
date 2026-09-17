<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserRole
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        if (! $request->user()) {
            return redirect()->route('login');
        }

        $user = $request->user();

        // Check if user is active
        if (! $user->is_active) {
            auth()->logout();

            return redirect()->route('login')->withErrors(['email' => 'Your account has been deactivated. Please contact support.']);
        }

        // Check if user's company is active (for non-owner users)
        if ($user->role !== 'owner' && $user->company && $user->company->status !== 'active') {
            auth()->logout();

            return redirect()->route('login')->withErrors(['email' => 'Your company account is inactive. Please contact the administrator.']);
        }

        // Check role permission
        if (! empty($roles) && ! in_array($user->role, $roles)) {
            if ($user->role === 'owner') {
                return redirect()->route('owner.dashboard');
            }
            if ($user->role === 'super_admin') {
                return redirect()->route('admin.dashboard');
            }
            abort(403, 'Unauthorized action.');
        }

        return $next($request);
    }
}
