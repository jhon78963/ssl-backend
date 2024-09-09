<?php

namespace App\Review\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReviewCreateRequest extends FormRequest
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
            'customerName' => 'sometimes|string',
            'description' => 'sometimes|string',
            'rating' => 'sometimes|string',
        ];
    }
}
