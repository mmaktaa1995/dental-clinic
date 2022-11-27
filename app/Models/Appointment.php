<?php

namespace App\Models;

/**
 * App/Models/Patient
 *
 * @property int $id
 * @property string $patient_id
 * @property string $notes
 * @property \Carbon\Carbon $date
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\patient $patient
 */
class Appointment extends \Eloquent
{
    protected $fillable = ['patient_id', 'date', 'notes'];

    const WAITING_MINUTES = 30;
    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

}
