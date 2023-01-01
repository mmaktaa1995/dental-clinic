<?php

namespace App\Models;

use App\Traits\SearchQuery;

/**
 * App\Models\Service
 *
 * @property int $id
 * @property string $name
 * @property int $price
 * @property int $original_price
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Payment[] $payment
 * @property-read int|null $payment_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\ServicePayment[] $servicePayment
 * @property-read int|null $service_payment_count
 * @method static \Illuminate\Database\Eloquent\Builder|Service newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Service newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Service query()
 * @method static \Illuminate\Database\Eloquent\Builder|Service whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Service whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Service whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Service wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Service whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Service extends \Eloquent
{
    use SearchQuery;

    public static $searchableFields = ['name', 'price'];
    public static $dateColumnFiltered = 'created_at';
    protected $fillable = ['name', 'price'];
    protected $appends = ['original_price'];

    public function getOriginalPriceAttribute()
    {
        $exchangeRate = AppConfig::getByKey('usd_exchange');
        return $this->price * $exchangeRate;
    }

    public function payment()
    {
        return $this->belongsToMany(Payment::class, 'service_payments', 'service_id', 'payment_id');
    }

    public function servicePayment()
    {
        return $this->hasMany(ServicePayment::class, 'service_id');
    }
}
