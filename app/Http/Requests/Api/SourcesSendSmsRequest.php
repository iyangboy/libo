<?php

namespace App\Http\Requests\Api;

class SourcesSendSmsRequest extends FormRequest
{
    public function rules()
    {
        return [
            // 'phone' => 'required|unique:users,phone',
            'phone'   => 'required',
            'source'  => 'required',
        ];
    }

    public function attributes()
    {
        return [
            'phone'   => '手机号',
            'source'  => '来源',
        ];
    }
}
