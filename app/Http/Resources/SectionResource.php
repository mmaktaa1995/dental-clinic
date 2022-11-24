<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class SectionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'section_no' => $this->section_no,
            'room_no' => $this->room_no,
            'time' => $this->time,
            'instructor_id' => $this->instructor_id,
            'course_id' => $this->course_id,
            'instructor' => InstructorResource::make($this->whenLoaded('instructor')),
            'course' => PatientResource::make($this->whenLoaded('course')),
            'created_at' => Carbon::parse($this->created_at)->toDateTimeString(),
            'updated_at' => Carbon::parse($this->updated_at)->toDateTimeString(),
        ];
    }
}
