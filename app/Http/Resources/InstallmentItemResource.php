<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class InstallmentItemResource extends JsonResource
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
            'id'             => $this->id,
            'installment_id' => $this->installment_id,
            'sequence'       => $this->sequence,
            'base'           => $this->base,
            'fee'            => $this->fee,
            'fine'           => $this->fine,
            'due_date'       => $this->due_date,
            'paid_at'        => $this->paid_at,
            'payment_method' => $this->payment_method,
            'payment_no'     => $this->payment_no,
            'refund_status'  => $this->refund_status,
            'created_at'     => $this->created_at,
            'updated_at'     => $this->updated_at,
        ];
    }
}
