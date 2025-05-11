<?php

namespace App\Models;


use App\Traits\SearchQuery;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @mixin IdeHelperPayment
 */
class Payment extends BaseModel
{
    use SoftDeletes;

    protected $fillable = ['visit_id', 'date', 'amount', 'remaining_amount', 'patient_id', 'user_id', 'status'];
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

    public function visit(): BelongsTo
    {
        return $this->belongsTo(Visit::class);
    }

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function services(): BelongsToMany
    {
        return $this->belongsToMany(Service::class, 'service_payments', 'payment_id', 'service_id')->withPivot('id');
    }

    public function servicePayment(): HasMany
    {
        return $this->hasMany(ServicePayment::class, 'payment_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id');
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class);
    }

    public function scopeWithLatestPayment($query): void
    {
        $query->addSelect([
            'latest_payment_date' => static::select('created_at')
                ->whereColumn('patient_id', 'payments.patient_id')
                ->where('amount', '>', 0)
                ->orderByDesc('id')
                ->limit(1),
            'latest_payment' => static::select('amount')
                ->whereColumn('patient_id', 'payments.patient_id')
                ->where('amount', '>', 0)
                ->orderByDesc('id')
                ->limit(1),
        ]);
    }

    public function scopeWithTotalRemainingAmount($query): void
    {
        $query->addSelect([
            'total_remaining_amount' => static::selectRaw('SUM(remaining_amount)')
                ->whereColumn('patient_id', 'payments.patient_id')
                ->groupBy('payments.patient_id')
                ->where('remaining_amount', '>', 0)
                ->limit(1),
        ]);
    }
}
