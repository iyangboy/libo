<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CreditLineOrderResource extends JsonResource
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
            'id' => $this->id,
            'no' => $this->no,
            'product_id' => $this->product_id,
            'total_amount' => $this->total_amount,
            'user_id' => $this->user_id,
        ];
    }
}
