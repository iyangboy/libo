<?php

namespace App\Http\Requests\Api;

class LoginPhoneCodeRequest extends FormRequest
{
    public function rules()
    {
        return [
            'phone'             => 'required|numeric',
            'verification_key'  => 'required',
            'verification_code' => 'required',
        ];
    }
}
