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
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'service_payments';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['service_id', 'payment_id', 'price', 'notes'];

    /**
     * Get the service that owns the service payment.
     */
    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    /**
     * Get the payment that owns the service payment.
     */
    public function payment()
    {
        return $this->belongsTo(Payment::class);
    }
}
