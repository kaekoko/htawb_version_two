<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class Section1dRequest extends FormRequest
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
     * @return array
     */
    public function rules()
    {
        return [
            'time_section'=>'required',
            'open_time'=>'required',
            'close_time'=>'required',
        ];
    }
}
