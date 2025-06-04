<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @mixin IdeHelperPatientRecord
 */
class PatientRecord extends Model
{
    use HasFactory;
    protected $fillable = ['patient_id', 'symptoms', 'diagnosis', 'record_date'];
    protected $casts = [
        'record_date' => 'datetime',
    ];

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function affectedTeeth(): BelongsToMany
    {
        return $this->belongsToMany(Tooth::class, 'patient_record_teeth', 'patient_record_id', 'tooth_id')
            ->withPivot(['description', 'is_treated']);
    }
}

