<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin \App\Models\Expense
 */
class ExpenseResource extends JsonResource
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
            'amount' => $this->amount,
            'date' => $this->date,
            'description' => $this->description,
            'name' => $this->name,
            'created_at' => $this->created_at?->format('Y-m-d H:i:s'),
        ];
    }
}
