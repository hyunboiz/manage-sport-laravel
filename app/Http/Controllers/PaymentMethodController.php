<?php

namespace App\Http\Controllers;

use App\Models\PaymentMethod;
use App\Http\Requests\StorePaymentMethodRequest;
use App\Http\Requests\UpdatePaymentMethodRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
class PaymentMethodController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $payments = PaymentMethod::all();
        return view('admin.payment', compact('payments'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StorePaymentMethodRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StorePaymentMethodRequest $request)
    {
        $validated = $request->validated();
        $paymentMethod = PaymentMethod::createPaymentMethod($validated);

        return response()->json([
            'status' => true,
            'message' => 'Thêm phương thức thanh toán thành công!',
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdatePaymentMethodRequest  $request
     * @param  \App\Models\PaymentMethod  $paymentMethod
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatePaymentMethodRequest $request, PaymentMethod $paymentMethod)
    {
        $validated = $request->validated();
        $paymentMethod = PaymentMethod::findOrFail($validated['id']);
        $paymentMethod->updatePaymentMethod($validated);

    return response()->json([
        'status' => true,
        'message' => 'Cập nhật phương thức thanh toán thành công!',
    ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\PaymentMethod  $paymentMethod
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, PaymentMethod $paymentMethod)
    {
        $id = $request->input('id');
        $paymentMethod = PaymentMethod::find($id);

        if (!$paymentMethod) {
            return response()->json([
                'status' => false,
                'message' => 'Không tìm thấy phương thức thanh toán với ID: ' . $id,
            ], 404);
        }

        // Xoá file icon
        if ($paymentMethod->icon) {
            $oldPath = str_replace('/storage/', '', $paymentMethod->icon);
            if (Storage::disk('public')->exists($oldPath)) {
                Storage::disk('public')->delete($oldPath);
            }
        }

        $paymentMethod->delete();

        return response()->json([
            'status' => true,
            'message' => 'Xoá phương thức thanh toán thành công!',
        ]);
    }
}
