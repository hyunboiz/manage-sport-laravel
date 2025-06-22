<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Http\Requests\StoreAdminRequest;
use App\Http\Requests\UpdateAdminRequest;
use Illuminate\Http\Request;
Use Validator;
use App\Models\Booking;
use App\Models\BookingDetail;
use App\Models\Customer;
use App\Models\Field;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
{
    $range = $request->query('range', 'all'); // today, 7days, 1month, all

    switch ($range) {
        case 'today':
            $startDate = Carbon::today();
            break;
        case '7days':
            $startDate = Carbon::now()->subDays(6);
            break;
        case '1month':
            $startDate = Carbon::now()->subDays(29);
            break;
        default:
            $startDate = null;
    }

    // Base queries
    $bookingQuery = Booking::query();
    $bookingDetailQuery = BookingDetail::query();

    if ($startDate) {
        $bookingQuery->whereDate('created_at', '>=', $startDate);
        $bookingDetailQuery->whereDate('date_book', '>=', $startDate);
    }

    // 1. Lượt đặt
    $totalBookings = $bookingQuery->count();

    // 2. Khách hàng có booking trong phạm vi
   $customerQuery = Customer::query();
    if ($startDate) {
        $customerQuery->whereDate('created_at', '>=', $startDate);
    }
    $totalCustomers = $customerQuery->count();

    // 3. Doanh thu trong phạm vi
    $totalRevenue = $bookingQuery->sum('total');

    // 4. Tỷ lệ huỷ
    $cancelBookings = (clone $bookingQuery)->where('status', 'cancel')->count();
    $cancelRate = $totalBookings ? round($cancelBookings / $totalBookings * 100, 2) : 0;

    // 5. Tổng giờ đã đặt
    $bookedHours = (clone $bookingDetailQuery)
        ->with('timeFrame')
        ->get()
        ->sum(fn($b) => max(0, $b->timeFrame->end - $b->timeFrame->start));

    // 6. Sân trống (theo ngày hôm nay)
    $totalFields = Field::count();
    $today = Carbon::today()->toDateString();
    $bookedFieldIdsToday = BookingDetail::whereDate('date_book', $today)->pluck('field_id')->unique();
    $emptyFieldsToday = $totalFields - $bookedFieldIdsToday->count();

    // === Biểu đồ ===

    // 1. Doanh thu theo ngày
    $revenuePerDay = (clone $bookingQuery)
        ->selectRaw('DATE(created_at) as date, SUM(total) as total')
        ->groupBy('date')
        ->orderBy('date')
        ->get()
        ->pluck('total', 'date');

    // 2. Đặt theo khung giờ
    $bookingsPerTime = (clone $bookingDetailQuery)
        ->select('time_id', DB::raw('COUNT(*) as total'))
        ->groupBy('time_id')
        ->with('timeFrame')
        ->get();

    // 3. Đặt theo loại sân
    $bookingsPerType = (clone $bookingDetailQuery)
        ->with('field.type')
        ->get()
        ->groupBy(fn($b) => optional($b->field->type)->name)
        ->map(fn($group) => $group->count());

    // 4. Tỷ lệ lấp đầy sân theo ngày
    $fillRate = (clone $bookingDetailQuery)
        ->selectRaw('date_book, COUNT(DISTINCT field_id) as used')
        ->groupBy('date_book')
        ->orderBy('date_book')
        ->get()
        ->mapWithKeys(fn($item) => [
            $item->date_book => round($item->used / max($totalFields, 1) * 100, 2)
        ]);

    return view('admin.dashboard', [
        'range' => $range,
        'totalBookings' => $totalBookings,
        'totalCustomers' => $totalCustomers,
        'totalRevenue' => $totalRevenue,
        'cancelRate' => $cancelRate,
        'emptyFieldsToday' => $emptyFieldsToday,
        'bookedHours' => $bookedHours,
        'revenuePerDay' => $revenuePerDay,
        'bookingsPerTime' => $bookingsPerTime,
        'bookingsPerType' => $bookingsPerType,
        'fillRate' => $fillRate,
    ]);
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
