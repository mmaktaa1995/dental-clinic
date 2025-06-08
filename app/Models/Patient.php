<?php

/**
 * Created by PhpStorm.
 * User: Eng.MohammEd
 * Date: 8/10/2017
 * Time: 12:09 PM
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @mixin IdeHelperPatient
 */
class Patient extends BaseModel
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = ['name', 'age', 'phone', 'mobile', 'file_number', 'image', 'gender', 'user_id', 'total_amount'];

    public static function boot()
    {
        parent::boot();

        static::deleting(function (Patient $patient) {
            \DB::transaction(function () use ($patient) {
                // Soft delete related records
                $patient->visits->each(function (Visit $visit) {
                    $visit->delete();
                });
                $patient->payments->each(function (Payment $payment) {
                    $payment->delete();
                });
                foreach ($patient->files()->get() as $file) {
                    $file->delete();
                }

                // Copy patient to deleted_patients table
                $deletedPatient = new \App\Models\DeletedPatient($patient->only([
                    'name',
                    'age',
                    'phone',
                    'mobile',
                    'file_number',
                    'image',
                    'gender',
                    'user_id',
                    'total_amount'
                ]));
                $deletedPatient->id = $patient->id;
                $deletedPatient->created_at = $patient->created_at;
                $deletedPatient->updated_at = $patient->updated_at;
                $deletedPatient->save();
            });
        });
    }

    public function files(): HasMany
    {
        return $this->hasMany(PatientFile::class);
    }

    public function visits(): HasMany
    {
        return $this->hasMany(Visit::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function lastVisit()
    {
        return $this->hasOne(Visit::class)->latest('date');
    }

    public function medications(): BelongsToMany
    {
        return $this->belongsToMany(Medication::class, 'patient_medications')
            ->withPivot('dosage', 'frequency', 'start_date', 'end_date')
            ->withTimestamps();
    }

    public function records(): HasMany
    {
        return $this->hasMany(PatientRecord::class);
    }

    public function symptoms(): HasMany
    {
        return $this->hasMany(PatientRecord::class)->whereNotNull('symptoms');
    }

    public function diagnosis(): HasMany
    {
        return $this->hasMany(PatientRecord::class)->whereNotNull('diagnosis');
    }

    public function affectedTeeth(): HasManyThrough
    {
        return $this->hasManyThrough(PatientRecordTooth::class, PatientRecord::class);
    }
}
