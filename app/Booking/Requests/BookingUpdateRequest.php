<?php

namespace App\Booking\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BookingUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'startDate' => 'nullable',
            'endDate' => 'nullable',
            'total' => 'nullable|numeric',
            'totalPaid' => 'nullable|numeric',
            'status' => 'nullable|string',
            'customerId' => 'nullable|integer',
            'facilitiesImport' => 'nullable|numeric',
            'peopleExtraImport' => 'nullable|numeric',
            'consumptionsImport' => 'nullable|numeric',
            'notes' => 'nullable',
            'description' => 'nullable',
        ];
    }
}
