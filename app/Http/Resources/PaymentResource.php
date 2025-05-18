<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property-read int $total_remaining_amount
 * @property-read ?string $latest_payment_date
 * @property-read int $latest_payment
 * @property-read ?int $total_amount
 * @mixin \App\Models\Payment
 */
class PaymentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'patient_id' => $this->patient_id,
            'patient' => PatientResource::make($this->whenLoaded('patient')),
            'visit_id' => $this->visit_id,
            'visit' => VisitResource::make($this->whenLoaded('visit')),
            'amount' => $this->amount ?: 0,
            'remaining_amount' => $this->remaining_amount ?: 0,
            'date' => $this->date,
            'status' => $this->status,
            'created_at' => $this->created_at?->format('Y-m-d H:i:s'),
        ];
    }
}
