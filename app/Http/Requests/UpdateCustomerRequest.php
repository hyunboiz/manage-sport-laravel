<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateCustomerRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $id = $this->input('id');
        return [
            'id' => 'required|exists:customers,id',
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:customers,email,' . $id,
            'username' => 'required|string|max:255|unique:customers,username,' . $id,
            'hotline' => 'nullable|string|max:20',
        ];
    }

    public function messages()
    {
        return [
            'id.required' => 'Thiếu ID khách hàng.',
            'id.exists' => 'Không tìm thấy khách hàng.',
            'name.required' => 'Vui lòng nhập họ tên.',
            'email.required' => 'Vui lòng nhập email.',
            'email.email' => 'Email không hợp lệ.',
            'email.unique' => 'Email đã tồn tại.',
            'username.required' => 'Vui lòng nhập tên đăng nhập.',
            'username.unique' => 'Tên đăng nhập đã tồn tại.',
            'hotline.max' => 'Số điện thoại tối đa 20 ký tự.',
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
