<?php

namespace App\Models;

use App\Traits\SearchQuery;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @mixin IdeHelperVisit
 */
class Visit extends BaseModel
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['patient_id', 'date', 'notes', 'user_id']; //@todo revert amount when migration
    protected $appends = ['amount'];//@todo comment amount when migration

    public function getDateAttribute($value): string
    {
        return date('Y-m-d', strtotime($value));
    }

    public function getAmountAttribute(): int|null
    {
        if (!$this->relationLoaded('payment')) {
            logger( "`payment` relation is not loaded!");
            return 0;
        }
        return $this->payment?->amount;
    }

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function payment(): HasOne
    {
        return $this->hasOne(Payment::class);
    }
}
