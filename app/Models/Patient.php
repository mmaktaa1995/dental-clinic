<?php
/**
 * Created by PhpStorm.
 * User: Eng.MohammEd
 * Date: 8/10/2017
 * Time: 12:09 PM
 */

namespace App\Models;

use App\Traits\SearchQuery;
use Eloquent;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @mixin IdeHelperPatient
 */
class Patient extends BaseModel
{
    use SearchQuery;

    public static $relationsWithForSearch = ['images'];
    public static $searchableFields = ['name', 'file_number', 'mobile', 'phone'];
//    protected $dateFormat = 'Y-m-d H:i:s';
    protected $fillable = ['name', 'age', 'phone', 'mobile', 'file_number', 'image', 'gender', 'user_id'];

    public static function boot()
    {
        parent::boot();

        static::deleting(function (Patient $patient) {
            \DB::transaction(function () use ($patient) {
                $patient->visits->each(function (Visit $visit) {
                    $visit->delete();
                });
                $patient->payments->each(function (Payment $payment) {
                    $payment->delete();
                });
//            foreach ($item->images()->get() as $image) {
//                $imageName = Str::replace('/storage/', '', $image->image);
//                $image->delete();
//                \Storage::disk('public')->delete($imageName);
//            }
                DeletedPatient::insert($patient->withoutRelations()->toArray());
            });
        });
    }

    public function visits()
    {
        return $this->hasMany(Visit::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function files()
    {
        return $this->hasMany(PatientFile::class);
    }

    public function lastVisit()
    {
        return $this->hasMany(Visit::class)->latest()->limit(1);
    }

    public function medications(): BelongsToMany
    {
        return $this->belongsToMany(Medication::class, 'patient_medications')
            ->withPivot('dosage', 'frequency', 'start_date', 'end_date')
            ->withTimestamps();
    }
}
