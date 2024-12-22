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
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\PatientImage[] $images
 * @property-read int|null $images_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Visit[] $lastVisit
 * @property-read int|null $last_visit_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Payment[] $payments
 * @property-read int|null $payments_count
 * @property-read int|null $visits_count
 * @method static \Illuminate\Database\Eloquent\Builder|Patient newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Patient newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Patient query()
 * @method static \Illuminate\Database\Eloquent\Builder|Patient whereAge($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Patient whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Patient whereFileNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Patient whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Patient whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Patient whereMobile($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Patient whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Patient wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Patient whereUpdatedAt($value)
 * @mixin Eloquent
 */
class Patient extends Eloquent
{
    use SearchQuery;

    public static $relationsWithForSearch = ['images'];
    public static $searchableFields = ['name', 'file_number', 'mobile', 'phone'];
//    protected $dateFormat = 'Y-m-d H:i:s';
    protected $fillable = ['name', 'age', 'phone', 'mobile', 'file_number', 'image'];

    public static function boot()
    {
        parent::boot();

        static::deleting(function (Patient $patient) {
            try {
                \DB::beginTransaction();
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
                \DB::commit();
            } catch (\Exception $exception) {
                \DB::rollBack();
                throw new $exception;
            }
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

    public function images()
    {
        return $this->hasMany(PatientImage::class, 'patient_id');
    }

    public function lastVisit()
    {
        return $this->hasMany(Visit::class)->latest()->limit(1);
    }
}
