<?php 

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\CustomerAuthService;
use App\Http\Requests\Auth\LoginCustomerRequest;
use App\Http\Requests\Auth\RegisterCustomerRequest;


use Illuminate\Http\Request;

class CustomerAuthController extends Controller
{
    protected $authService;

    public function __construct(CustomerAuthService $authService)
    {
        $this->authService = $authService;
    }

    public function login(LoginCustomerRequest $request)
    {
        $result = $this->authService->login($request->only('email', 'password'));

        return response()->json([
            'status' => $result['status'],
            'message' => $result['status'] ? 'Đăng nhập khách hàng thành công' : 'Sai tài khoản hoặc mật khẩu',
            'user' => $result['user'] ?? null,
        ]);
    }

    public function register(RegisterCustomerRequest $request)
    {
        $result = $this->authService->register($request->only('username','name', 'email', 'password','hotline'));
        return response()->json([
            'status' => $result['status'],
            'message' => 'Đăng ký khách hàng thành công',
            'user' => $result['user'],
        ]);
    }

    public function logout()
    {
        $this->authService->logout();
        return response()->json(['status' => true, 'message' => 'Đã đăng xuất khách hàng']);
    }
}
