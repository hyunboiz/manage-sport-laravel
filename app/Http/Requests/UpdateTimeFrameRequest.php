<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
class UpdateTimeFrameRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
     public function rules()
    {
        $id = $this->input('id');
        return [
            'id' => 'required|exists:time_frames,id',
            'start' => 'required|date_format:H:i',
            'end' => 'required|date_format:H:i|after:start',
            'ex_rate' => 'required|numeric|min:0',
        ];
    }

    public function messages()
    {
        return [
            'id.required' => 'ID không được bỏ trống.',
            'id.exists' => 'Không tìm thấy khung giờ với ID này.',
            'start.required' => 'Vui lòng nhập thời gian bắt đầu.',
            'end.required' => 'Vui lòng nhập thời gian kết thúc.',
            'end.after' => 'Thời gian kết thúc phải sau thời gian bắt đầu.',
            'ex_rate.required' => 'Vui lòng nhập tỷ lệ quy đổi.',
            'ex_rate.numeric' => 'Tỷ lệ quy đổi phải là số.',
            'ex_rate.min' => 'Tỷ lệ quy đổi không âm.',
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
