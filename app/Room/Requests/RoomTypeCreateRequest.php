<?php

namespace App\Room\Requests;

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
            'pricePerCapacity' => 'required',
            'pricePerAdditionalPerson' => 'required',
            'ageFree' => 'required|integer',
        ];
    }
}
