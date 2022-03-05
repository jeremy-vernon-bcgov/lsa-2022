<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreIdentificationRequest extends FormRequest
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
            'guid'                          => 'required|string',
            'idir'                          => 'required|string',
            'government_email'              => 'required|email',
            'employee_number'               => 'required|string|gte:4',
            'first_name'                    => 'required|string|gte:1',
            'last_name'                     => 'required|string|gte:1',
            'organization_id'               => 'required|numeric',
            'branch_name'                   => 'required|string|gte:3'
        ];
    }
}
