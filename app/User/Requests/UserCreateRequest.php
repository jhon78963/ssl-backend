<?php

namespace App\User\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserCreateRequest extends FormRequest
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
            'username' => 'required|string|max:25',
            'email' => 'required|email|max:190',
            'name' => 'required|string|max:25',
            'surname' => 'required|string|max:25',
            'roleId' => 'required',
            'file' => 'nullable|max:2048',
        ];
    }
}
