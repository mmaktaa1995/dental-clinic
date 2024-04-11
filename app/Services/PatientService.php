<?php

namespace App\Services;

use App\Models\Patient;

class PatientService
{
    /**
     * @return int
     */
    public function getLastFileNumber()
    {
        $patient = Patient::latest()->first();
        return $patient ? ($patient->file_number + 1) : 1;
    }
}
