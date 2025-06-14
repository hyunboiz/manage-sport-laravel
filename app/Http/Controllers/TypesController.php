<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Type;
use App\Models\Sport;
use Illuminate\Http\Request;
use App\Http\Requests\StoreTypeRequest;
use App\Http\Requests\UpdateTypeRequest;

class TypesController extends Controller
{
    public function index()
    {
        $sports = Sport::all();
        $types = Type::with(['sport'])->get();
        return view('admin.type', compact('types', 'sports'));
    }

    public function store(StoreTypeRequest $request)
    {
        Type::create($request->validated());

        return response()->json([
            'status' => true,
            'message' => 'Thêm loại sân thành công',
        ]);
    }

    public function update(UpdateTypeRequest $request)
    {
        $type = Type::findOrFail($request->id);
        $type->update($request->validated());

        return response()->json([
            'status' => true,
            'message' => 'Cập nhật loại sân thành công',
        ]);
    }

    public function destroy(Request $request)
    {
        $type = Type::find($request->id);
        if (!$type) {
            return response()->json([
                'status' => false,
                'message' => 'Không tìm thấy loại sân',
            ]);
        }

        $type->delete();

        return response()->json([
            'status' => true,
            'message' => 'Xoá loại sân thành công',
        ]);
    }
}

