<?php

namespace App\Services;

use App\Models\Patient;

class PatientService
{
    /**
     * Get the next available file number
     * 
     * @return string
     */
    public function getLastFileNumber()
    {
        $patient = Patient::orderBy('file_number', 'desc')->first();
        
        if (!$patient) {
            return '1';
        }
        
        $fileNumber = $patient->file_number;
        
        // Check if the file number has a 'PAT' prefix
        if (str_starts_with($fileNumber, 'PAT')) {
            $number = (int) substr($fileNumber, 3);
            return 'PAT' . str_pad($number + 1, 4, '0', STR_PAD_LEFT);
        }
        
        // Handle numeric file numbers without prefix
        return (string)((int)$fileNumber + 1);
    }
}
