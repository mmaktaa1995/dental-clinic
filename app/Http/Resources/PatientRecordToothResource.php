<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin \App\Models\PatientRecordTooth
 */
class PatientRecordToothResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        info("ID: ".$this->id. ": {$this->is_treated}");
        return [
            'id' => $this->id,
            'patient_record_id' => $this->patient_record_id,
            'tooth_id' => $this->tooth_id,
            'is_treated' => $this->is_treated,
            'description' => $this->description,
        ];
    }
}
