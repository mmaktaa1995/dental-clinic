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
    protected $fillable = ['name', 'price', 'user_id'];

    public function payment(): BelongsToMany
    {
        return $this->belongsToMany(Payment::class, 'service_payments', 'service_id', 'payment_id');
    }

    public function servicePayment(): HasMany
    {
        return $this->hasMany(ServicePayment::class, 'service_id');
    }
}
