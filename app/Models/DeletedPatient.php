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
 * App\Models\DeletedPatient
 *
 * @property int $id
 * @property string|null $name
 * @property string|null $age
 * @property string|null $phone
 * @property string|null $mobile
 * @property string|null $file_number
 * @property string|null $image
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Payment[] $payments
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Visit[] $visits
 * @method static \Illuminate\Database\Eloquent\Builder|DeletedPatient newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DeletedPatient newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DeletedPatient query()
 * @method static \Illuminate\Database\Eloquent\Builder|DeletedPatient whereAge($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DeletedPatient whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DeletedPatient whereFileNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DeletedPatient whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DeletedPatient whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DeletedPatient whereMobile($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DeletedPatient whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DeletedPatient wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DeletedPatient whereUpdatedAt($value)
 * @mixin Eloquent
 */
class DeletedPatient extends Eloquent
{
    public static $relationsWithForSearch = [];
    use SearchQuery;

    protected $fillable = ['name', 'age', 'phone', 'mobile', 'file_number', 'image'];


    public function visits()
    {
        return self::hasMany(Visit::class, 'patient_id')->withTrashed();
    }

    public function payments()
    {
        return $this->hasMany(Payment::class, 'patient_id')->withTrashed();
    }

    public function images()
    {
        return self::hasMany(PatientImage::class, 'patient_id', 'id');
    }
}
