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
                $startDate = null; // full
        }

        $bookingQuery = Booking::query();
        $bookingDetailQuery = BookingDetail::query();
        if ($startDate) {
            $bookingQuery->whereDate('created_at', '>=', $startDate);
            $bookingDetailQuery->whereDate('date_book', '>=', $startDate);
        }

        $totalBookings = $bookingQuery->count();
        $cancelBookings = (clone $bookingQuery)->where('status', 'cancel')->count();
        $todayRevenue = $bookingQuery->sum('total');
        $totalFields = Field::count();
        $bookedFieldIds = (clone $bookingDetailQuery)->pluck('field_id')->unique();
        $emptyFieldsToday = $totalFields - $bookedFieldIds->count();
        $bookedHours = (clone $bookingDetailQuery)
        ->with('timeFrame')
        ->get()
        ->sum(fn($b) => max(0, $b->timeFrame->end - $b->timeFrame->start));
        // 1. Doanh thu theo ngày
        $revenuePerDay = (clone $bookingQuery)
            ->selectRaw('DATE(created_at) as date, SUM(total) as total')
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->pluck('total', 'date');

        // 2. Tỷ lệ trạng thái
        $statusCounts = (clone $bookingQuery)
            ->select('status', DB::raw('COUNT(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status');

        // 3. Đặt theo khung giờ
        $bookingsPerTime = (clone $bookingDetailQuery)
            ->select('time_id', DB::raw('COUNT(*) as total'))
            ->groupBy('time_id')
            ->with('timeFrame')
            ->get();

        // 4. Đặt theo loại sân
        $bookingsPerType = (clone $bookingDetailQuery)
            ->with('field.type')
            ->get()
            ->groupBy(fn($b) => optional($b->field->type)->name)
            ->map(fn($g) => $g->count());

        // 5. Tỷ lệ lấp đầy sân
        $fillRate = (clone $bookingDetailQuery)
            ->selectRaw('date_book, COUNT(DISTINCT field_id) as used')
            ->groupBy('date_book')
            ->orderBy('date_book')
            ->get()
            ->mapWithKeys(fn($i) => [$i->date_book => round($i->used / max($totalFields, 1) * 100, 2)]);

        return view('admin.dashboard', [
            'range'            => $range,
            'totalBookings'    => $totalBookings,
            'totalCustomers'   => Customer::count(),
            'totalRevenue'     => Booking::sum('total'),
            'cancelRate'       => $totalBookings ? round($cancelBookings / $totalBookings * 100, 2) : 0,
            'todayBookings'    => $totalBookings,
            'todayRevenue'     => $todayRevenue,
            'emptyFieldsToday' => $emptyFieldsToday,
            'revenuePerDay'    => $revenuePerDay,
            'statusCounts'     => $statusCounts,
            'bookingsPerTime'  => $bookingsPerTime,
            'bookingsPerType'  => $bookingsPerType,
            'fillRate'         => $fillRate,
            'bookedHours' => $bookedHours,
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
