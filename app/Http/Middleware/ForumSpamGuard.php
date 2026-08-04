<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ForumSpamGuard
{
    /**
     * Block forum posts containing URLs from accounts less than 24 hours old.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user && $user->created_at->diffInHours(now()) < 24) {
            $body = $request->input('body', '');

            if (preg_match('/https?:\/\/|www\./i', $body)) {
                return back()
                    ->withInput()
                    ->withErrors([
                        'body' => 'New accounts cannot include links in forum posts for the first 24 hours. This helps us prevent spam.',
                    ]);
            }
        }

        return $next($request);
    }
}
