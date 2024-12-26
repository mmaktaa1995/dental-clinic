<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @mixin IdeHelperPatientRecord
 */
class PatientRecord extends BaseModel
{
    protected $fillable = ['patient_id', 'symptoms', 'diagnosis', 'record_date'];
    protected $casts = [
        'record_date' => 'timestamp',
    ];

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }
}

