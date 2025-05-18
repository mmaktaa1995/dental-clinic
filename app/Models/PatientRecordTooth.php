<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @mixin IdeHelperPatientRecordTooth
 */
class PatientRecordTooth extends Model
{
    protected $fillable = ['patient_record_id', 'is_treated', 'tooth_id', 'description'];
    public $timestamps = false;

}

