<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\UploadedFile;
use Illuminate\Validation\Rule;

class FileUploadRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Authorization is handled by middleware
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'files' => ['required', 'array', 'min:1', 'max:10'],
            'files.*' => [
                'required',
                'file',
                'mimes:jpeg,png,jpg,gif,svg,pdf,doc,docx,xls,xlsx,ppt,pptx,txt',
                'max:' . (10 * 1024), // 10MB
            ],
            'folder' => ['required', 'string', 'max:255', 'regex:/^[a-z0-9_-]+$/i'],
            'type' => ['required', 'string', 'in:images,documents,other'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'files.required' => 'Please select at least one file to upload.',
            'files.array' => 'Invalid file upload format.',
            'files.min' => 'Please select at least one file to upload.',
            'files.max' => 'You cannot upload more than 10 files at once.',
            'files.*.required' => 'A file is required.',
            'files.*.file' => 'The uploaded file is invalid.',
            'files.*.mimes' => 'The file must be a file of type: :values.',
            'files.*.max' => 'The file must not be larger than :max kilobytes.',
            'folder.required' => 'A folder name is required.',
            'folder.regex' => 'The folder name may only contain letters, numbers, underscores, and hyphens.',
            'type.required' => 'A file type is required.',
            'type.in' => 'The selected file type is invalid.',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // Ensure files is always an array
        if ($this->hasFile('files') && !is_array($this->file('files'))) {
            $this->merge([
                'files' => [$this->file('files')]
            ]);
        }
    }
}
