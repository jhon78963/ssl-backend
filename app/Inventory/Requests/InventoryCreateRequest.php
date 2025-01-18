<?php

namespace App\Inventory\Requests;

use Illuminate\Foundation\Http\FormRequest;

class InventoryCreateRequest extends FormRequest
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
            'stock' => 'required|integer',
            'stockInUse' => 'nullable|integer'
        ];
    }
}
