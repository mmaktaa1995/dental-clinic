<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin \App\Models\Payment
 */
class PaymentResource extends JsonResource
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
            'patient' => PatientResource::make($this->whenLoaded('patient')),
            'visit_id' => $this->visit_id,
            'visit' => VisitResource::make($this->whenLoaded('visit')),
            'amount' => $this->amount,
            'remaining_amount' => $this->remaining_amount,
            'total_remaining_amount' => $this->total_remaining_amount,
            'latest_payment_date' => $this->latest_payment_date,
            'latest_payment' => $this->latest_payment,
            'date' => $this->date,
            'created_at' => Carbon::parse(strtotime($this->created_at))->format('Y-m-d'),
        ];
    }
}
