<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use App\Models\TimeFrame;

class UpdateTimeFrameRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'id' => 'required|exists:time_frames,id',
            'start' => 'required|integer',
            'end' => 'required|integer|gt:start',
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
            'end.gt' => 'Thời gian kết thúc phải lớn hơn thời gian bắt đầu.',
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

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $id = (int) $this->input('id');
            $start = (int) $this->input('start');
            $end = (int) $this->input('end');

            $overlap = TimeFrame::where('id', '!=', $id)
                ->where(function ($query) use ($start, $end) {
                    $query->where('start', '<', $end)
                          ->where('end', '>', $start);
                })->exists();

            if ($overlap) {
                $validator->errors()->add('start', 'Khung giờ bị trùng hoặc chồng lặp với khung giờ khác.');
            }
        });
    }
}
