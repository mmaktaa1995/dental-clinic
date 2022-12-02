<?php
/**
 * Created by PhpStorm.
 * User: Eng.MohammEd
 * Date: 8/10/2017
 * Time: 12:09 PM
 */

namespace App\Models;


use App\Traits\SearchQuery;

class Payment extends \Eloquent
{
    use SearchQuery;

    public static $relationsWithForSearch = ['patient'];
    public static $columnsToSelect = [];
    public static $searchInRelations = ['patient:name,file_number'];
    protected $fillable = ['visit_id', 'date', 'amount', 'remaining_amount', 'patient_id'];
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

    public function visit()
    {
        return $this->belongsTo(Visit::class);
    }

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }
}
