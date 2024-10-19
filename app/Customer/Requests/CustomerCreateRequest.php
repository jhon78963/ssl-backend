<?php

namespace App\Customer\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CustomerCreateRequest extends FormRequest
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
            'dni' => 'required|string|size:8',
            'name' => 'required|string',
            'surname' => 'required|string',
            'cellphone' => 'required|string|size:9',
            'email' => 'required|email',
            'genderId' => 'required|integer',
        ];
    }
}
