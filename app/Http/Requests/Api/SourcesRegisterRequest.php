<?php

namespace App\Http\Requests\Api;

class SourcesRegisterRequest extends FormRequest
{
    public function rules()
    {
        return [
            'phone'             => 'required|unique:users,phone',
            // 'phone'             => 'required',
            'source'            => 'required',
            'verification_key'  => 'required|string',
            'verification_code' => 'required|string',
        ];
    }

    public function attributes()
    {
        return [
            'phone'             => '手机号',
            'source'            => '来源',
            'verification_key'  => 'key',
            'verification_code' => 'code',
        ];
    }
}
