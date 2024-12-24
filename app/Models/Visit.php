<?php

namespace App\Models;

use App\Traits\SearchQuery;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @mixin IdeHelperVisit
 */
class Visit extends BaseModel
{
    use SearchQuery, SoftDeletes;

    public static $relationsWithForSearch = ['patient', 'payment'];
    public static $searchInRelations = ['patient:name'];
    public static $searchableFields = ['date', 'notes'];
    public static $dateColumnFiltered = 'date';
    protected $fillable = ['patient_id', 'date', 'notes', 'user_id']; //@todo revert amount when migration
    protected $appends = ['amount'];//@todo comment amount when migration

    public function getDateAttribute($value)
    {
        return date('Y-m-d', strtotime($value));
    }

    public function getAmountAttribute()
    {
        if (!$this->relationLoaded('payment')) {
            abort(422, "`payment` relation is not loaded!");
        }
        return $this->payment ? $this->payment->amount : 0;
    }

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }
}
