<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePersonalContactRequest extends FormRequest
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
     * @return array
     */
    public function rules()
    {
        return [
            'guid'                              => 'required',
            'personal_email'                    => 'required',
            'personal_phone_number'             => 'required',
            'personal_address_prefix'           => '',
            'personal_address_street_address'   => 'required',
            'personal_address_postal_code'      => 'required',
            'personal_address_community'        => 'required'
        ];
    }
}
