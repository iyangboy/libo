<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CreditLineResource extends JsonResource
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
            'market_price' => $this->market_price,
            'price' => $this->price,
            'title' => $this->title,
            'image' => $this->image,
            'quota_min' => $this->quota_min,
            'quota_max' => $this->quota_max,
        ];
    }
}
