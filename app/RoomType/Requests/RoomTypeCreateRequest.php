<?php

namespace App\RoomType\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RoomTypeCreateRequest extends FormRequest
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
            'description' => 'required|string',
            'capacity' => 'required|integer|min:1|max:6',
            'rentalHours' => 'required|integer|min:1|max:24',
            'pricePerCapacity' => 'required',
            'pricePerAdditionalPerson' => 'required',
            'pricePerExtraHour' => 'required',
            'ageFree' => 'required|integer',
        ];
    }
}
