<?php

namespace App\Http\Requests\Api;


class LoginUserRequest extends FormRequest
{
    public function rules()
    {
        return [
            'phone' => 'required|numeric',
            'password'  => 'required|min:4',
        ];
    }
}
