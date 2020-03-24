<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserBankCardResource extends JsonResource
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
            'user_id' => $this->user_id,
            'bank_code_id' => $this->bank_code_id,
            'user_name' => $this->user_name,
            'bank_name' => $this->bank_name,
            'card_number' => $this->card_number,
            'phone' => $this->phone,
            'bank' => new BankCodeResource($this->bankCode),
        ];
    }
}
