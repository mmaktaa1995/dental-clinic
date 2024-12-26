<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;

/**
 * @mixin \App\Models\PatientRecord
 */
class SymptomResource extends JsonResource
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
            'symptoms' => $this->symptoms,
            'record_date' => Carbon::createFromTimestamp($this->record_date)->format("Y-m-d H:i:s"),
        ];
    }
}
