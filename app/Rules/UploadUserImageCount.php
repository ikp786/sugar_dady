<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class UploadUserImageCount implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        // print_r($value);die;
        return (count($value) <= '8') ? true : false;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'You cannot upload more than 8 images';
        // return 'The validation error message.';
    }
}
