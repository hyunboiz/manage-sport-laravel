<?php

namespace App\Http\Controllers;

use App\Models\TimeFrame;
use App\Http\Requests\StoreTimeFrameRequest;
use App\Http\Requests\UpdateTimeFrameRequest;
use Illuminate\Http\Request;

class TimeFrameController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $TimeFrame = TimeFrame::all();
        return view('admin.timeframe', compact('timeframes'));
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreTimeFrameRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreTimeFrameRequest $request)
    {
        $timeFrame = TimeFrame::create($request->validated());

        return response()->json([
            'status' => true,
            'message' => 'Thêm khung giờ thành công!',
            'data' => $timeFrame,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateTimeFrameRequest  $request
     * @param  \App\Models\TimeFrame  $timeFrame
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateTimeFrameRequest $request)
    {
        $timeFrame = TimeFrame::findOrFail($request->input('id'));
        $timeFrame->update($request->validated());

        return response()->json([
            'status' => true,
            'message' => 'Cập nhật khung giờ thành công!',
            'data' => $timeFrame,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\TimeFrame  $timeFrame
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $timeFrame = TimeFrame::find($request->input('id'));

        if (!$timeFrame) {
            return response()->json([
                'status' => false,
                'message' => 'Không tìm thấy khung giờ!',
            ], 404);
        }

        $timeFrame->delete();

        return response()->json([
            'status' => true,
            'message' => 'Xoá khung giờ thành công!',
        ]);
    }
}
