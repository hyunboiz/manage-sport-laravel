<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Http\Requests\StoreAdminRequest;
use App\Http\Requests\UpdateAdminRequest;
use Illuminate\Http\Request;
Use Validator;
class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.dashboard');
    }

    public function viewLogin()
    {
        return view('admin.login');
    }

    public function createAdmin()
    {
        $admins = Admin::all();
        return view('admin.admin', compact('admins'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreAdminRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreAdminRequest $request)
    {
        
            // Lấy dữ liệu đã validate
            $validated = $request->validated();

            // Gọi method tạo admin trong Model
            $admin = Admin::createAdmin($validated);
            return response()->json([
            'status' => true,
            'message' => "Thêm mới admin thành công",
            ], 200);
        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateAdminRequest  $request
     * @param  \App\Models\Admin  $admin
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateAdminRequest $request, Admin $admin)
    {
        $id = $request->input('id');
        $admin = Admin::findOrFail($id);
        $validated = $request->validated();
        $admin->updateAdmin($validated);

        return response()->json([
            'status' => true,
            'message' => 'Cập nhật admin thành công!',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Admin  $admin
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Admin $admin)
    {
        $id = $request->input('id');

        $admin = Admin::find($id);
        if (!$admin) {
            return response()->json([
                'status' => false,
                'message' => 'Không tìm thấy admin với ID: ' . $id,
            ]);
        }

        $admin->delete();

        return response()->json([
            'status' => true,
            'message' => 'Xóa admin thành công!',
        ]);
    }
}
