<?php

namespace App\Reservation\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReservationUpdateRequest extends FormRequest
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
            'reservationDate' => 'sometimes',
            'total' => 'sometimes|numeric',
            'totalPaid' => 'nullable|numeric',
            'status' => 'nullable|string',
            'customerId' => 'nullable|integer',
            'reservationTypeId' => 'sometimes|integer',
        ];
    }
}
