<?php

namespace App\Http\Requests\Api;


class LoanOrderStoreRequest extends FormRequest
{
    public function rules()
    {
        return [
            'total_amount'  => 'required|numeric',
            'staging'       => 'required|numeric',
            'interest_rate' => 'required|numeric',
        ];
    }
}
