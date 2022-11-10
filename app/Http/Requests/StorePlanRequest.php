<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePlanRequest extends FormRequest
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
            'plan_title'        =>  'required',
            'plan_price'       =>  'required|numeric|between:0,99999.99',            
            'plan_duration'     =>  'required'
        ];
    }
    
    public function messages(){

        return [
            'plan_title.required'       => 'Plan Title should be required',
            'plan_price.required'       => 'Plan Price should be required',
            'plan_duration.requred'     => 'Plan Duration'
        ];
    }

}
