<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use App\Models\TimeFrame;

class StoreTimeFrameRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'start' => 'required|integer',
            'end' => 'required|integer|gt:start',
            'ex_rate' => 'required|numeric',
        ];
    }

    public function messages()
    {
        return [
            'start.required' => 'Vui lòng nhập thời gian bắt đầu.',
            'end.required' => 'Vui lòng nhập thời gian kết thúc.',
            'end.gt' => 'Thời gian kết thúc phải lớn hơn thời gian bắt đầu.',
            'ex_rate.required' => 'Vui lòng nhập tỷ lệ quy đổi.',
            'ex_rate.numeric' => 'Tỷ lệ quy đổi phải là số.',
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
            $start = (int) $this->input('start');
            $end = (int) $this->input('end');

            // Kiểm tra trùng lặp hoặc chồng lặp khung giờ
            $overlap = TimeFrame::where(function ($query) use ($start, $end) {
                $query->where(function ($q) use ($start, $end) {
                    $q->where('start', '<', $end)
                      ->where('end', '>', $start);
                });
            })->exists();

            if ($overlap) {
                $validator->errors()->add('start', 'Khung giờ bị trùng hoặc chồng lặp với khung giờ đã tồn tại.');
            }
        });
    }
}
