<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Support\Facades\Log;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    protected function redirectTo($request)
    {
        if (! $request->expectsJson()) {
            $first = $request->segment(1);
            if ($first === 'admin') {
                return '/admin/login';
            }

            return '/auth/login';
        }

        return null;
    }

    /**
     * Override the default unauthenticated behavior to redirect properly
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  array  $guards
     * @throws \Illuminate\Auth\AuthenticationException
     */

protected function unauthenticated($request, array $guards)
{
    Log::info('[DEBUG] Unauthenticated triggered', [
        'guards' => $guards,
        'segment_1' => $request->segment(1),
    ]);

    $guard = $guards[0] ?? null;

    if ($guard === 'admin' || $request->segment(1) === 'admin') {
        $login = '/admin/login';
    } else {
        $login = '/auth/login';
    }

    throw new AuthenticationException(
    'Unauthenticated.',
    $guards,
    $login // ← luôn truyền URL login, không kiểm tra JSON nữa
);
}
}
