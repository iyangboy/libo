<?php

namespace App\Http\Requests\Api;

class PhoneCodeRequest extends FormRequest
{
    public function rules()
    {
        return [
            // 'phone' => 'required|unique:users,phone',
            'phone' => 'required',
        ];
    }

    public function attributes()
    {
        return [
            'phone'   => '手机号',
        ];
    }
}
