<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Customer;

class CustomerAuthService
{
    public function login(array $credentials): array
    {
        if (Auth::guard('web')->attempt($credentials)) {
            return [
                'status' => true,
                'user' => Auth::guard('web')->user(),
            ];
        }

        return ['status' => false];
    }

    public function register(array $data): array
    {
        $customer = Customer::create([
            'email' => $data['email'],
            'username' => $data['username'],
            'name' => $data['name'],
            'hotline' => $data['hotline'],
            'password' => Hash::make($data['password']),
        ]);

        return [
            'status' => true,
            'user' => $customer,
        ];
    }

    public function logout(): void
    {
        Auth::guard('web')->logout();
    }
}
