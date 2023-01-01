<?php
/**
 * Created by PhpStorm.
 * User: Eng.MohammEd
 * Date: 8/10/2017
 * Time: 12:09 PM
 */

namespace App\Models;

/**
 * App\Models\PatientImage
 *
 * @property int $id
 * @property int $patient_id
 * @property string|null $image
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|PatientImage newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PatientImage newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PatientImage query()
 * @method static \Illuminate\Database\Eloquent\Builder|PatientImage whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PatientImage whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PatientImage whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PatientImage wherePatientId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PatientImage whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class PatientImage extends \Eloquent
{
    protected $fillable = ['image', 'patient_id'];

}
