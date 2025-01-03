<?php

namespace App\Book\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BookUpdateRequest extends FormRequest
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
            'startDate' => 'sometimes',
            'endDate' => 'nullable',
            'total' => 'sometimes|numeric',
            'totalPaid' => 'nullable|numeric',
            'status' => 'nullable|string',
            'customerId' => 'nullable|integer',
            'facilitiesImport' => 'sometimes|numeric',
            'peopleExtraImport' => 'sometimes|numeric',
            'notes' => 'nullable',
            'scheduleId' => 'nullable|integer',
            'title' => 'nullable',
            'description' => 'nullable',
            'location' => 'nullable',
            'backgroundColor' => 'nullable',
            'borderColor' => 'nullable',
            'textColor' => 'nullable',
        ];
    }
}
