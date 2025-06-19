<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Customer;
use App\Models\Booking;
use App\Models\BookingDetail;
use App\Models\Field;
use App\Models\TimeFrame;

class CustomerAndBookingSeeder extends Seeder
{
    public function run()
    {
        // Xoá dữ liệu cũ
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('booking_details')->truncate();
        DB::table('bookings')->truncate();
        DB::table('customers')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Tạo khách hàng
        Customer::factory()->count(20)->create();

        // Danh sách dữ liệu phụ
        $fields = Field::pluck('id')->toArray();
        $timeFrames = TimeFrame::pluck('id')->toArray();
        $statuses = ['confirmed', 'confirmed', 'pending', 'cancel'];

        Customer::all()->each(function ($customer) use ($fields, $timeFrames, $statuses) {
            $bookingCount = rand(3, 7);

            for ($i = 0; $i < $bookingCount; $i++) {
                $date = now()->subDays(rand(1, 30))->toDateString(); // trước hôm nay
                $status = collect($statuses)->random();

                $booking = Booking::create([
                    'customer_id' => $customer->id,
                    'admin_id' => in_array($status, ['confirmed', 'cancel']) ? 1 : null,
                    'status' => $status,
                    'total' => 0,
                    'payment_id' => rand(1, 2),
                    'created_at' => $date,
                    'updated_at' => $date,
                ]);

                $detailCount = rand(1, 3);
                $usedCombinations = [];

                for ($j = 0; $j < $detailCount; $j++) {
                    $tries = 0;
                    $maxTries = 10;

                    while ($tries < $maxTries) {
                        $timeId = collect($timeFrames)->random();
                        $fieldId = collect($fields)->random();

                        $key = "$fieldId-$timeId-$date";

                        if (in_array($key, $usedCombinations)) {
                            $tries++;
                            continue;
                        }

                        $exists = BookingDetail::where('field_id', $fieldId)
                            ->where('time_id', $timeId)
                            ->where('date_book', $date)
                            ->exists();

                        if (!$exists) {
                            $usedCombinations[] = $key;

                            $field = Field::find($fieldId);
                            $time = TimeFrame::find($timeId);

                            $price = $field->price + ($field->price * $time->ex_rate / 100);

                            BookingDetail::create([
                                'booking_id' => $booking->id,
                                'field_id' => $fieldId,
                                'time_id' => $timeId,
                                'date_book' => $date,
                                'price' => $price,
                                'created_at' => $date,
                                'updated_at' => $date,
                            ]);

                            $booking->increment('total', $price);
                            break;
                        }

                        $tries++;
                    }
                }
            }
        });
    }
}
