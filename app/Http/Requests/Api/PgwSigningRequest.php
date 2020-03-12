<?php

namespace App\Http\Requests\Api;


class PgwSigningRequest extends FormRequest
{
    public function rules()
    {
        return [
            'sms_verify_code' => 'required|min:2',
        ];
    }

    public function attributes()
    {
        return [
            'sms_verify_code' => '短信验证码',
        ];
    }
}
