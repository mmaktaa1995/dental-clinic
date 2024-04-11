<?php
/**
 * Created by PhpStorm.
 * User: Eng.MohammEd
 * Date: 8/10/2017
 * Time: 12:09 PM
 */

namespace App\Models;

use App\Traits\SearchQuery;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App/Models/Patient
 *
 * @property int $id
 * @property string $patient_id
 * @property string $amount
 * @property string $date
 * @property string $notes
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Contracts\Pagination\LengthAwarePaginator getAll($params)
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\patient $patient
 * @property-read \App\Models\Payment|null $payment
 * @method static \Illuminate\Database\Eloquent\Builder|Visit newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Visit newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Visit query()
 * @method static \Illuminate\Database\Eloquent\Builder|Visit whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Visit whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Visit whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Visit whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Visit wherePatientId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Visit whereUpdatedAt($value)
 * @mixin \Eloquent
 * @psalm-template \Eloquent
 */
class Visit extends \Eloquent
{
    use SearchQuery, SoftDeletes;

    public static $relationsWithForSearch = ['patient', 'payment'];
    public static $searchInRelations = ['patient:name'];
    public static $searchableFields = ['date', 'notes'];
    public static $dateColumnFiltered = 'date';
    protected $fillable = ['patient_id', 'date', 'notes']; //@todo revert amount when migration
    protected $appends = ['amount'];//@todo comment amount when migration

    public function getDateAttribute($value)
    {
        return date('Y-m-d', strtotime($value));
    }

    public function getAmountAttribute()
    {
        return $this->payment ? $this->payment->amount : 0;
    }

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }
}
