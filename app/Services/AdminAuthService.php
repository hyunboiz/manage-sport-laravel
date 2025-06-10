<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;

class AdminAuthService
{
    public function login(array $credentials): array
    {
        if (Auth::guard('admin')->attempt($credentials)) {
            return [
                'status' => true,
                'user' => Auth::guard('admin')->user(),
            ];
        }

        return ['status' => false];
    }

    public function logout(): void
    {
        Auth::guard('admin')->logout();
    }
}
