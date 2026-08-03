<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckSubscription
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (!$user) {
            return redirect()->route('login');
        }

        // Admins bypass subscription checks
        if ($user->isAdmin()) {
            return $next($request);
        }

        // Check if user is premium
        if (!$user->isPremium()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'error' => 'Premium subscription required.',
                    'redirect' => route('pricing'),
                ], 403);
            }

            return redirect()->route('pricing')
                ->with('error', 'This feature or exam requires an active Pro Subscription. Upgrade today!');
        }

        return $next($request);
    }
}
