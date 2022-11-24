<?php
/**
 * Created by PhpStorm.
 * User: Eng.MohammEd
 * Date: 8/10/2017
 * Time: 12:09 PM
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ServiceVisit extends Model
{
    protected $fillable = ['service_id', 'visit_id', 'date'];
    protected $dates = ['date'];

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function visit()
    {
        return $this->belongsTo(Visit::class);
    }
}
