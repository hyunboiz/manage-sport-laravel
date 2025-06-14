<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Field;
use App\Models\Sport;
use App\Models\Type;
use Illuminate\Http\Request;
use App\Http\Requests\StoreFieldRequest;
use App\Http\Requests\UpdateFieldRequest;

class FieldController extends Controller
{
    public function index()
    {
        $fields = Field::with(['sport', 'type'])->get();
        $sports = Sport::all();
        $types = Type::all();

        return view('admin.field', compact('fields', 'sports', 'types'));
    }

    public function store(StoreFieldRequest $request)
    {
        $data = $request->validated();
        $field = Field::createField($data);

        return response()->json([
            'status' => true,
            'message' => 'Thêm mới sân thành công!',
        ]);
    }

    public function update(UpdateFieldRequest $request)
    {
        $id = $request->input('id');
        $field = Field::findOrFail($id);

        $field->updateField($request->validated());

        return response()->json([
            'status' => true,
            'message' => 'Cập nhật sân thành công!',
        ]);
    }

    public function destroy(Request $request)
    {
        $id = $request->input('id');
        $field = Field::find($id);

        if (!$field) {
            return response()->json([
                'status' => false,
                'message' => 'Không tìm thấy sân.',
            ]);
        }

        // Xoá ảnh nếu có
        if ($field->image) {
            $oldPath = str_replace('/storage/', '', $field->image);
            if (\Storage::disk('public')->exists($oldPath)) {
                \Storage::disk('public')->delete($oldPath);
            }
        }

        $field->delete();

        return response()->json([
            'status' => true,
            'message' => 'Xóa sân thành công!',
        ]);
    }
    public function getBySport(Request $request)
    {
        $request->validate([
            'sport_id' => 'required|integer|exists:sports,id'
        ]);

        return Type::where('sport_id', $request->sport_id)
                ->select('id', 'name')
                ->get();
    }
}
