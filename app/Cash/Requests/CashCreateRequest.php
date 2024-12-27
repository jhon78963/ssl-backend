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
            'cashId' => 'required|integer',
            'cashTypeId' => 'required|integer',
            'scheduleId' => 'required|integer',
            'date' => 'required',
            'pettyCashAmount' => 'required',
            'initialAmount' => 'required',
            'name' => 'required',
        ];
    }
}
