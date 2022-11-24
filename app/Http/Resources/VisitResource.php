<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin \App\Models\Visit
 */
class VisitResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'patient_id' => $this->patient_id,
//            'services' => $this->services,
            'patient' => PatientResource::make($this->whenLoaded('patient')),
            'amount' => $this->amount,
            'date' => $this->date,
            'notes' => $this->notes,
            'created_at' => Carbon::parse(strtotime($this->created_at))->format('Y-m-d'),
        ];
    }
}
