<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ImportRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return boolean
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'file' => ['required', 'file', 'mimes:csv,xlsx,xls', 'max:10240'], // Max 10MB
        ];
    }

    /**
     * Get custom validation messages.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'file.required' => 'Please select a file to import.',
            'file.file' => 'The uploaded file is invalid.',
            'file.mimes' => 'The file must be a CSV, XLSX, or XLS file.',
            'file.max' => 'The file size must not exceed 10MB.',
        ];
    }
}
