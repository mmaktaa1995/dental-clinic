<?php
/**
 * Created by PhpStorm.
 * User: Eng.MohammEd
 * Date: 8/10/2017
 * Time: 12:09 PM
 */

namespace App\Models;

class PatientImage extends \Eloquent
{
    protected $fillable = ['image', 'patient_id'];

}
