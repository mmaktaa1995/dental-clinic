<?php
/**
 * Created by PhpStorm.
 * User: Eng.MohammEd
 * Date: 8/10/2017
 * Time: 12:09 PM
 */

namespace App\Models;

/**
 * @mixin IdeHelperPatientFile
 */
class PatientFile extends \Eloquent
{
    protected $fillable = ['file', 'patient_id', 'type'];

}
