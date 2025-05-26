<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @mixin IdeHelperAppointment
 */
class Appointment extends BaseModel
{
    use HasFactory;
    const WAITING_MINUTES = 30;
    protected $fillable = ['patient_id', 'date', 'notes', 'user_id'];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

}
