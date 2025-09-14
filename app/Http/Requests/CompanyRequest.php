<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CompanyRequest extends FormRequest
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
            // Don't forget to pray before you start coding. Enjoy and happy coding.
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:companies,email',
            'logo' => 'nullable|image|dimensions:min_width=100,min_height=100|max:2048',
            'website' => 'nullable|url'
        ];
    }
}
