<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreServicePinsRequest extends FormRequest
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
            'recipient_id'                      => 'required',
            'supervisor_full_name'              => 'required',
            'supervisor_email'                  => 'required',
            'supervisor_address_prefix'         => '',
            'supervisor_address_street_address' => '',
            'supervisor_address_postal_code'    => '',
            'supervisor_address_community'      => ''
        ];
    }
}
