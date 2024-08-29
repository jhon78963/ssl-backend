<?php

namespace App\Company\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CompanyUpdateRequest extends FormRequest
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
            'businessName' => 'sometimes|string',
            'representativeLegal' => 'sometimes|string',
            'address' => 'sometimes|string',
            'phoneNumber' => 'sometimes|string',
            'email' => 'sometimes|email',
            'googleMapsLocation' => 'sometimes',
        ];
    }
}
