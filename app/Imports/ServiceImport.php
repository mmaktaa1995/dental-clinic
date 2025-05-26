<?php

namespace App\Imports;

use App\Models\Service;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

class ServiceImport extends BaseImport
{
    /**
     * Process a single row
     *
     * @param Collection $row
     * @return void
     */
    protected function processRow(Collection $row)
    {
        $service = new Service([
            'name' => $row['name'],
            'price' => $row['price'],
            'user_id' => Auth::id(),
        ]);

        $service->save();

        return $service;
    }

    /**
     * Get validation rules for the import
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'price' => ['required', 'numeric', 'min:0'],
        ];
    }

    /**
     * Get custom validation messages
     *
     * @return array
     */
    public function customValidationMessages(): array
    {
        return [
            'name.required' => 'The service name is required.',
            'price.required' => 'The service price is required.',
            'price.numeric' => 'The price must be a number.',
            'price.min' => 'The price must be at least 0.',
        ];
    }

    /**
     * Get custom validation attributes
     *
     * @return array
     */
    public function customValidationAttributes(): array
    {
        return [
            'name' => 'service name',
            'price' => 'service price',
        ];
    }
}
