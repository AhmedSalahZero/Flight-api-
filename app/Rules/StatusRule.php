<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class StatusRule implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
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
        return $value=='ontime' || $value == 'delayed' ; 

    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'the :attribute field can be only ontime or delayed .';
        
    }
}
