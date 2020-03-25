<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PrivateUserResource extends JsonResource
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
            'id'              => $this->id,
            'name'            => $this->name,
            'phone'           => $this->phone,
            'id_card'         => $this->id_card,
            'real_name'       => $this->real_name,
            'created_at'      => $this->created_at,
            'predict_money'   => $this->predict_money,
            'card_front_path' => $this->card_front_path,
            'card_back_path'  => $this->card_back_path,
            'user_relation'   => new UserRelationResource($this->userRelation),
            'user_info'       => new UserInfoResource($this->userInfo),
            'user_bank_card'  => new UserBankCardResource($this->userBankCard),
            'loan_success'    => new CreditLineOrderResource($this->creditLineOrderSuccess),
        ];
    }
}
