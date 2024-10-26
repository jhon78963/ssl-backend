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
            'reservationDate' => 'required',
            'total' => 'required|numeric',
            'status' => 'nullable|string',
            'customerId' => 'nullable|integer',
            'roomId' => 'nullable|integer',
        ];
    }
}
