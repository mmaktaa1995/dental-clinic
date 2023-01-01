<?php
/**
 * Created by PhpStorm.
 * User: Eng.Mohammad
 * Date: 8/10/2017
 * Time: 12:09 PM
 */

namespace App\Models;


use App\Traits\SearchQuery;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\Payment
 *
 * @property int $id
 * @property string|null $amount
 * @property string|null $remaining_amount
 * @property string $date
 * @property int $patient_id
 * @property int $visit_id
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Patient $patient
 * @property-read Payment|null $payment
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\ServicePayment[] $servicePayment
 * @property-read int|null $service_payment_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Service[] $services
 * @property-read int|null $services_count
 * @method static \Illuminate\Database\Eloquent\Builder|Payment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Payment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Payment query()
 * @method static \Illuminate\Database\Eloquent\Builder|Payment whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Payment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Payment whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Payment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Payment wherePatientId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Payment whereRemainingAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Payment whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Payment whereVisitId($value)
 * @mixin \Eloquent
 */
class Payment extends \Eloquent
{
    use SearchQuery, SoftDeletes;

    public static $relationsWithForSearch = ['patient'];
    public static $columnsToSelect = [];
    public static $searchInRelations = ['patient:name,file_number'];
    protected $fillable = ['visit_id', 'date', 'amount', 'remaining_amount', 'patient_id'];
//    protected $appends = ['latestPaymentDate'];
//
//    public function getLatestPaymentDateAttribute()
//    {
//        return (new self())->newQuery()->where('patient_id', $this->patient_id)->latest('date')->limit(1)->value('date');
//    }


    public function getDateAttribute($value)
    {
        return date('Y-m-d', strtotime($value));
    }

    public function visit()
    {
        return $this->belongsTo(Visit::class);
    }

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function services()
    {
        return $this->belongsToMany(Service::class, 'service_payments', 'payment_id', 'service_id')->withPivot('id');
    }

    public function servicePayment()
    {
        return $this->hasMany(ServicePayment::class, 'payment_id');
    }
}
