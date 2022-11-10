<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MiscRequest extends FormRequest
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
            'misc_title' => 'required',
            'misc_type' =>  'required'
        ];
    }
    
    public function messages(){

        return [
            'misc_title.required'   => 'Misc Title should be required',
            'misc_type.required'    => 'Misc Type should be required',
        ];
    }

}
