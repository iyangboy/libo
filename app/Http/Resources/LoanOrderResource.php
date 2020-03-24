<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class LoanOrderResource extends JsonResource
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
            'id'            => $this->id,
            'no'            => $this->no,
            'user_id'       => $this->user_id,
            'total_amount'  => $this->total_amount,
            'ship_status'   => $this->ship_status,
            'staging'       => $this->staging,
            'interest_rate' => $this->interest_rate,
            'created_at'    => $this->created_at,
            'updated_at'    => $this->updated_at,
            'user'          => new PrivateUserResource($this->user),
            'installment'   => new InstallmentResource($this->installment),
        ];
    }
}
