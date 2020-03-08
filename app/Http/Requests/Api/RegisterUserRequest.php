<?php

namespace App\Http\Requests\Api;

class RegisterUserRequest extends FormRequest
{
    // 用户注册
    public function rules()
    {
        return [
            'phone'     => 'required|numeric|unique:users',
            // 'email'     => 'required|email|unique:users',
            'name'      => 'required|min:3',
            'password'  => 'required|min:4',
        ];
    }
}
