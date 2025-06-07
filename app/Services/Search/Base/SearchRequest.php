<?php

namespace App\Services\Search\Base;

use Illuminate\Foundation\Http\FormRequest;

abstract class SearchRequest extends FormRequest
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
            'from_date' => ['nullable', 'date'],
            'to_date' => ['nullable', 'date', 'after_or_equal:fromDate'],
            'date' => ['nullable', 'date'],
            'per_page' => ['required', 'numeric', 'min:10'],
            'page' => ['required', 'numeric', 'min:1'],
            'query' => ['nullable', 'string'],
        ];
    }
}
