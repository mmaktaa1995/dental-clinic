<?php

namespace App\Models;

/**
 * @mixin IdeHelperPatientFile
 */
class PatientFile extends \Eloquent
{
    protected $fillable = ['file', 'patient_id', 'type', 'file_name'];

    protected static function boot()
    {
        parent::boot();
        static::deleting(function (PatientFile $file) {
            $filePath = \Str::replace('/storage/', '', $file->file);
            \Storage::disk('public')->delete($filePath);
        });
    }

}
