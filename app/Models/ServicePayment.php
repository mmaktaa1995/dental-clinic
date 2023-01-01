<?php
/**
 * Created by PhpStorm.
 * User: Eng.MohammEd
 * Date: 8/10/2017
 * Time: 12:09 PM
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;

/**
 * App\Models\ServicePayment
 *
 * @property int $id
 * @property int $payment_id
 * @property int $service_id
 * @property int|null $price
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Payment $payment
 * @property-read \App\Models\Service $service
 * @method static \Illuminate\Database\Eloquent\Builder|ServicePayment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ServicePayment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ServicePayment query()
 * @method static \Illuminate\Database\Eloquent\Builder|ServicePayment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ServicePayment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ServicePayment wherePaymentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ServicePayment wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ServicePayment whereServiceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ServicePayment whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class ServicePayment extends Pivot
{
    protected $fillable = ['service_id', 'payment_id', 'price', 'notes'];

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function payment()
    {
        return $this->belongsTo(Payment::class);
    }
}
