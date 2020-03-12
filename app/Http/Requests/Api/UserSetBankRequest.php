<?php

namespace App\Http\Requests\Api;


class UserSetBankRequest extends FormRequest
{
    public function rules()
    {
        return [
            'user_name'    => 'required|string',
            'bank_name'    => '',
            'phone'        => 'required|string',
            'bank_code_id' => 'required',
            'card_number'  => 'required|string',
            'address'      => 'string',
        ];
    }

    public function attributes()
    {
        return [
            'user_name'    => '用户名',
            'bank_name'    => '银行名称',
            'phone'        => '手机号',
            'bank_code_id' => '所属银行',
            'card_number'  => '银行卡号',
            'address'      => '归属地',
        ];
    }
}
