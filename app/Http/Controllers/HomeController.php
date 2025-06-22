<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sport;
use App\Models\Field;
use App\Models\TimeFrame;
use App\Models\Booking;
use App\Models\PaymentMethod;
use App\Models\BookingDetail;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
class HomeController extends Controller
{
     public function index()
    {
        $sports = Sport::all();
        return view('user.index', compact('sports'));
    }

     public function fieldList($id)
    {
        return view('user.field', ['sportId' => $id]);
    }

    public function fieldListAjax(Request $request)
    {
        $sportId = $request->input('sport_id');
        $date = $request->input('date', now()->toDateString());

        if (!$sportId) {
            return response()->json(['error' => 'Missing sport_id'], 400);
        }

        $fields = Field::with(['sport:id,name', 'type:id,name'])
    ->where('sport_id', $sportId)
    ->get();
        $timeframes = TimeFrame::all();

        $lockedMap = BookingDetail::whereDate('date_book', $date)
            ->whereIn('field_id', $fields->pluck('id'))
            ->get(['field_id', 'time_id'])          // lấy cả 2 cột
            ->mapWithKeys(fn ($row) => [
                $row->field_id . '_' . $row->time_id => true
            ]);

        $now = Carbon::now('Asia/Ho_Chi_Minh');

        return response()->json([
            'fields'       => $fields,
            'timeframes'   => $timeframes,
            'date'         => $date,
            'locked'       => $lockedMap,
            'today'        => $now->toDateString(),
            'currentHour'  => $now->hour,
        ]);
    }

public function checkout(Request $request)
{
    $selections = $request->input('selections', []);
    $paymentId  = $request->input('payment_id');
    $customerId = auth('web')->id();

    if (empty($selections)) {
        return response()->json(['error' => 'Giỏ hàng trống'], 400);
    }

    // --- 1. Kiểm tra trùng ---
    foreach ($selections as $item) {
        $exists = BookingDetail::where('field_id', $item['fieldId'])
            ->where('time_id', $item['timeId'])
            ->whereDate('date_book', $item['date'])
            ->exists();
        if ($exists) {
            return response()->json([
                'error' => "Sân {$item['fieldId']} khung {$item['timeId']} ngày {$item['date']} đã có người đặt"
            ], 200);
        }
    }

    // --- 2. Tạo Booking ---
    $total = collect($selections)->sum(fn($i) => $i['fieldPrice']);
    $booking = Booking::create([
        'customer_id' => $customerId,
        'payment_id'  => $paymentId,
        'status'      => 'pending',
        'total'       => $total,
    ]);

    // --- 3. Chi tiết đặt sân ---
    foreach ($selections as $item) {
        BookingDetail::create([
            'booking_id' => $booking->id,
            'field_id'   => $item['fieldId'],
            'time_id'    => $item['timeId'],
            'date_book'  => $item['date'],
            'price'      => $item['fieldPrice'],
        ]);
    }

    // --- 4. Nếu chọn thanh toán qua VNPay ---
     if ($paymentId === '2') {
        $vnp_Url        = config('services.vnpay.url');
        $vnp_Returnurl  = config('services.vnpay.return');
        $vnp_TmnCode    = config('services.vnpay.tmn');
        $vnp_HashSecret = config('services.vnpay.secret');

        $txnRef = Str::uuid()->toString();

        $vnp_Params = [
            'vnp_Version'   => '2.1.0',
            'vnp_TmnCode'   => $vnp_TmnCode,
            'vnp_Amount'    => $total * 100,                  // nhân 100
            'vnp_Command'   => 'pay',
            'vnp_CreateDate'=> now()->format('YmdHis'),
            'vnp_CurrCode'  => 'VND',
            'vnp_IpAddr'    => $request->ip(),
            'vnp_Locale'    => 'vn',
            'vnp_OrderInfo' => "Thanh toan don hang {$booking->id}",
            'vnp_OrderType' => 'other',
            'vnp_ReturnUrl' => $vnp_Returnurl,
            'vnp_TxnRef'    => $txnRef,
        ];

        ksort($vnp_Params);

        // GHÉP HashData: urlencode cả key & value
        $hashData = collect($vnp_Params)
            ->map(fn($v,$k)=>urlencode($k).'='.urlencode($v))
            ->implode('&');

        $vnp_Params['vnp_SecureHash'] = hash_hmac('sha512', $hashData, $vnp_HashSecret);

        // lưu ref
        $booking->update(['payment_txnref' => $txnRef]);

        return response()->json([
            'redirect' => $vnp_Url . '?' . http_build_query($vnp_Params)
        ]);
    }

    // --- 7. Nếu là COD hoặc phương thức khác ---
    return response()->json([
        'message'    => 'Đặt sân thành công',
        'booking_id' => $booking->id
    ]);
}

    public function cart()
    {
        $payments = PaymentMethod::all();
        return view('user.cart', compact('payments'));
    }
}
