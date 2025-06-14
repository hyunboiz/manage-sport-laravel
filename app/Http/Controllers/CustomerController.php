<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Booking;
use App\Http\Requests\StoreCustomerRequest;
use App\Http\Requests\UpdateCustomerRequest;
use App\Http\Requests\UpdateCustomerByUserRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function viewLogin()
    {
        return view("user.login");
    }

     public function viewRegister()
    {
        return view("user.register");
    }

    public function viewProfile()
    {
        return view("user.profile");
    }

    public function viewChangePass()
    {
        return view("user.password");
    }

    public function history()
    {
        $customerId = auth()->guard('web')->id(); // hoặc auth()->id() nếu đang dùng guard mặc định

        $bookings = Booking::with([
                                    'paymentMethod',
                                    'bookingDetails.field.sport',
                                    'bookingDetails.field.type',
                                    'bookingDetails.timeframe'
                                ])
                                ->where('customer_id', $customerId)
                                ->orderByDesc('created_at')
                                ->get();

        return view('user.history', compact('bookings'));
    }
    public function updateByUser(UpdateCustomerByUserRequest $request, Customer $customer) //  update on User
    {   
        $user = Auth::guard('web')->user();
        $customer = Customer::findOrFail($request->id);    
        $customer->update($request->validated());

        return response()->json([
            'status' => true,
            'message' => 'Thay đổi thông tin thành công!',
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $customers = Customer::all();
        return view('admin.customer', compact('customers'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreCustomerRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreCustomerRequest $request)
    {
        $validated = $request->validated();
        $customer = Customer::create($validated);

        return response()->json([
            'status' => true,
            'message' => 'Thêm mới customer thành công',
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateCustomerRequest  $request
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateCustomerRequest $request, Customer $customer) //  update on Admin
    {
        $customer = Customer::findOrFail($request->input('id'));
        $customer->update($request->validated());

        return response()->json([
            'status' => true,
            'message' => 'Cập nhật customer thành công!',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Customer $customer)
    {
        $customer = Customer::find($request->input('id'));

        if (!$customer) {
            return response()->json([
                'status' => false,
                'message' => 'Không tìm thấy customer!',
            ]);
        }

        $customer->delete();

        return response()->json([
            'status' => true,
            'message' => 'Xóa customer thành công!',
        ]);
    }
}
