<?php

namespace App\Http\Controllers;

use App\Models\Sport;
use App\Http\Requests\StoreSportRequest;
use App\Http\Requests\UpdateSportRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $sports = Sport::all();
        return view('admin.sport', compact('sports'));
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreSportRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreSportRequest $request)
    {
        $validated = $request->validated();

        // Gọi hàm trong model
        $sport = Sport::createSport([
            'name' => $validated['name'],
            'icon' => $request->file('icon'),  // Truyền file
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Tạo môn thể thao thành công!',
            'data' => $sport,
        ]);
    }
   
    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateSportRequest  $request
     * @param  \App\Models\Sport  $sport
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateSportRequest $request, Sport $sport)
    {
        $validated = $request->validated();
        $sport = Sport::findOrFail($validated['id']);

        $sport->updateSport([
            'name' => $validated['name'],
            'icon' => $request->file('icon'),  // Nếu có gửi icon
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Cập nhật môn thể thao thành công!',
        ]);
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Sport  $sport
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Sport $sport)
    {
        $id = $request->input('id');
        $sport = Sport::find($id);

        if (!$sport) {
            return response()->json([
                'status' => false,
                'message' => 'Không tìm thấy môn thể thao với ID: ' . $id,
            ], 200);
        }

        // Xóa file icon nếu có
        if ($sport->icon) {
            $iconPath = str_replace('/storage/', '', $sport->icon);  // Chuyển về path lưu trữ trong storage
            if (Storage::disk('public')->exists($iconPath)) {
                Storage::disk('public')->delete($iconPath);
            }
        }

        $sport->delete();

        return response()->json([
            'status' => true,
            'message' => 'Xóa môn thể thao thành công!',
        ]);
    }
}
