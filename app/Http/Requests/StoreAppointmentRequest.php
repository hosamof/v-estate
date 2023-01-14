<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAppointmentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *      
     * @return bool
     */
    public function authorize()
    {
        /** @todo : check roles for agents */
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'title' => 'required|max:255',
            'address' => 'required|max:255',
            'date' => 'required',
            'customer_name' => 'required|max:255',
            'customer_phone' => 'required|max:255',
        ];
    }
}
