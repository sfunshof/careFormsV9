<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Http; // Add this line

class ValidPostcode implements Rule
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
        $response = Http::get("https://api.postcodes.io/postcodes/{$value}/validate");

        return $response->successful() && $response->json('result');
    }

    public function message()
    {
        return  'Not a valid postcode'; // 'The :attribute is not a valid postcode.';
    }
}
