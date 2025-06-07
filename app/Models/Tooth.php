<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @mixin IdeHelperTooth
 */
class Tooth extends BaseModel
{
    use HasFactory;

    public $timestamps = false;
    protected $fillable = ['name', 'image', 'number', 'extra'];
    protected $casts = [
        'extra' => 'array'
    ];

    public function patientRecords(): BelongsToMany
    {
        return $this->belongsToMany(PatientRecord::class, 'patient_record_teeth')
            ->withPivot('description');
    }
}
