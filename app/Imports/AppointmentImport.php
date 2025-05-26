<?php

namespace App\Imports;

use App\Models\Appointment;
use App\Models\Patient;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

class AppointmentImport extends BaseImport
{
    /**
     * Process a single row
     *
     * @param Collection $row
     * @return void
     */
    protected function processRow(Collection $row)
    {
        // Find the patient by name or ID
        $patient = null;
        if ($row->has('patient_id') && $row['patient_id']) {
            $patient = Patient::find($row['patient_id']);
        } elseif ($row->has('patient_name') && $row['patient_name']) {
            $patient = Patient::where('name', $row['patient_name'])->first();
        }

        if (!$patient) {
            throw new \Exception('Patient not found');
        }

        $appointment = new Appointment([
            'patient_id' => $patient->id,
            'date' => Carbon::parse($row['date']),
            'notes' => $row['notes'] ?? null,
            'user_id' => Auth::id(),
        ]);

        $appointment->save();

        return $appointment;
    }

    /**
     * Get validation rules for the import
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'patient_id' => ['nullable', 'exists:patients,id'],
            'patient_name' => ['required_without:patient_id', 'string', 'max:255'],
            'date' => ['required', 'date'],
            'notes' => ['nullable', 'string'],
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
            'patient_id.exists' => 'The patient ID does not exist.',
            'patient_name.required_without' => 'Either patient ID or patient name is required.',
            'date.required' => 'The appointment date is required.',
            'date.date' => 'The appointment date must be a valid date.',
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
            'patient_id' => 'patient ID',
            'patient_name' => 'patient name',
            'date' => 'appointment date',
            'notes' => 'notes',
        ];
    }
}
