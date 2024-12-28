<?php

namespace App\Cash\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CashCreateRequest extends FormRequest
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
            'scheduleId' => 'nullable|integer',
            'description' => 'required',
            'pettyCashAmount' => 'required',
            'name' => 'required',
            'status' => 'required',
        ];
    }
}
