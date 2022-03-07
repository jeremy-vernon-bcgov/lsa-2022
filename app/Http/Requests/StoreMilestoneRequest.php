<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreMilestoneRequest extends FormRequest
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
          'recipient_id' => 'required',
          'milestones' => 'required',
          'qualifying_year' => 'required',
          'retiring_this_year' => 'required|boolean|required_with:retirement_date',
          'retirement_date' => 'date|after:today'
        ];
    }
}
