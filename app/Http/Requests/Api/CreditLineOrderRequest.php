<?php

namespace App\Http\Requests\Api;


class CreditLineOrderRequest extends FormRequest
{
    public function rules()
    {
        return [
            'product_id'     => 'required',
            // 'payment_method' => 'required',
        ];
    }
}
