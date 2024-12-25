<?php

namespace App\Models;

use App\Traits\SearchQuery;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @mixin IdeHelperService
 */
class Service extends BaseModel
{
    use SearchQuery;

    public static $searchableFields = ['name', 'price'];
    public static $dateColumnFiltered = 'created_at';
    protected $fillable = ['name', 'price', 'user_id'];
    protected $appends = ['original_price'];

    public function getOriginalPriceAttribute()
    {
        $exchangeRate = AppConfig::getByKey('usd_exchange');
        return $this->price * $exchangeRate;
    }

    public function payment(): BelongsToMany
    {
        return $this->belongsToMany(Payment::class, 'service_payments', 'service_id', 'payment_id');
    }

    public function servicePayment(): HasMany
    {
        return $this->hasMany(ServicePayment::class, 'service_id');
    }
}
