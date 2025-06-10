<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreFieldRequest extends FormRequest
{
    public function authorize()
    {
        return true; // Có thể kiểm tra quyền sau nếu cần
    }

    public function rules()
    {
        return [
            'price' => 'required',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'sport_id' => 'required|exists:sports,id',
            'type_id' => 'required|exists:types,id',
        ];
    }

    public function messages()
    {
        return [
            'price.required' => 'Vui lòng nhập giá tiền.',
            'image.required' => 'Vui lòng chọn ảnh biểu tượng.',
            'image.image' => 'File icon phải là hình ảnh.',
            'image.mimes' => 'File icon chỉ chấp nhận: jpeg, png, jpg, gif, svg.',
            'image.max' => 'File icon không được lớn hơn 2MB.',
            'sport_id.required' => 'Vui lòng chọn môn thể thao.',
            'sport_id.exists' => 'Môn thể thao không hợp lệ.',
            'type_id.required' => 'Vui lòng chọn loại sân.',
            'type_id.exists' => 'Loại sân không hợp lệ.',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            response()->json([
                'status' => false,
                'message' => $validator->errors()->first(),
            ], 200)
        );
    }
}
