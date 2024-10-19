<?php

namespace App\Customer\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CustomerUpdateRequest extends FormRequest
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
            'dni' => 'sometimes|string|size:8',
            'name' => 'sometimes|string',
            'surname' => 'sometimes|string',
            'cellphone' => 'sometimes|string|size:9',
            'email' => 'sometimes|email',
            'genderId' => 'sometimes|integer',
        ];
    }
}
