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
 * @mixin IdeHelperServicePayment
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
