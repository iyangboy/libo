<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserInfoResource extends JsonResource
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
            'id'                => $this->id,
            'province'          => $this->province,
            'city'              => $this->city,
            'district'          => $this->district,
            'address'           => $this->address,
            'occupation'        => $this->occupation,
            'phone_long_time'   => $this->phone_long_time,
            'overdue_state'     => $this->overdue_state,
            'social_insurance'  => $this->social_insurance,
            'accumulation_fund' => $this->accumulation_fund,
            'monthly_pay'       => $this->monthly_pay,
        ];
    }
}
