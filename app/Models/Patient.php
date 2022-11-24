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

/**
 * App/Models/Patient
 *
 * @property int $id
 * @property string $name
 * @property string $age
 * @property string $phone
 * @property string $mobile
 * @property string $file_number
 * @property string $image
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Contracts\Pagination\LengthAwarePaginator getAll($params)
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Visit[] $visits
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\PatientImage[] $patientImages
 */
class Patient extends Eloquent
{
    use SearchQuery;

    protected $dateFormat = 'Y-m-d H:i:s';

    public static $relationsWithForSearch = [];
    public static $searchableFields = ['name', 'file_number', 'mobile', 'phone'];

    protected $fillable = ['name', 'age', 'phone', 'mobile', 'file_number', 'image'];

    public static function boot()
    {
        parent::boot();

        static::deleting(function (self $item) {
            foreach ($item->visits()->get() as $visit) {
                $visit->delete();
            }
            foreach ($item->images()->get() as $image) {
                $image->delete();
            }

            DeletedPatient::create($item->toArray());
        });
    }

    public function visits()
    {
        return $this->hasMany(Visit::class);
    }

    public function lastVisit()
    {
        return $this->hasMany(Visit::class)->latest()->limit(1);
    }

    public function images()
    {
        return self::hasMany(PatientImage::class, 'patient_id', 'id');
    }
}
