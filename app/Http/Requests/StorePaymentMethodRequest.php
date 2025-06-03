<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class StorePaymentMethodRequest extends FormRequest
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
        return [
            'name' => 'required|string',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'status' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Vui lòng nhập tên phương thức thanh toán.',
            'image.required' => 'Vui lòng chọn ảnh biểu tượng.',
            'image.image' => 'File icon phải là hình ảnh.',
            'image.mimes' => 'File icon chỉ chấp nhận: jpeg, png, jpg, gif, svg.',
            'image.max' => 'File icon không được lớn hơn 2MB.',
            'status.required' => 'Vui lòng chọn trạng thái.'
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
