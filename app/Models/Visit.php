<?php
/**
 * Created by PhpStorm.
 * User: Eng.MohammEd
 * Date: 8/10/2017
 * Time: 12:09 PM
 */

namespace App\Models;

use App\Traits\SearchQuery;

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
 */
class Visit extends \Eloquent
{
    use SearchQuery;

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

    public function services()
    {
        return $this->belongsToMany(Service::class, 'service_visits', 'visit_id', 'service_id')->withPivot('id');
    }

    public function serviceVisit()
    {
        return $this->hasMany(ServiceVisit::class, 'visit_id');
    }
}
