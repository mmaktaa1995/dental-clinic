<?php

namespace App\Imports;

use App\Models\Patient;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class PatientImport extends BaseImport
{
    /**
     * Process a single row
     *
     * @param Collection $row
     * @return void
     */
    protected function processRow(Collection $row)
    {
        $patient = new Patient([
            'name' => $row['name'],
            'age' => $row['age'],
            'gender' => $row['gender'],
            'phone' => $row['phone'] ?? null,
            'mobile' => $row['mobile'] ?? null,
            'file_number' => $row['file_number'] ?? Str::random(8),
            'total_amount' => $row['total_amount'] ?? 0,
        ]);

        $patient->save();

        return $patient;
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
            'age' => ['required', 'numeric', 'min:0', 'max:120'],
            'gender' => ['required', 'in:male,female'],
            'phone' => ['nullable', 'string', 'max:20'],
            'mobile' => ['nullable', 'string', 'max:20'],
            'file_number' => ['nullable', 'string', 'max:50'],
            'total_amount' => ['nullable', 'numeric', 'min:0'],
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
            'name.required' => 'The patient name is required.',
            'age.required' => 'The patient age is required.',
            'gender.required' => 'The patient gender is required.',
            'gender.in' => 'The gender must be either male or female.',
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
            'name' => 'patient name',
            'age' => 'patient age',
            'gender' => 'patient gender',
            'phone' => 'phone number',
            'mobile' => 'mobile number',
            'file_number' => 'file number',
            'total_amount' => 'total amount',
        ];
    }
}
