<?php
/**
 * Created by PhpStorm.
 * User: Eng.MohammEd
 * Date: 8/10/2017
 * Time: 12:09 PM
 */

namespace App\Models;

use App\Traits\SearchQuery;
use Eloquent;

class DeletedPatient extends Eloquent
{
    public static $relationsWithForSearch = [];
    use SearchQuery;

    protected $fillable = ['name', 'age', 'phone', 'mobile', 'file_number', 'image'];


    public function visits()
    {
        return self::hasMany(Visit::class, 'user_id', 'id');
    }

    public function images()
    {
        return self::hasMany(PatientImage::class, 'patient_id', 'id');
    }
}
