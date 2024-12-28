<?php

namespace App\Reservation\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReservationCreateRequest extends FormRequest
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
            'initialReservationDate' => 'required',
            'finalReservationDate' => 'nullable',
            'total' => 'required|numeric',
            'totalPaid' => 'nullable|numeric',
            'status' => 'nullable|string',
            'customerId' => 'nullable|integer',
            'reservationTypeId' => 'required|integer',
            'facilitiesImport' => 'required|numeric',
            'consumptionsImport' => 'required|numeric',
            'peopleExtraImport' => 'required|numeric',
            'hoursExtraImport' => 'required|numeric',
            'brokenThingsImport' => 'nullable|numeric',
            'notes' => 'nullable',
            'scheduleId' => 'nullable|integer',
        ];
    }
}
