<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateSportRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        $id = $this->input('id');  // Nhận id từ form POST
        return [
            'id' => 'required|exists:sports,id',
            'name' => 'required|string',
            'icon' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ];
    }
    public function messages()
    {
        return [
            'id.required' => 'ID không được bỏ trống.',
            'id.exists' => 'Không tìm thấy môn thể thao với ID này.',
            'name.required' => 'Vui lòng nhập tên môn thể thao.',
            'icon.image' => 'File icon phải là hình ảnh.',
            'icon.mimes' => 'File icon chỉ chấp nhận jpeg, png, jpg, gif, svg.',
            'icon.max' => 'File icon không được lớn hơn 2MB.',
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
