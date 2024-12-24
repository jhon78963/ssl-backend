<?php

namespace App\RoomType\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RoomTypeUpdateRequest extends FormRequest
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
            'description' => 'sometimes|string',
            'capacity' => 'sometimes|integer|min:1|max:6',
            'pricePerCapacity' => 'sometimes',
            'pricePerAdditionalPerson' => 'sometimes',
            'pricePerExtraHour' => 'sometimes',
            'ageFree' => 'sometimes|integer',
        ];
    }
}
