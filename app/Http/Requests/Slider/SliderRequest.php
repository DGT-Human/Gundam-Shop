<?php

namespace App\Http\Requests\Slider;

use Illuminate\Foundation\Http\FormRequest;
class SliderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }
    public function rules(): array
    {
        return [
            'name' => 'required',
            'thumb' => 'required',
            'url' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Tên không được để trống',
            'thumb.required' => 'Ảnh không được để trống',
            'url.required' => 'Url không được để trống'
        ];
    }
}