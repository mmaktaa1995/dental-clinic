<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @mixin IdeHelperMedication
 */
class Medication extends BaseModel
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'manufacturer', 'price', 'user_id'];

    public function patients(): BelongsToMany
    {
        return $this->belongsToMany(Patient::class, 'patient_medications')
            ->withPivot('dosage', 'frequency', 'start_date', 'end_date')
            ->withTimestamps();
    }
}
