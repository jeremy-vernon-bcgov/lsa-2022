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
            'guid'                          => 'required',
            'idir'                          => 'required',
            'government_email'              => 'required',
            'employee_number'               => 'required',
            'full_name'                     => 'required',
            'organization_id'               => 'required',
            'branch_name'                   => 'required'
        ];
    }
}
