<?php

namespace App\Http\Resources;

use App\Models\Appointment;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin \App\Models\Appointment
 */
class AppointmentResource extends JsonResource
{
    public static $wrap = 'appointments';

    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'patient_id' => $this->patient_id,
            'patient' => PatientResource::make($this->whenLoaded('patient')),
            'date' => Carbon::parse($this->date),
            'start' => Carbon::parse($this->date),
            'end' => Carbon::parse($this->date)->addMinutes(Appointment::WAITING_MINUTES),
            'notes' => $this->notes,
            'isPast' => Carbon::parse($this->date) < now(),
            'className' => Carbon::parse($this->date) < now() ? ' bg-red-500 hover:bg-red-600 transition-colors' : ' bg-teal-500 hover:bg-teal-600 transition-colors',
        ];
    }
}
