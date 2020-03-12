<?php

namespace App\Http\Requests\Api;

class UserSetIdCardRequest extends FormRequest
{
    public function rules()
    {
        return [
            'id_card'   => 'required|string',
            'user_name' => 'required|string|min:2',
        ];
    }

    public function attributes()
    {
        return [
            'id_card'   => '身份证号',
            'user_name' => '用户名',
        ];
    }
}
