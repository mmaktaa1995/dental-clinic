<?php

namespace App\Models;

class Service extends \Eloquent
{
    protected $fillable = ['name', 'price'];

    public function visit()
    {
        return $this->belongsToMany(Visit::class, 'service_visits', 'service_id', 'visit_id');
    }

    public function serviceVisit()
    {
        return $this->hasMany(ServiceVisit::class, 'service_id');
    }
}
