<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @mixin IdeHelperPatientFile
 */
class PatientFile extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = ['file', 'patient_id', 'user_id', 'type', 'file_name'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    protected static function boot()
    {
        parent::boot();
        static::deleting(function (PatientFile $file) {
            $filePath = \Str::replace('/storage/', '', $file->file);
            \Storage::disk('public')->delete($filePath);
        });
    }
}
