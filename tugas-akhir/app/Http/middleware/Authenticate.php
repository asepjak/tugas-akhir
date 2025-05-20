<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo(Request $request): ?string
    {
        if ($request->expectsJson()) {
            return null;
        }

        return route('login');
    }

    /**
     * Handle an unauthenticated user.
     */
    protected function unauthenticated($request, array $guards)
    {
        if ($request->expectsJson()) {
            abort(response()->json([
                'message' => 'Unauthenticated.',
                'error' => 'Silakan login terlebih dahulu untuk mengakses halaman ini.'
            ], 401));
        }

        return redirect()->guest(route('login'))->with('error', 'Silakan login terlebih dahulu.');
    }
}
