<?php

namespace App\Booking\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BookingCreateRequest extends FormRequest
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
            'startDate' => 'required',
            'endDate' => 'nullable',
            'total' => 'required|numeric',
            'totalPaid' => 'nullable|numeric',
            'status' => 'nullable|string',
            'customerId' => 'nullable|integer',
            'facilitiesImport' => 'required|numeric',
            'peopleExtraImport' => 'required|numeric',
            'consumptionsImport' => 'required|numeric',
            'notes' => 'nullable',
            'scheduleId' => 'nullable|integer',
            'title' => 'required',
            'description' => 'required',
            'location' => 'required',
            'backgroundColor' => 'required',
            'borderColor' => 'required',
            'textColor' => 'required',
        ];
    }
}
