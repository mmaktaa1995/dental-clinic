<?php

namespace App\Models;

use App\Traits\SearchQuery;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @mixin IdeHelperService
 */
class Service extends BaseModel
{
    use HasFactory;

    protected $fillable = ['name', 'price', 'user_id'];

    /**
     * Get the user that owns the service.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * The payments that belong to the service.
     */
    public function payments(): BelongsToMany
    {
        return $this->belongsToMany(Payment::class, 'service_payments', 'service_id', 'payment_id')
            ->withPivot('price', 'notes')
            ->withTimestamps();
    }

    /**
     * Get the service payments for the service.
     */
    public function servicePayments(): HasMany
    {
        return $this->hasMany(ServicePayment::class, 'service_id');
    }
}
