<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBookingRequest;
use App\Http\Requests\UpdateBookingRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use App\Models\{Sport, Field, TimeFrame, Booking, BookingDetail, Customer, PaymentMethod};
use Illuminate\Support\Facades\Validator;

class BookingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $bookings = Booking::with([
                                'customer',
                                'paymentMethod',
                                'admin',
                                'bookingDetails.field.sport',
                                'bookingDetails.field.type',
                            ])->get();
        return view('admin.booking', compact('bookings'));
    }
    /* ---------- View form ---------- */
    public function createView()
    {
        return view('admin.createbooking', [
            'sports'     => Sport::all(),
            'customers'  => Customer::orderBy('name')->get(),   // cho dropdown KH có sẵn
            'timeframes' => TimeFrame::all(),
        ]);
    }

    /* ---------- API: Danh sách sân theo môn ---------- */
    public function fieldsBySport(Request $request)
    {
        $sportId = $request->input('sport_id');
        if (!$sportId) return response()->json(['error' => 'Missing sport_id'], 400);

        $fields = Field::with('type:id,name')
                    ->where('sport_id', $sportId)
                    ->get(['id', 'type_id', 'price']);
        return response()->json(['fields' => $fields]);
    }

    /* ---------- API: Khung giờ còn trống ---------- */
    public function availableTimes(Request $request)
{
    $fieldId = $request->input('field_id');
    $date = $request->input('date');

    $booked = BookingDetail::where('field_id', $fieldId)
        ->whereDate('date_book', $date)
        ->pluck('time_id')
        ->toArray();

    $times = TimeFrame::all()->map(function ($t) use ($booked) {
        return [
            'id'      => $t->id,
            'start'   => $t->start,
            'end'     => $t->end,
            'ex_rate' => $t->ex_rate,
            'locked'  => in_array($t->id, $booked),
        ];
    });

    return response()->json(['times' => $times]);
}


    /* ---------- API: Lưu booking (chặn trùng) ---------- */

public function store(Request $request)
{
    /* ----- 1. Xác định tạo KH mới hay KH cũ ----- */
    $isNew = !$request->filled('customer_id');

    $rules = [
        'selections'                => 'required|array|min:1',
        'selections.*.field_id'     => 'required|exists:fields,id',
        'selections.*.time_id'      => 'required|exists:time_frames,id',
        'selections.*.date'         => 'required|date',
        'selections.*.price'        => 'required|numeric|min:0',
    ];

    if ($isNew) {
        $rules = array_merge($rules, [
            'name'     => 'required|string',
            'username' => 'required|string|unique:customers,username',
            'email'    => 'required|email|unique:customers,email',
            'hotline'  => 'required|string',
        ]);
    } else {
        $rules['customer_id'] = 'required|exists:customers,id';
    }

    $validator = Validator::make($request->all(), $rules);
    if ($validator->fails()) {
        return response()->json([
            'message' => 'Validation failed',
            'errors'  => $validator->errors(),
        ], 422);
    }

    /* ----- 2. Lấy hoặc tạo khách hàng ----- */
    $customer = $isNew
        ? Customer::create([
            'name'     => $request->name,
            'username' => $request->username,
            'email'    => $request->email,
            'hotline'  => $request->hotline,
            'password' => bcrypt('123456'),
        ])
        : Customer::find($request->customer_id);

    /* ----- 3. Kiểm tra trùng lịch cho từng selection ----- */
    foreach ($request->selections as $sel) {
        $dup = BookingDetail::where('field_id', $sel['field_id'])
            ->where('time_id',  $sel['time_id'])
            ->whereDate('date_book', $sel['date'])
            ->exists();

        if ($dup) {
            return response()->json([
                'status'  => false,
                'message' => "Sân {$sel['field_id']} đã được đặt khung giờ này ({$sel['date']})."
            ]);
        }
    }

    /* ----- 4. Tạo booking & booking-details ----- */
    $total = collect($request->selections)->sum('price');
    $payment = PaymentMethod::firstWhere('id', 1);   // thanh toán tại sân

    DB::transaction(function () use ($request, $customer, $payment, $total) {
        $booking = Booking::create([
            'customer_id' => $customer->id,
            'admin_id'    => auth('admin')->id(),
            'payment_id'  => $payment?->id,
            'status'      => 'pending',
            'total'       => $total,
        ]);

        foreach ($request->selections as $sel) {
            BookingDetail::create([
                'booking_id' => $booking->id,
                'field_id'   => $sel['field_id'],
                'time_id'    => $sel['time_id'],
                'date_book'  => $sel['date'],
                'price'      => $sel['price'],
            ]);
        }
    });

    return response()->json(['status' => true, 'message' => 'Đặt sân thành công']);
}


    public function updateStatus(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:bookings,id',
            'status' => 'required|in:pending,confirmed,cancel'
        ]);

        $booking = Booking::findOrFail($request->id);
        $booking->status = $request->status;
        $booking->save();

        return response()->json([
            'status' => true,
            'message' => 'Booking status updated successfully.',
            'data' => [
                'id' => $booking->id,
                'status' => $booking->status,
            ]
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Booking  $booking
     * @return \Illuminate\Http\Response
     */
    public function destroy(Booking $booking)
    {
        //
    }
}
