<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdatePaymentMethodRequest extends FormRequest
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

    public function rules()
    {
        $id = $this->input('id');
        return [
            'id' => 'required|exists:payment_methods,id',
            'name' => 'required|string',
            'image' => 'nullable|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'status' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'id.required' => 'ID không được bỏ trống.',
            'id.exists' => 'Không tìm thấy phương thức thanh toán với ID này.',
            'name.required' => 'Vui lòng nhập tên phương thức thanh toán.',
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
