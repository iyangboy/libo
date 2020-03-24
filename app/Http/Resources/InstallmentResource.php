<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class InstallmentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        // return parent::toArray($request);
        return [
            'id'           => $this->id,
            'type'         => $this->type,
            'no'           => $this->no,
            'user_id'      => $this->user_id,
            'order_id'     => $this->order_id,
            'total_amount' => $this->total_amount,
            'count'        => $this->count,
            'fee_rate'     => $this->fee_rate,
            'fine_rate'    => $this->fine_rate,
            'status'       => $this->status,
            'created_at'   => $this->created_at,
            'items'        => InstallmentItemResource::collection($this->items),
        ];
    }
}
