<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @mixin IdeHelperMedication
 */
class Medication extends BaseModel
{
    protected $fillable = ['name', 'description', 'manufacturer', 'price'];

    public function patients(): BelongsToMany
    {
        return $this->belongsToMany(Patient::class, 'patient_medications')
            ->withPivot('dosage', 'frequency', 'start_date', 'end_date')
            ->withTimestamps();
    }
}
