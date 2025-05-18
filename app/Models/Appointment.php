<?php

namespace App\Models;

/**
 * @mixin IdeHelperAppointment
 */
class Appointment extends BaseModel
{
    const WAITING_MINUTES = 30;
    protected $fillable = ['patient_id', 'date', 'notes', 'user_id'];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

}
