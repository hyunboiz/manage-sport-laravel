<?php 

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\AdminAuthService;
use App\Http\Requests\Auth\LoginAdminRequest;

use Illuminate\Http\Request;

class AdminAuthController extends Controller
{
    protected $authService;

    public function __construct(AdminAuthService $authService)
    {
        $this->authService = $authService;
    }

    public function login(LoginAdminRequest $request)
    {
        $result = $this->authService->login($request->only('email', 'password'));

        return response()->json([
            'status' => $result['status'],
            'message' => $result['status'] ? 'Đăng nhập admin thành công' : 'Sai tài khoản hoặc mật khẩu',
        ]);
    }

    public function logout()
    {
        $this->authService->logout();
        return response()->json(['status' => true, 'message' => 'Đã đăng xuất admin']);
    }
}
