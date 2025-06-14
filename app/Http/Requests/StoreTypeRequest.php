<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreTypeRequest extends FormRequest
{
    public function authorize() { return true; }

    public function rules()
{
    return [
        'name' => 'required|string|max:255',
        'description' => 'nullable|string',
        'sport_id' => 'required|integer|exists:sports,id', // ✅ thêm dòng này
    ];
}

public function messages()
{
    return [
        'name.required' => 'Vui lòng nhập tên loại sân.',
        'sport_id.required' => 'Vui lòng chọn môn thể thao.',
        'sport_id.integer' => 'ID môn thể thao phải là số nguyên.',
        'sport_id.exists' => 'Môn thể thao không hợp lệ.',
    ];
}

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'status' => false,
            'message' => $validator->errors()->first(),
        ], 200));
    }
}
