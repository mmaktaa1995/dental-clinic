<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @mixin IdeHelperPatientRecord
 */
class PatientRecord extends Model
{
    protected $fillable = ['patient_id', 'symptoms', 'diagnosis', 'record_date'];
    protected $casts = [
        'record_date' => 'timestamp',
    ];

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function affectedTeeth(): BelongsToMany
    {
        return $this->belongsToMany(Tooth::class, 'patient_record_teeth', 'patient_record_id', 'tooth_id')
            ->withPivot('description');
    }
}

