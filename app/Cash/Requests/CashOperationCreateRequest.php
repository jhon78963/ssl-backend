<?php

namespace App\Cash\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CashOperationCreateRequest extends FormRequest
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
            'cashId' => 'nullable|integer',
            'cashTypeId' => 'required|integer',
            'scheduleId' => 'nullable|integer',
            'date' => 'required',
            'amount' => 'required',
        ];
    }
}
