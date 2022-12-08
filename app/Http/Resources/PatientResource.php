<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin \App\Models\Patient
 */
class PatientResource extends JsonResource
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
            'name' => $this->name,
            'age' => $this->age,
            'phone' => $this->phone,
            'mobile' => $this->mobile,
            'file_number' => $this->file_number,
            'image' => $this->image,
            'images' => $this->images,
            'created_at' => Carbon::parse(strtotime($this->created_at))->format('Y-m-d'),
        ];
    }
}
