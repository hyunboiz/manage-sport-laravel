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
        $customerId = auth('web')->id(); // hoặc từ session
        $paymentId = $request->input('payment_id');

        if (empty($selections)) {
            return response()->json(['error' => 'Giỏ hàng trống'], 400);
        }

        // 1. Kiểm tra trùng
        foreach ($selections as $item) {
            $exists = BookingDetail::where('field_id', $item['fieldId'])
                ->where('time_id', $item['timeId'])
                ->whereDate('date_book', $item['date'])
                ->exists();

            if ($exists) {
                return response()->json([
                    'error' => "Sân số {$item['fieldId']} khung giờ {$item['timeId']} ngày {$item['date']} đã có người đặt"
                ], 200);
            }
        }

        // 2. Tạo Booking
        $booking = Booking::create([
            'customer_id' => $customerId,
            'payment_id' => $paymentId,
            'status'      => 'pending', // hoặc paid nếu xử lý ngay
            'total'       => collect($selections)->sum(fn($i) => $i['fieldPrice']),
        ]);

        // 3. Tạo Booking Details
        foreach ($selections as $item) {
            BookingDetail::create([
                'booking_id' => $booking->id,
                'field_id'   => $item['fieldId'],
                'time_id'    => $item['timeId'],
                'date_book'  => $item['date'],
                'price'      => $item['fieldPrice'],
            ]);
        }

        return response()->json([
            'message' => 'Đặt sân thành công',
            'booking_id' => $booking->id
        ]);
    }
    public function cart()
    {
        $payments = PaymentMethod::all();
        return view('user.cart', compact('payments'));
    }
}
